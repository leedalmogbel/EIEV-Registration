<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;

    const REAL_STATUS = [
        'P' => 'Pending',
        'A' => 'Approved',
        'R' => 'Rejected'
    ];
    
    protected $primaryKey = 'season_id';
    protected $fillable = ['season', 'start_date', 'end_date'];

    public function realStatus() {
        if (!isset(self::REAL_STATUS[$this->status])) return 'Unknown';
        return self::REAL_STATUS[$this->status];
    }
}
