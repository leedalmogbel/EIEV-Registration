<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Monarobase\CountryList\CountryListFacade as CountryList;
use App\Services\ServiceProvider;
use App\Models\Horse as HorseModel;
use Str;

class HorseController extends Controller
{
    //
    protected $model = 'horse';

    protected function prepTPLVars() {
        $tpl_vars = [];
        $tpl_vars['countries'] = CountryList::getList('en');
        $tpl_vars['breeds'] = HorseModel::BREEDS;
        $tpl_vars['genders'] = HorseModel::GENDERS;
        
        $owners = ServiceProvider::owner()->getAll(true)->toArray();
        $trainers = ServiceProvider::trainer()->getAll(true)->toArray();
        
        $tpl_vars['owners'] = ServiceProvider::arrayToKeyVal($owners, 'owner_id', 'firstname|[ ]|lastname');
        $tpl_vars['trainers'] = ServiceProvider::arrayToKeyVal($trainers, 'trainer_id', 'firstname|[ ]|lastname');
        
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

        $api_url = 'https://ebe.eiev-app.ae/api/uaeerf/horselist?params[StableID]='.$profile->stableid;
        if (isset($profile->stableid) && $profile->stableid == "E0000014") {
            $api_url = 'https://ebe.eiev-app.ae/api/uaeerf/horselist?params[AdminUserID]='.$profile->userid;
        }
        $options = [
            'headers' => [
                "38948f839e704e8dbd4ea2650378a388" => "0b5e7030aa4a4ee3b1ccdd4341ca3867"
            ],
            // 'form_params' => [
                //     "params" => [
                    //         "AdminUserID" => "516",
                    //         "StableID" => "E000006"
                    //     ]
                    // ]
        ];
        $response = $httpClient->request('POST', $api_url, $options);
        $hasHorse = json_decode($response->getBody());
        
        $tpl_vars['eef_horses'] = $hasHorse->horses->data;
        // dd($tpl_vars)
        return view(sprintf(self::LIST_TPL, $this->model), $tpl_vars);
    }
}
