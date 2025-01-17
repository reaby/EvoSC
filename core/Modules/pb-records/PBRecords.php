<?php

namespace esc\Modules\PBRecords;

use esc\Classes\Hook;
use esc\Classes\Template;
use esc\Classes\ChatCommand;
use esc\Controllers\MapController;
use esc\Models\Dedi;
use esc\Models\LocalRecord;
use esc\Models\Map;
use esc\Models\Player;

class PBRecords
{
    /**
     * @var \Illuminate\Support\Collection
     */
    private static $targets;

    /**
     * @var \Illuminate\Support\Collection
     */
    private static $defaultTarget;

    public function __construct()
    {
        Hook::add('PlayerConnect', [self::class, 'playerConnect']);
        Hook::add('EndMatch', [self::class, 'endMatch']);
        Hook::add('BeginMatch', [self::class, 'beginMap']);
        Hook::add('PlayerLocal', [self::class, 'playerMadeLocal']);

        ChatCommand::add('/target', [self::class, 'setTargetCommand'], 'Use /target local|dedi|wr|me #id to load CPs of record to bottom widget');
        ChatCommand::add('/pb', [self::class, 'getPersonalBest'], 'Get your best time on this map.');

        self::$targets = collect();
    }

    public static function playerMadeLocal(Player $player, $record)
    {
        if (self::$targets->has($player->id) && self::$targets->get($player->id)->Score <= $record->Score) {
            //Keep better target until the player beats it.

            return;
        }

        self::$targets->put($player->id, $record);
        self::sendUpdatedTimes(MapController::getCurrentMap(), $player);
    }

    public static function playerConnect(Player $player)
    {
        self::sendUpdatedTimes(MapController::getCurrentMap(), $player);
        Template::show($player, 'pb-records.pb-cp-records');
    }

    public static function sendUpdatedTimes(Map $map, Player $player)
    {
        $target = self::getTarget($map, $player);

        if (!$target) {
            $target = self::$defaultTarget;
        }

        if ($target instanceof LocalRecord) {
            $targetString = sprintf('%d. Local  %s$z', $target->Rank, $target->player->NickName ?? $target->player->Login);
        } elseif ($target instanceof Dedi) {
            $targetString = sprintf('%d. Dedi  %s$z', $target->Rank, $target->player->NickName ?? $target->player->Login);
        } else {
            $targetString = 'unknown';
        }

        $checkpoints = $target->Checkpoints ?? '-1';

        Template::show($player, 'pb-records.set-times', compact('checkpoints', 'targetString'));
    }

    public static function endMatch(...$args)
    {
        self::$targets = collect();
    }

    public static function beginMap()
    {
        $map = MapController::getCurrentMap();

        if ($map->locals()->count() > 0) {
            $defaultTarget = $map->locals()->where('Rank', '<=', config('locals.limit'))->orderByDesc('Score')->get()->first();
        } else {
            $defaultTarget = $map->dedis()->orderByDesc('Score')->first();
        }

        if ($defaultTarget) {
            $targetString        = sprintf('%d. Dedi  %s$z', $defaultTarget->Rank, $defaultTarget->player->NickName ?? $defaultTarget->player->Login);
            self::$defaultTarget = $defaultTarget;
        } else {
            self::$defaultTarget = null;
            $checkpoints         = '-1';
            $targetString        = 'No records available.';
            Template::showAll('pb-records.set-times', compact('checkpoints', 'targetString'));

            return;
        }

        $checkpoints = $defaultTarget->Checkpoints ?? '-1';
        Template::showAll('pb-records.set-times', compact('checkpoints', 'targetString'));

        $onlinePlayers = onlinePlayers();
        $playerIds     = $onlinePlayers->pluck('id');
        $locals        = $map->locals()->whereIn('Player', $playerIds)->get()->keyBy('Player');
        $dedis         = $map->dedis()->whereIn('Player', $playerIds)->get()->keyBy('Player');

        $onlinePlayers->each(function (Player $player) use ($map, $locals, $dedis, $defaultTarget) {
            $target       = null;
            $targetString = null;

            if ($locals->has($player->id)) {
                $target       = $locals->get($player->id);
                $targetString = sprintf('%d. Local  %s$z', $target->Rank, $target->player->NickName ?? $target->player->Login);
            }

            if (!$target && $dedis->has($player->id)) {
                $target       = $dedis->get($player->id);
                $targetString = sprintf('%d. Dedi  %s$z', $target->Rank, $target->player->NickName ?? $target->player->Login);
            }

            if ($targetString) {
                $checkpoints = $target->Checkpoints ?? '-1';
                Template::show($player, 'pb-records.set-times', compact('checkpoints', 'targetString'), true);
            }
        });

        Template::executeMulticall();
    }

    /**
     * Chat-command: /pb (Display personal best)
     *
     * @param \esc\Models\Player $player
     * @param                    $cmd
     */
    public static function getPersonalBest(Player $player, $cmd)
    {
        $map   = MapController::getCurrentMap();
        $local = $map->locals()->wherePlayer($player->id)->first();
        $dedi  = $map->dedis()->wherePlayer($player->id)->first();

        $record = null;

        if ($local && !$dedi) {
            $record = $local;
        }
        if (!$local && $dedi) {
            $record = $dedi;
        }
        if ($local && $dedi) {
            $record = $dedi->Score < $local->Score ? $dedi : $local;
        }

        if (!$record) {
            infoMessage('You do not have a personal best on this track yet.')->send($player);

            return;
        }

        infoMessage('Your personal best is ', $record)->send($player);
    }

    private static function getTarget(Map $map, Player $player)
    {
        if (self::$targets->has($player->id)) {
            return self::$targets->get($player->id);
        }

        $local = $map->locals()->wherePlayer($player->id)->first();

        if ($local) {
            return $local;
        }

        return $map->dedis()->wherePlayer($player->id)->first();
    }

    public static function setTargetCommand(Player $player, $cmd, $dediOrLocal = null, $recordId = null)
    {
        if (!$dediOrLocal) {
            infoMessage('You must specify ', secondary('local'), ', ', secondary('dedi'), ', ', secondary('wr'), ', ', secondary('me'), ' as first and the id of the record as second parameter.')
                ->send($player);

            return;
        }

        $map = MapController::getCurrentMap();

        switch ($dediOrLocal) {
            case 'me':
            case 'reset':
                $record = self::getTarget(MapController::getCurrentMap(), $player);
                break;

            case 'wr':
                $record = $map->dedis()->whereRank(1)->first();
                break;

            case 'dedi':
                $record = $map->dedis()->whereRank($recordId ?? 1)->first();
                break;

            case 'local':
                $record = $map->locals()->whereRank($recordId ?? 1)->first();
                break;

            default:
                infoMessage('You must specify "local", "dedi", "wr" or "me" as first parameter.')->send($player);
                $record = null;
                break;
        }

        if (!$record) {
            warningMessage('Unknown record selected.')->send($player);

            return;
        }

        if (!isset($record->Checkpoints)) {
            warningMessage('Record has no saved checkpoints.')->send($player);

            return;
        }

        self::$targets->put($player->id, $record);

        infoMessage('New checkpoints target: ', $record)->send($player);

        self::sendUpdatedTimes(MapController::getCurrentMap(), $player);
    }
}