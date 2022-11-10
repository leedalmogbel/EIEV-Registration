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
        $horses = HorseModel::select('name', 'originalName', 'horse_id')
                ->join('owners', 'owners.owner_id', '=', 'horses.owner_id')
                ->where('owners.user_id', $userId)
                ->where('horses.status', 'A')
                ->get()
                ->toArray();

        $riders = RiderModel::select('firstname', 'lastname', 'rider_id')
               ->where('user_id', $userId)
               ->where('status', 'A')
               ->get()
               ->toArray();

        return [
            'riders' => $riders,
            'horses' => $horses
        ];
    }
    
    public function prepTPLVars() {
        $tplVars = [];
        
        $races = ServiceProvider::race()->getAll(true)->toArray();
        $tplVars['races'] = ServiceProvider::arrayToKeyVal($races, 'race_id', 'title');

        $users = UserModel::where('status', 'A');
        if (session()->get('role')->role == 'user') {
            $users = $users->where('user_id', session()->get('user')->user_id);
        }

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
            
            $tplVars['horses'] = ServiceProvider::arrayToKeyVal($horseRider['horses'], 'horse_id', 'name');
            $tplVars['riders'] = ServiceProvider::arrayToKeyVal($horseRider['riders'], 'rider_id', 'firstname|[ ]|lastname');
        }

        return $tplVars;
    }
}
