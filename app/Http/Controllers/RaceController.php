<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Race as RaceModel;
use App\Services\ServiceProvider;
use Str;

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

    public function listing(Request $request) {
        $tpl_vars = [];

        $service = ServiceProvider::{$this->model}();
        $tpl_vars[Str::plural($this->model)] = $service->listing($request->except('_token'));
        $tpl_vars['isSearchable'] = $service->isSearchable();
        
        $httpClient = new \GuzzleHttp\Client();
        $api_url = '';
        $profile = session()->get('profile');

        $api_url = 'https://ebe.eiev-app.ae/api/uaeerf/eventlist';
        $options = [
            'headers' => [
                "38948f839e704e8dbd4ea2650378a388" => "0b5e7030aa4a4ee3b1ccdd4341ca3867"
            ],
        ];
        $response = $httpClient->request('POST', $api_url, $options);
        $hasEvents = json_decode($response->getBody());
        $events = $hasEvents->events->data;

        // $events = array_filter($events, function($obj){
        //     if ($obj->statusname !== "Pending") {
        //         return $obj;
        //     } 
        // });

        $tpl_vars['eef_events'] = $events;

        return view(sprintf(self::LIST_TPL, $this->model), $tpl_vars);
    }
}
