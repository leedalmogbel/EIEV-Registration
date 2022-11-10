<?php

namespace App\Services;

use App\Models\Race as RaceModel;
use App\Validators\Race as RaceValidator;

use App\Models\RaceStable as RaceStableModel;

use App\Exceptions\MsgException;
use App\Exceptions\FieldException;

class Race extends RaceModel {
    use Base;

    const MODEL_VALIDATOR = 'App\Validators\Race';
    const PAGINATION_ENTRY = 15;

    protected $searchables = ['title'];

    /**
     * hook: called before the actual create
     *
     */
    public function createPre() {
        $this->createEvent($this);
    }
    
    public function updatePre(&$object) {
        $this->createEvent($object);
    }

    /**
     * hook: called after the actual create
     *
     */
    public function createPost() {
        // get stable specific
        $stables = request()->input('stable_specific');
        if (empty($stables)) {
            return;
        }

        // create race stable
        return $this->createRaceStable($stables, $this);
    }
    
    public function updatePost(&$object) {
        // to be sure, remove all race stables
        RaceStableModel::where('race_id', $object->race_id)->delete();
        
        // get stable specific
        $stables = request()->input('stable_specific');
        if (empty($stables)) {
            return;
        }

        // create race stable
        return $this->createRaceStable($stables, $object);
    }

    /**
     * creates race stable during race create
     *
     * @params array stable data
     * @return void
     */
    private function createRaceStable($stables, &$object) {
        // check if stable specific
        $raceId = $object->race_id;
        
        if (
            !$raceId
            || !isset($stables['stable_id'])
            || !is_array($stables['stable_id'])
            || empty($stables['stable_id'])
        ) {
            // do nothing
            return;
        }

        // it would we nice if we have a validator here
        foreach ($stables['stable_id'] as $key => $stableId) {
            if (
                !isset($stables['entryCount'][$key])
                || !is_numeric($stables['entryCount'][$key])
                ||  $stables['entryCount'][$key] < 1
            ) {
                // not properly setup
                continue;
            }

            $raceStable = new RaceStableModel();
            $raceStable->race_id = $raceId;
            $raceStable->stable_id = $stableId;
            $raceStable->entryCount = $stables['entryCount'][$key];

            $raceStable->save();
        }
    }

    /**
     * creates event from race service
     *
     * @return void
     */
    private function createEvent(&$object) {
        // check if we have to create new event
        if ($object->event_id == 'new' ) {
            // create event via service provider
            $event = ServiceProvider::event($object->event)->createNew();
            // set created event id to race event id
            $object->event_id = $event->event_id;
        }
        
        // unset event object
        unset($object->event);
    }
}
