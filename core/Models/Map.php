<?php

namespace esc\Models;


use esc\Classes\Log;
use esc\Controllers\MapController;
use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    protected $table = 'maps';

    protected $fillable = ['uid', 'filename', 'plays', 'author', 'last_played', 'enabled', 'mx_details', 'mx_world_record', 'gbx', 'cooldown'];

    protected $dates = ['last_played'];

    public $timestamps = false;

    public function locals()
    {
        return $this->hasMany(LocalRecord::class, 'Map');
    }

    public function dedis()
    {
        return $this->hasMany(Dedi::class, 'Map');
    }

    public function author()
    {
        return $this->hasOne(Player::class, 'id', 'author');
    }

    public function getAuthorAttribute($playerId)
    {
        return Player::whereId($playerId)->first();
    }

    public function ratings()
    {
        return $this->hasMany(Karma::class, 'Map', 'id');
    }

    public function getAverageRatingAttribute()
    {
        $mxDetails = $this->mx_details;

        if ($mxDetails && $mxDetails->RatingVoteCount > 0) {
            return $mxDetails->RatingVoteAverage;
        }

        return $this->ratings()->pluck('Rating')->average();
    }

    public function favorites()
    {
        return $this->belongsToMany(Player::class, 'map-favorites');
    }

    public function getMxDetailsAttribute($jsonMxDetails)
    {
        if ($jsonMxDetails) {
            $data = json_decode($jsonMxDetails);

            if (array_key_exists(0, $data)) {
                return $data[0];
            }
        }

        return null;
    }

    public function getMxWorldRecordAttribute($jsonMxWorldRecordDetails)
    {
        return json_decode($jsonMxWorldRecordDetails);
    }

    public function getGbxAttribute($gbxJson)
    {
        return json_decode($gbxJson);
    }

    public function canBeJuked(): bool
    {
        $lastPlayedDate = $this->last_played;

        if ($lastPlayedDate) {
            return $this->last_played->diffInSeconds() > 1800;
        }

        return true;
    }

    public function __toString()
    {
        $gbx = $this->gbx;

        if (!$gbx) {
            Log::logAddLine('Map', 'Loading missing GBX for ' . $this->filename);
            $gbx       = MapController::getGbxInformation($this->filename);
            $this->gbx = $gbx;
            $this->save();

            $gbx = json_decode($gbx);
        }

        return $gbx->Name;
    }

    public static function getByUid(string $mapUid): ?Map
    {
        if (config('database.type') == 'mysql') {
            return Map::where('gbx->MapUid', $mapUid)
                      ->get()
                      ->first();
        } else {
            return Map::all()->filter(function (Map $map) use ($mapUid) {
                return $map->gbx->MapUid == $mapUid;
            })->first();
        }
    }

    public static function getByMxId(string $mxId): ?Map
    {
        if (config('database.type') == 'mysql') {
            return Map::where('mx_details->TrackID', $mxId)
                      ->get()
                      ->first();
        } else {
            return Map::all()->filter(function (Map $map) use ($mxId) {
                $details = $map->mx_details;

                if (!$details) {
                    return false;
                }

                return $details->TrackID == $mxId;
            })->first();
        }
    }
}