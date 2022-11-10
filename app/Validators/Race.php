<?php

namespace App\Validators;

use App\Models\Race as RaceModel;
use App\Exceptions\FieldException;
use App\Exceptions\MsgException;
use App\Services\ServiceProvider;

class Race {
    use Base;
    
    const MODEL_NAME = 'race';
    const SERVICE_MODEL = 'App\Models\Race';
    const VALID_STATUS = ['A', 'R', 'P'];

    protected $requiredFields = [
        'title' => 'Race Title',
        'event_id' => 'Event',
        'contact[person]' => 'Contact Person',
        'contact[number]' => 'Contact Number',
        'entryCount' => 'Entry count',
        'date' => 'Race Date',
        'opening' => 'Opening Date',
        'closing' => 'Closing Date',
    ];

    public function createPre(&$object) {
        self::validateEvent($object);
    }
    
    public function updatePre(&$object) {
        self::validateEvent($object);
    }

    private function validateEvent($object) {
        // if create new event
        if ($object->event_id == 'new') {
            $err = [];

            // validate event data first
            try {
                // create event model base on event array
                $eventModel = new \App\Models\Event($object->event);
                // validate event creation data
                Event::createNew($eventModel);
            } catch (FieldException $e) {
                $err = [];
                // manually catch it
                $errors = $e->getMessages();
                foreach ($errors as $field => $msg) {
                    $err["event[$field]"] = $msg;
                }

                throw new FieldException(json_encode($err));
            }
        }
    }
}
