<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Entry as EntryModel;
use App\Models\Horse as HorseModel;
use App\Models\Rider as RiderModel;
use App\Models\User as UserModel;

use App\Services\ServiceProvider;

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
        $profile = session()->get('profile');
        

        $horse_url = 'https://ebe.eiev-app.ae/api/uaeerf/horselist?params[StableID]='.$profile->stableid;
        if (isset($profile->stableid) && $profile->stableid == "E0000014") {
            $horse_url = 'https://ebe.eiev-app.ae/api/uaeerf/horselist?params[AdminUserID]='.$profile->userid;
        }

        $rider_url = 'https://ebe.eiev-app.ae/api/uaeerf/riderlist?params[StableID]='.$profile->stableid;
        if (isset($profile->stableid) && $profile->stableid == "E0000014") {
            $rider_url = 'https://ebe.eiev-app.ae/api/uaeerf/riderlist?params[AdminUserID]='.$profile->userid;
        }

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
            'horses' => $horses
        ];
    }

    public function create(Request $request) {
        $profile = session()->get('profile');
        $data = $request->data;
        $httpClient = new \GuzzleHttp\Client();
        $raceid = $request->get('raceid');
        $horseid = $data[0]['horse'];
        $riderid = $data[0]['rider'];
        $entry_url = "https://ebe.eiev-app.ae/api/uaeerf/addentry?params[EventID]={$raceid}&params[HorseID]={$horseid}&params[RiderID]={$riderid}&params[UserID]=".$profile->userid;
        $options = [
            'headers' => [
                "38948f839e704e8dbd4ea2650378a388" => "0b5e7030aa4a4ee3b1ccdd4341ca3867"
            ],
        ];
// dd($entry_url);
        $entryResponse = $httpClient->request('POST', $entry_url, $options);
// echo '<pre>'; print_r($entryResponse); exit;
        // ServiceProvider::{$this->model}($request->except('_token'))->createNew();
        
        $this->flashMsg(sprintf('%s created successfully', ucwords($this->model)), 'success');
        return redirect(sprintf('/%s', $this->model));
    }
    
    public function prepTPLVars() {
        $tplVars = [];
        
        $races = ServiceProvider::race()->getAll(true)->toArray();
        $tplVars['races'] = ServiceProvider::arrayToKeyVal($races, 'race_id', 'title');

        $users = UserModel::where('status', 'A');
        // if (session()->get('role')->role == 'user') {
        //     $users = $users->where('user_id', session()->get('user')->user_id);
        // }

        $users = $users->get()
               ->toArray();
        
        $tplVars['users'] = ServiceProvider::arrayToKeyVal($users, 'user_id', 'firstname|[ ]|lastname');
        $tplVars['horses'] = [];
        $tplVars['riders'] = [];
        $tplVars['jsonHorse'] = '{}';
        $tplVars['jsonRider'] = '{}';
        
        if (old('user_id')) {
            $horseRider = $this->horseRider(old('user_id'));
            $tplVars['jsonHorse'] = json_encode($horseRider['horses']);
            $tplVars['jsonRider'] = json_encode($horseRider['riders']);
            
            $tplVars['horses'] = ServiceProvider::arrayToKeyVal($horseRider['horses'], 'horse_id', 'name | nfregistration | gender | color');
            $tplVars['riders'] = ServiceProvider::arrayToKeyVal($horseRider['riders'], 'rider_id', 'firstname|[ ]|lastname');
        }

        return $tplVars;
    }
}
