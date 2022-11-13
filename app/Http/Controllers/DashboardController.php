<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    public function index() {
        $modelName = 'dashboard';
        $httpClient = new \GuzzleHttp\Client();
        $api_url = '';
        $profile = session()->get('profile');

        $api_url = 'https://ebe.eiev-app.ae/api/uaeerf/stablestats?params[StableID]='.$profile->stableid;
        if (isset($profile->stableid) && $profile->stableid == "E0000014") {
            $api_url = 'https://ebe.eiev-app.ae/api/uaeerf/stablestats?params[AdminUserID]='.$profile->userid;
        }
        $apiEvents_url = 'https://ebe.eiev-app.ae/api/uaeerf/eventlist';
        $options = [
            'headers' => [
                "38948f839e704e8dbd4ea2650378a388" => "0b5e7030aa4a4ee3b1ccdd4341ca3867"
            ],
        ];


        $response = $httpClient->request('POST', $api_url, $options);
        $hasCount = json_decode($response->getBody());
        $dashcount = $hasCount->data;

        $eventRes = $httpClient->request('POST', $apiEvents_url, $options);
        $hasEvents = json_decode($eventRes->getBody());
        $events = $hasEvents->events->data;

        return view('pages.dashboard', [
            'modelName' => $modelName,
            'dashcount' => $dashcount,
            'events' => $events
        ]);
    }
}
