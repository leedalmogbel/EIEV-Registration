<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Stable as StableModel;
use App\Models\User as UserModel;

use App\Services\ServiceProvider;

class StableController extends Controller
{
    protected $model = 'stable';

    protected function prepTPLVars () {
        $tplVars = [];

        $tplVars['types'] = StableModel::STABLE_TYPES;
        $users = ServiceProvider::user()->getAll();
        $tplVars['users'] = [];
        foreach ($users as $user) {
            $tplVars['users'][$user->user_id] = "$user->firstname $user->lastname";
        }

        return $tplVars;
    }

}
