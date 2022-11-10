<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'event', // fake field
        'contact',
        'title',
        'entryCount',
        'date',
        'opening',
        'closing',
        'pledge',
        'sheikhStable',
        'privateStable',
        'description',
    ];

    protected $casts = [
        'contact' => 'array',
        'pledge' => 'array',
    ];

    protected $primaryKey = 'race_id';

    public function raceStables() {
        return $this->hasMany('\App\Models\RaceStable', 'race_id', 'race_id');
    }

    public function event() {
        return $this->belongsTo('\App\Models\Event', 'event_id', 'event_id');
    }

}
