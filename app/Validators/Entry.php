<?php

namespace App\Validators;

use App\Models\Entry as EntryModel;
use App\Exceptions\FieldException;
use App\Exceptions\MsgException;

class Entry {
    use Base;
    
    const MODEL_NAME = 'entry';
    const SERVICE_MODEL = 'App\Models\Entry';
    const VALID_STATUS = ['A', 'R', 'P'];
    
    protected $requiredFields = [
        'race_id' => 'Race',
    ];

    protected $modelShouldExist = [
        'race_id' => [
            'Race',
            \App\Services\Race::class,
        ],
    ];

    public function createPre($object, $class) {
        self::dynamicValidation($object, $class);
    }

    private function dynamicValidation($object, &$class) {
        if (!request()->input('data') || !is_array(request()->input('data'))) {
            request()->merge(['data' => ['0' => []]]);
        }

        foreach (request()->input('data') as $index => $value) {
            // required validator
            $class->requiredFields["data[$index][rider]"] = 'Rider';
            $class->requiredFields["data[$index][horse]"] = 'Horse';

            // should exist validator
            $class->modelShouldExist["data[$index][rider]"] = [
                'Rider',
                \App\Services\Rider::class
            ];
            
            $class->modelShouldExist["data[$index][horse]"] = [
                'Horse',
                \App\Services\Horse::class
            ];
        }
    }
}
