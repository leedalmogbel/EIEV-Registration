<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Owner as OwnerModel;
use App\Services\ServiceProvider;

class OwnerController extends Controller
{

    protected $model = 'owner';

    protected function prepTPLVars() {
        $tplVars = [];
        $tplVars['discipline'] = OwnerModel::DISCIPLINE;
        $tplVars['visa_types'] = OwnerModel::VISA_TYPES;

        return $tplVars;
    }
}
