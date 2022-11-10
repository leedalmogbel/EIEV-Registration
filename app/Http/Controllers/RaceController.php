<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Race as RaceModel;
use App\Services\ServiceProvider;

class RaceController extends Controller
{
    //
    protected $model = 'race';

    public function prepTPLVars () {
        $tpl_vars = [];
        $events = ServiceProvider::event()->getAll()->toArray();
        $events = ServiceProvider::arrayToKeyVal($events, 'event_id', 'name');
        $events['new'] = 'Create new Event';
        
        $tpl_vars = EventController::prepTPLVars();
        $tpl_vars['events'] = $events;

        $stables = ServiceProvider::stable()->getAll()->toArray();
        $stables = ServiceProvider::arrayToKeyVal($stables, 'stable_id', 'name');
        $tpl_vars['stables'] = $stables;
        
        return $tpl_vars;
    }
}
