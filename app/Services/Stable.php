<?php

namespace App\Services;

use App\Models\Stable as StableModel;
use App\Validators\Stable as StableValidator;

use App\Exceptions\MsgException;

class Stable extends StableModel {
    use Base;
    
    const PAGINATION_ENTRY = 15;
    const MODEL_VALIDATOR = 'App\Validators\Stable';

    protected $searchables = [
        'name',
    ];
    
    protected function createPre() {
        $this->metadata = [];
    }
}
