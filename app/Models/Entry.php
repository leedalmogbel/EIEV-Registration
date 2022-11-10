<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    use HasFactory;

    protected $fillable = [
        'race_id',
        'horse_id',
        'rider_id',
        'user_id',
        'status',
        'data',
        'sequence',
    ];

    protected $primaryKey = 'entry_id';

    public function user () {
        return $this->belongsTo('App\Models\User', 'user_id', 'user_id');
    }
    
    public function race () {
        return $this->belongsTo('App\Models\Race', 'race_id', 'race_id');
    }
    
    public function rider () {
        return $this->belongsTo('App\Models\Rider', 'rider_id', 'rider_id');
    }
    
    public function horse () {
        return $this->belongsTo('App\Models\Horse', 'horse_id', 'horse_id');
    }
}
