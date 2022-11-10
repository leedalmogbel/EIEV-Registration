<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Trainer as RiderModel;
use App\Services\ServiceProvider;
use Str;

class RiderController extends Controller
{
    protected $model = 'rider';

    protected function prepTPLVars() {
        $tplVars = [];
        $tplVars['discipline'] = RiderModel::DISCIPLINE;
        $tplVars['visa_types'] = RiderModel::VISA_TYPES;

        return $tplVars;
    }

    public function listing(Request $request) {
        $tpl_vars = [];

        $service = ServiceProvider::{$this->model}();
        $tpl_vars[Str::plural($this->model)] = $service->listing($request->except('_token'));
        $tpl_vars['isSearchable'] = $service->isSearchable();
        
        $httpClient = new \GuzzleHttp\Client();
        $api_url = '';
        $profile = session()->get('profile');

        $api_url = 'https://ebe.eiev-app.ae/api/uaeerf/riderlist?params[StableID]='.$profile->stableid;
        if (isset($profile->stableid) && $profile->stableid == "E0000014") {
            $api_url = 'https://ebe.eiev-app.ae/api/uaeerf/riderlist?params[AdminUserID]='.$profile->userid;
        }
        $options = [
            'headers' => [
                "38948f839e704e8dbd4ea2650378a388" => "0b5e7030aa4a4ee3b1ccdd4341ca3867"
            ],
        ];
        $response = $httpClient->request('POST', $api_url, $options);
        $hasRider = json_decode($response->getBody());

        $tpl_vars['eef_riders'] = $hasRider->riders->data;

        return view(sprintf(self::LIST_TPL, $this->model), $tpl_vars);
    }
}
