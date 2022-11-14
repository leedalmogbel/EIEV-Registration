<?php

namespace App\Services;

use App\Models\Entry as EntryModel;
use App\Models\RaceStable as RaceStableModel;
use App\Validators\Entry as EntryValidator;

use App\Exceptions\MsgException;

class Entry extends EntryModel {
    use Base;

    const MODEL_VALIDATOR = 'App\Validators\Entry';
    const PAGINATION_ENTRY = 15;

    
    protected $searchables = [
        'name',
    ];

    public function createNew() {
        call_user_func([self::MODEL_VALIDATOR, 'createNew'], $this);
        $entryCount = $this->getUsersAllowedEntry($this->race_id, $this->user_id);

        $sequenceIndex = 1;
        foreach ($this->data as $sequence => $data) {
            $status = 'A';
            if ($sequenceIndex > $entryCount) {
                $status = 'P';
            }
            
            $model = new EntryModel([
                'race_id' => $this->race_id,
                'user_id' => session()->get('user')['user_id'],
                // 'user_id' => session()->get('user')->user_id,
                'horse_id' => $data['horse'],
                'rider_id' => $data['rider'],
                'sequence' => $sequence,
                'status' => $status
            ]);

            $model->save();
            $sequenceIndex++;
        }
    }

    public function getUsersAllowedEntry($raceId, $userId) {
        
        $raceStable =  RaceStableModel::where('race_id', $raceId)
            ->join('stables', 'stables.stable_id', '=', 'race_stables.stable_id')
            ->where('stables.user_id', $userId)
            ->first();

        if (empty($raceStable)
            || !isset($raceStable->entryCount)
            || !is_numeric($raceStable->entryCount)
        ) {
            return 0;
        }

        return $raceStable->entryCount;
    }
}
