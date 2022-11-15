<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Entry as EntryModel;
use App\Models\Horse as HorseModel;
use App\Models\Rider as RiderModel;
use App\Models\User as UserModel;

use App\Services\ServiceProvider;
use Str;
use App\Models\Multi;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Artisan;

class EntryController extends Controller
{
    //
    protected $model = 'entry';

    public function horseRider($userId) {
        // $horses = HorseModel::select('name', 'originalName', 'horse_id')
        //         ->join('owners', 'owners.owner_id', '=', 'horses.owner_id')
        //         ->where('owners.user_id', $userId)
        //         ->where('horses.status', 'A')
        //         ->get()
        //         ->toArray();

        // $riders = RiderModel::select('firstname', 'lastname', 'rider_id')
        //        ->where('user_id', $userId)
        //        ->where('status', 'A')
        //        ->get()
        //        ->toArray();

        $httpClient = new \GuzzleHttp\Client();
        $horse_url = '';
        $rider_url = '';
        $stableId = '';
        $userId = '';
        $profile = session()->get('profile');
        

        $horse_url = 'https://ebe.eiev-app.ae/api/uaeerf/horselist?params[StableID]='.$profile->stableid;
        if (isset($profile->stableid) && $profile->stableid == "E0000014") {
            $horse_url = 'https://ebe.eiev-app.ae/api/uaeerf/horselist?params[AdminUserID]='.$profile->userid;
        }

        $rider_url = 'https://ebe.eiev-app.ae/api/uaeerf/riderlist?params[StableID]='.$profile->stableid;
        if (isset($profile->stableid) && $profile->stableid == "E0000014") {
            $rider_url = 'https://ebe.eiev-app.ae/api/uaeerf/riderlist?params[AdminUserID]='.$profile->userid;
        }

        $userId = $profile->userid;
        $stableId = $profile->stableid;

        $options = [
            'headers' => [
                "38948f839e704e8dbd4ea2650378a388" => "0b5e7030aa4a4ee3b1ccdd4341ca3867"
            ],
        ];

        $horseResponse = $httpClient->request('POST', $horse_url, $options);
        $riderResponse = $httpClient->request('POST', $rider_url, $options);
        $horsesJson = json_decode($horseResponse->getBody());
        $ridersJson = json_decode($riderResponse->getBody());

        $horses = $horsesJson->horses->data;
        $riders = $ridersJson->riders->data;

        return [
            'riders' => $riders,
            'horses' => $horses,
            'user' => ['userid' => $userId, 'stableid' => $stableId]
        ];
    }

    public function create(Request $request) {
        $profile = session()->get('profile');
        $reqData = $request->data;
        $httpClient = new \GuzzleHttp\Client();
        $raceid = $request->get('raceid');
        $options = [
            'headers' => [
                "38948f839e704e8dbd4ea2650378a388" => "0b5e7030aa4a4ee3b1ccdd4341ca3867"
            ],
        ];

        $entryCode = array();
        foreach ($reqData as $key => $value) {
            if (!isset($reqData[$key]['horse']) || !isset($reqData[$key]['rider'])) {
                $this->flashMsg(sprintf('%s must not be empty', ucwords($this->model)), 'warning');
                return redirect(URL::current());
            }
            $horseid = $reqData[$key]['horse'];
            $riderid = $reqData[$key]['rider'];
            $entry_url = "https://ebe.eiev-app.ae/api/uaeerf/addentry?params[EventID]={$raceid}&params[HorseID]={$horseid}&params[RiderID]={$riderid}&params[UserID]=".$profile->userid;
            $entryResponse = $httpClient->request('POST', $entry_url, $options);
            $entryCode[$key]['horse'] = $horseid;
            $entryCode[$key]['rider'] = $riderid;
            $entryCode[$key]['entry'] = json_decode($entryResponse->getBody());

            if($entryCode[$key]['entry']->entrycode === '0') {
                $this->flashMsg(sprintf('%s', $entryCode[$key]['entry']->msgs[0]), 'warning');
                return redirect(URL::current());
            }

            $fEntries[$key] = array(
                "riderid" => $riderid,
                "horseid" => $horseid,
                "userid" => $profile->userid,
                "code" => $entryCode[$key]['entry']->entrycode,
                "eventcode" => $raceid
            );
        }

        Multi::insertOrUpdate($fEntries, 'fentries');
        // foreach($fEntries as $key => $val) {
        //     Artisan::call('command:syncentries --ip=eievadmin --host=admineiev --entryid='.$fEntries['code']);
        // }
        // foreach ($entryCode as $key => $value) {
        //     $this->flashMsg(sprintf('%s Horse '.$entryCode[$key]['horse'].' and '.$entryCode[$key]['rider'].' created successfully', ucwords($this->model)), 'success');
        //     if($entryCode[$key]['entry']->entrycode === '0') {
        //         $this->flashMsg(sprintf('%s Horse '.$entryCode[$key]['horse'].' and '.$entryCode[$key]['rider'].' failed', ucwords($this->model)), 'warning');
        //     }
        // }

// echo '<pre>'; print_r($entryResponse); exit;
        // ServiceProvider::{$this->model}($request->except('_token'))->createNew();
        
        // $this->flashMsg(sprintf('%s created successfully', ucwords($this->model)), 'success');
        return redirect(sprintf('/%s?raceid='.$raceid, $this->model));
        // return redirect(sprintf('/%s', 'dashboard'));
        
    }
    
