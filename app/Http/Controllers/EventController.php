<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event as EventModel;
use App\Services\ServiceProvider;

class EventController extends Controller
{
    protected $model = 'event';

    public function prepTPLVars() {
        $tplVars = [];
        $tplVars['locations'] = EventModel::EVENT_LOCATIONS;
        $tplVars['countries'] = EventModel::EVENT_COUNTRIES;
        $seasons = ServiceProvider::season()->getAll(true)->toArray();

        $tplVars['seasons'] = ServiceProvider::arrayToKeyVal($seasons, 'season_id', 'season');

        return $tplVars;
    }
}
