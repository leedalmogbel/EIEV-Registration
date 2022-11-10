<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    
    const EVENT_LOCATIONS = [
        'Al Wathba, UAE',
        'Bouthib, UAE',
        'Dubai, UAE'
    ];

    const EVENT_COUNTRIES = ['UAE' => 'United Arab Emirates'];
    
    protected $primaryKey = 'event_id';
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'season_id',
        'location',
        'country',
        'description',
    ];

    public function realCountry() {
        if (!$this->country || !isset(self::EVENT_COUNTRIES[$this->country]))
            return '';

        return self::EVENT_COUNTRIES[$this->country];
    }
    
    /** RELATIONAL METHODS
      ================================================*/
    public function season() {
        return $this->belongsTo('App\Models\Season', 'season_id', 'season_id');
    }
}