    public function prepTPLVars() {
        $tplVars = [];
        
        $races = ServiceProvider::race()->getAll(true)->toArray();
        $tplVars['races'] = ServiceProvider::arrayToKeyVal($races, 'race_id', 'title');

        $users = UserModel::where('status', 'A');

        if (session()->get('role')->role == 'user') {
            $users = $users->where('user_id', session()->get('user')->user_id);
        }
        // if (session()->get('role')['role'] == 'user') {
        //     $users = $users->where('user_id', session()->get('user')->user_id);
        // }

        $users = $users->get()
               ->toArray();
        
        $tplVars['users'] = ServiceProvider::arrayToKeyVal($users, 'user_id', 'firstname|[ ]|lastname');
        $tplVars['horses'] = [];
        $tplVars['riders'] = [];
        $tplVars['jsonHorse'] = '{}';
        $tplVars['jsonRider'] = '{}';
        $tplVars['jsonUser'] = '{}';

        if (old('user_id')) {
            $horseRider = $this->horseRider(old('user_id'));
            $tplVars['jsonHorse'] = json_encode($horseRider['horses']);
            $tplVars['jsonRider'] = json_encode($horseRider['riders']);
            $tplVars['jsonUser'] = json_encode($horseRider['user']);

            
            $tplVars['horses'] = ServiceProvider::arrayToKeyVal($horseRider['horses'], 'horse_id', 'name | nfregistration | gender | color');
            $tplVars['riders'] = ServiceProvider::arrayToKeyVal($horseRider['riders'], 'rider_id', 'firstname|[ ]|lastname');
        }

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

        if (empty($request->get('raceid'))){
            return redirect(sprintf('/%s', 'dashboard'));
        }
        $api_url = 'https://ebe.eiev-app.ae/api/uaeerf/entries?params[SearchEventID]='.$request->get('raceid').'&params[SearchUserID]='.$profile->userid;
        // $api_url = 'http://192.168.1.161:8000/api/uaeerf/entries?params[SearchEventID]='.$request->get('raceid');

        $options = [
            'headers' => [
                "38948f839e704e8dbd4ea2650378a388" => "0b5e7030aa4a4ee3b1ccdd4341ca3867"
            ],
        ];
        $response = $httpClient->request('POST', $api_url, $options);
        $hasEntries = json_decode($response->getBody());
        $entries = $hasEntries->entries->data;

        $tpl_vars['eef_entries'] = $entries;

        return view(sprintf(self::LIST_TPL, $this->model), $tpl_vars);
    }

    public function withdrawn(Request $request) {
        $raceid = $request->get('raceid');
        $entrycode = $request->get('entrycode');
        $status = $request->get('status');

        $httpClient = new \GuzzleHttp\Client();
        $api_url = '';
        $api_url = 'https://ebe.eiev-app.ae/api/uaeerf/updateentry?params[EventID]='.$raceid.'&params[SearchEntryID]='.$entrycode.'&params[Entrystatus]='.$status.'&params[Remarks]=Withdrawn';
        // $api_url = 'http://192.168.1.161:8000/api/uaeerf/updateentry?params[EventID]='.$raceid.'&params[SearchEntryID]='.$entrycode.'&params[Entrystatus]='.$status.'&params[Remarks]=Withdrawn';

        $options = [
            'headers' => [
                "38948f839e704e8dbd4ea2650378a388" => "0b5e7030aa4a4ee3b1ccdd4341ca3867"
            ],
        ];
       
        $response = $httpClient->request('POST', $api_url, $options);
        $withdrawEntry = json_decode($response->getBody());
        // $entries = $hasEntries->entries->data;

        return redirect(sprintf('/%s', 'entry?raceid='.$raceid));
    }
}
