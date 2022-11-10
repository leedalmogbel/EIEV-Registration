<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stable extends Model
{
    use HasFactory;
    
    const STABLE_TYPES = [
        'P' => 'Private',
        'S' => 'Sheikh'
    ];

    protected $fillable = ['name', 'type', 'user_id'];
    protected $primaryKey = 'stable_id';

    protected $casts = [
        'metadata' => 'array',
    ];

    public function realType() {
        if (!$this->type || !isset(self::STABLE_TYPES[$this->type])) {
            return 'Unknown';
        }

        return self::STABLE_TYPES[$this->type];
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
