<?php

namespace esc\Classes;


use esc\Classes\ChatCommand;
use esc\Models\Map;
use esc\Models\Player;

class MatchSettings
{
    public $xml;
    public $filename;
    public $id;

    private static $actions = [
        'mss' => [self::class, 'updateModeScriptSettings'],
        'map' => [self::class, 'updateMap'],
        'gameinfo' => [self::class, 'updateGameInfo'],
        'filter' => [self::class, 'updateFilter']
    ];

    public function __construct(\SimpleXMLElement $xml, $id, $filename)
    {
        $this->xml = $xml;
        $this->id = $id;
        $this->filename = $filename;
    }

    public function __toString()
    {
        return sprintf('[%s,%s]', $this->filename, $this->id);
    }

    public function handle(Player $player, string ...$cmd)
    {
        $command = array_shift($cmd);

        array_unshift($cmd, $player);

        if (array_key_exists($command, self::$actions)) {
            call_user_func_array(self::$actions[$command], $cmd);
        } else {
            Log::logAddLine('MatchSettings', sprintf('Unknown update-action: %s [%s]', $command, implode(', ', $cmd)), true);
        }
    }

    public function updateGameInfo(Player $player, string $key, string $value)
    {
        $this->xml->gameinfos->{$key} = $value;
        $this->save();
    }

    public function updateFilter(Player $player, string $key, string $value)
    {
        $this->xml->filter->{$key} = $value;
        $this->save();
    }

    public function updateModeScriptSettings(Player $player, string $name, string $type, string $value)
    {
        foreach ($this->xml->mode_script_settings->setting as $element) {
            if ($element['name'] == $name) {
                $element['value'] = $value;
            }
        }

        $this->save();
    }

    public function updateMap(Player $player, string $mapId, string $enabledString)
    {
        $map = Map::whereId($mapId)->first();

        if ($enabledString == '1') {
            $mapBranch = $this->xml->addChild('map');
            $mapBranch->addChild('file', $map->filename);
            $mapBranch->addChild('ident', $map->gbx->MapUid);
            infoMessage('Added map ', $map, ' to ', secondary($this->filename))->send($player);
        } else {
            foreach ($this->xml->map as $mapNode) {
                if ($mapNode->ident == $map->gbx->MapUid) {
                    unset($mapNode[0]);
                    infoMessage('Removed map ', $map, ' from ', secondary($this->filename))->send($player);
                }
            }
        }

        //Set random start index
        $this->xml->startindex = rand(1, count($this->xml->map));

        $this->save();
    }

    public function save()
    {
        $file = Server::getMapsDirectory() . '/MatchSettings/' . $this->filename;
        $this->xml->saveXML($file);
    }
}