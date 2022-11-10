<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    use HasFactory;

    const DISCIPLINE = [
        'E' => 'Endurance',
        'S' => 'Show Jumping',
        'D' => 'Dressage',
        'V' => 'Eventing',
        'R' => 'Racing',
        'T' => 'Tent Pegging',
    ];

    const VISA_TYPES = [
        'R' => 'Resident',
        'T' => 'Tourist',
        'C' => 'Citizen',
    ];

    protected $primaryKey = 'trainer_id';

    protected $fillable = [
        'emiratesId',
        'discipline',
        'feiRegistrationNo',
        'feiRegistrationDate',
        'visaType',
        'firstname',
        'lastname',
        'nationality',
        'uaeAddress',
        'homeAddress',
        'email',
        'phone',
        'mobile',
        'remarks',
    ];
    
    protected $casts = [
        'uaeAddress' => 'array',
        'homeAddress' => 'array',
    ];

    public function realVisaType() {
        if (!$this->visaType || !isset(self::VISA_TYPES[$this->visaType])) {
            return;
        }

        return self::VISA_TYPES[$this->visaType];
    }

    public function realDiscipline() {
        if (!$this->discipline || !isset(self::DISCIPLINE[$this->discipline])) {
            return;
        }

        return self::DISCIPLINE[$this->discipline];
    }
}
