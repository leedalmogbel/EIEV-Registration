<?php

namespace App\Http\Controllers;

use App\Models\Fentry;
use App\Models\Fevent;
use App\Models\Snpool;
use App\Models\Multi;
use App\Models\Fstable;
use Illuminate\Http\Request;
use App\Http\Controllers\FederationController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use App\Models\Userprofile;

class FentryControler extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $fieldlist = ["SearchEntryID","SearchEventID",
      "SearchHorseID","SearchRiderID",
      "SearchUserID","SearchStableID"];
      $ppage = 15;
      if(isset($request->ppage)){
          $ppage = $request->ppage;
      }
      $entries = Fentry::query();
      if(isset($request->SearchEntryID)){
          $entries = $entries->where('code','like',$request->SearchEntryID);
      }
      if(isset($request->SearchEventID)){
          $entries = $entries->where('eventcode','like',"%".$request->SearchEventID);
      }
      if(isset($request->SearchHorseID)){
          $entries = $entries->where('horseid','like',"%".$request->SearchHorseID);
      }
      if(isset($request->SearchRiderID)){
          $entries = $entries->where('riderid','like',"%".$request->SearchRiderID);
      }
      if(isset($request->SearchUserID)){
          $entries = $entries->where('userid','like',"%".$request->SearchUserID);
      }
      if(isset($request->SearchStableID)){
          $entries = $entries->where('stableid','like',"%".$request->SearchStableID);
      }
      $entries = $entries->get();
      return response()->json(['entries'=>$entries]);

    }



    public function generateStartnumber(Request $request)  
    {
        if(isset($request->eventId) && isset($request->action)){
            $totalentries = Fentry::where('eventcode',$request->eventId)->where('status','Accepted')->count();
            if(isset($request->recalc)){
                $pool = Snpool::where('active',1)->get();
                if($pool){
                    foreach ($pool as $sn) {
                        $startassigned = json_decode($sn->assigned ?? '{}',true);
                        if(isset($startassigned[$request->eventId])){
                            unset($startassigned[$request->eventId]);
                            $sn->assigned = $startassigned;
                            $sn->save();
                        }
                    }
                }
                Fentry::where('eventcode',$request->eventId)->where('status','Accepted')->update(['startno'=>NULL]);
            }
            switch ($request->action) {
                case 'royal':
                    $royalstables = Fstable::where('category','Royal')->pluck('stableid')->toArray();
                    $entries = Fentry::whereIn('stableid',$royalstables)->where('eventcode',$request->eventId)->where('status','Accepted')->whereNull('startno')->whereNull('startno')->orderByRaw('CAST(code as UNSIGNED)')->get();
                    $rsnupdates = array();
                    foreach ($entries as $entry) {
                        $snum = array();
                        $startno = Snpool::where('stableid',$entry->stableid)->where('userid',$entry->userid)->where('active',1)->whereRaw('IFNULL(JSON_EXTRACT(assigned,"$.'.$request->eventId.'"),-1) <0')->orderBy('startno')->first();
                        if($startno){
                            if($startno->startno <= $totalentries){
                                $snum['code']=$entry->code;
                                $snum['startno']=$startno->startno;
                                array_push($rsnupdates,$snum);
                                $startassigned = json_decode($startno->assigned ?? '{}',true);
                                $startassigned[$request->eventId]=1;
                                $startno->assigned = $startassigned;
                                $startno->save();
                            }
                        }
                    }
                    if(count($rsnupdates)>0){
                        Multi::insertOrUpdate($rsnupdates,'fentries');
                        return response()->json(['msg'=>sprintf('Updated %s entries',count($rsnupdates)), 'entries'=>$rsnupdates]);
                    }
                    return response()->json(['msg'=>'No entries updated.']);
                    break;
                case 'others':
                    
                    $exclude = Snpool::whereRaw('IFNULL(JSON_EXTRACT(assigned,"$.'.$request->eventId.'"),-1) >0')->orWhere('active',0)->pluck('startno')->toArray();
                    $collection = collect(range(1,$totalentries+count($exclude)))->map(function ($n)use ($exclude){ if(!in_array($n,$exclude)) return $n;})->reject(function($n){return empty($n);})->sort()->values()->all();
                    $entries = Fentry::where('eventcode',$request->eventId)->where('status','Accepted')->whereNull('startno')->orderByRaw('CAST(code as UNSIGNED)')->get();
                    $osnupdates = array();
                    if($entries){
                        for($i=0;$i<count($entries);$i++){
                            $snum['code']=$entries[$i]->code;
                            $snum['startno']=$collection[$i];
                            array_push($osnupdates,$snum);
                        }
                        if(count($osnupdates)>0){
                            Multi::insertOrUpdate($osnupdates,'fentries');
                            return response()->json(['msg'=>sprintf('Updated %s entries',count($osnupdates)), 'entries'=>$osnupdates]);
                        }

                    }
                    break;
                case 'both':
                    $royalstables = Fstable::where('category','Royal')->pluck('stableid')->toArray();
                    $entries = Fentry::whereIn('stableid',$royalstables)->where('eventcode',$request->eventId)->where('status','Accepted')->whereNull('startno')->orderByRaw('CAST(code as UNSIGNED)')->get();
                    $rsnupdates = array();
                    foreach ($entries as $entry) {
                        $snum = array();
                        $startno = Snpool::where('stableid',$entry->stableid)->where('userid',$entry->userid)->where('active',1)->whereRaw('IFNULL(JSON_EXTRACT(assigned,"$.'.$request->eventId.'"),-1) <0')->orderBy('startno')->first();
                        if($startno){
                            if($startno->startno <= $totalentries){
                                $snum['code']=$entry->code;
                                $snum['startno']=$startno->startno;
                                array_push($rsnupdates,$snum);
                                $startassigned = json_decode($startno->assigned ?? '{}',true);
                                $startassigned[$request->eventId]=1;
                                $startno->assigned = $startassigned;
                                $startno->save();
                            }
                        }
                    }
                    if(count($rsnupdates)>0){
                        Multi::insertOrUpdate($rsnupdates,'fentries');
                    }
                    $exclude = Snpool::whereRaw('IFNULL(JSON_EXTRACT(assigned,"$.'.$request->eventId.'"),-1) >0')->orWhere('active',0)->pluck('startno')->toArray();
                    $collection = collect(range(1,$totalentries+count($exclude)))->map(function ($n)use ($exclude){ if(!in_array($n,$exclude)) return $n;})->reject(function($n){return empty($n);})->sort()->values()->all();
                    $entries = Fentry::where('eventcode',$request->eventId)->where('status','Accepted')->whereNull('startno')->orderByRaw('CAST(code as UNSIGNED)')->get();
                    $osnupdates = array();
                    if($entries){
                        for($i=0;$i<count($entries);$i++){
                            $snum['code']=$entries[$i]->code;
                            $snum['startno']=$collection[$i];
                            array_push($osnupdates,$snum);
                        }
                        if(count($osnupdates)>0){
                            Multi::insertOrUpdate($osnupdates,'fentries');
                        }
                        
                    }
                    return response()->json(['msg'=>sprintf('Updated %s entries',count($osnupdates) + count($rsnupdates)), 'entries'=>$osnupdates,'rentries'=>$rsnupdates]);
                    break;
                case 'rem':
                        $exclude = Snpool::whereRaw('IFNULL(JSON_EXTRACT(assigned,"$.'.$request->eventId.'"),-1) >0')->orWhere('active',0)->pluck('startno')->toArray();
                        $existingnos = Fentry::where('eventcode',$request->eventId)->where('status','Accepted')->whereNotNull('startno')->orderByRaw('CAST(code as UNSIGNED)')->pluck('startno')->toArray();
                        $collection = collect(range(1,$totalentries+count($exclude)))->map(function ($n)use ($exclude,$existingnos){ if(!in_array($n,$exclude) && !in_array($n,$existingnos)) return $n;})->reject(function($n){return empty($n);})->sort()->values()->all();
                        $entries = Fentry::where('eventcode',$request->eventId)->where('status','Accepted')->whereNull('startno')->orderByRaw('CAST(code as UNSIGNED)')->get();
                        $osnupdates = array();
                        if($entries){
                            for($i=0;$i<count($entries);$i++){
                                $snum['code']=$entries[$i]->code;
                                $snum['startno']=$collection[$i];
                            array_push($osnupdates,$snum);
                        }
                        if(count($osnupdates)>0){
                            Multi::insertOrUpdate($osnupdates,'fentries');
                        }
                        
                    }
                    return response()->json(['msg'=>sprintf('Updated %s entries',count($osnupdates)), 'entries'=>$osnupdates]);
                    break;
            }
        }
        return response()->json(['msg'=>'No action needed.'],400);
    }

    public function getlists(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'SearchEventID'=>'required',
          ]);
        $totalcount = 0;
        $tables = Fentry::where('eventcode','like',"%".strval(intval($request->SearchEventID)))->distinct('stablename')->pluck('stablename','userid')->toArray();
        $events = Fevent::selectRaw('CONCAT( CAST(raceid as UNSIGNED), " : ", racename, "    |   Event Date - ", DATE_FORMAT( CAST(racefromdate as DATETIME),"%Y-%m-%d"), "    |   Opening - ", DATE_FORMAT( CAST(openingdate as DATETIME),"%Y-%m-%d %H:%i:%s"), "    |   Closing - ", DATE_FORMAT( CAST(closingdate as DATETIME),"%Y-%m-%d %H:%i:%s") ) as race, CAST(raceid as UNSIGNED) as raceid')->where('statusname','like','%Entries%')->orWhere('statusname','like','%Closed%')->pluck('race','raceid')->toArray();
        $eventnames = Fevent::selectRaw('CAST(raceid as UNSIGNED) as raceid,racename')->pluck('racename','raceid')->toArray();
        if($validator->fails()){
            return view('tempadmin.tlists',['modelName'=>'entry','stables'=>$tables,'events'=>$events,'eventnames'=>$eventnames,'entries'=>[]]);
        }
        
        $ppage= 15;
        if(isset($request->ppage)){
            $ppage = $request->ppage;
        }
        $iitems = [
            [
                "flds"=>["startno","pStartCode","pRiderName","pHorseName"],
                "cnames"=>["col-1","col-1","col-5","col-5"],
                "lbls"=>["START NO","SC","RNAME","HNAME"]
            ],
            [
                "flds"=>["pOwnerName","pTrainerName","pStableName","entryCode"],
                "cnames"=>["col-3","col-3","col-3","col-3"],
                "lbls"=>["OWNER","TRAINER","STABLE","ENTRY CODE"]
            ],
        ];
        $actions = [
            'assign-no'=>["cname"=>"btn btn-success","lbl"=>"Assign"],
            'unassign-no'=>["cname"=>"btn btn-danger","lbl"=>"Unassign"]
        ];
        //final list
        $fentries = Fentry::query();
        //limited entries
        $eentries = Fentry::query();
        //regular / private entries
        $pentries = Fentry::query();
        //pending for approval
        $reventries = Fentry::query();
        //rejected entries
        $rentries = Fentry::query();
        //royal for president cup
        $pcentries = Fentry::query();
        $fentries = $fentries->where('eventcode','like',"%".strval(intval($request->SearchEventID)))->where('status', 'Accepted');
        $eentries = $eentries->where('eventcode','like',"%".strval(intval($request->SearchEventID)))->where('classcode',"1")->whereIn('status',['Eligible']);
        $pentries = $pentries->where('eventcode','like',"%".strval(intval($request->SearchEventID)))->where('classcode',"3")->where('status', 'Pending')->where('review','<>','0');
        $reventries = $reventries->where('eventcode','like','%'.strval(intval($request->SearchEventID)))->where('status','Pending')->where('review','0');
        $rentries = $rentries->where('eventcode','like','%'.strval(intval($request->SearchEventID)))->whereIn('status',['Rejected','Withdrawn']);
        $pcentries = $pcentries->where('eventcode','like',"%".strval(intval($request->SearchEventID)))->where('classcode',"4")->where('status', 'Pending')->where('review','<>','0');
        if(isset($request->stablename)){
            $fentries = $fentries->whereIn('userid',explode(',',$request->stablename))->orderByRaw('CAST(startno as UNSIGNED) asc');
            $eentries = $eentries->whereIn('userid',explode(',',$request->stablename))->orderByRaw('CAST(startno as UNSIGNED) asc');
            $pentries = $pentries->whereIn('userid',explode(',',$request->stablename))->orderByRaw('CAST(startno as UNSIGNED) asc');
            $reventries = $reventries->whereIn('userid',explode(',',$request->stablename))->orderByRaw('CAST(startno as UNSIGNED) asc');
            $rentries = $rentries->whereIn('userid',explode(',',$request->stablename))->orderByRaw('DATE_FORMAT(withdrawdate,"%Y-%m-%d %H:%i%s") DESC');
            $pcentries = $pcentries->whereIn('userid',explode(',',$request->stablename))->orderByRaw('CAST(startno as UNSIGNED) asc');
        }
        $fentries = $fentries->orderByRaw('CAST(startno as UNSIGNED) asc');
        $eentries = $eentries->orderByRaw('CAST(startno as UNSIGNED) asc');
        $pentries = $pentries->orderByRaw('CAST(startno as UNSIGNED) asc');
        $reventries = $reventries->orderByRaw('CAST(startno as UNSIGNED) asc');
        $rentries = $rentries->orderByRaw('DATE_FORMAT(withdrawdate,"%Y-%m-%d %H:%i%s") DESC');
        $pcentries = $pcentries->orderByRaw('CAST(startno as UNSIGNED) asc');
        $fentries =isset($request->ppage)? $fentries->paginate($ppage): $fentries->get();
        $eentries =isset($request->ppage)? $eentries->paginate($ppage): $eentries->get();
        $pentries =isset($request->ppage)? $pentries->paginate($ppage): $pentries->get();
        $reventries = isset($request->ppage)? $reventries->paginate($ppage) : $reventries->get();
        $rentries = isset($request->ppage)? $rentries->paginate($ppage) : $rentries->get();
        $pcentries =isset($request->ppage)? $pcentries->paginate($ppage): $pcentries->get();
        $totalcount = count($fentries) + count($eentries) + count($pentries)
        + count($reventries) + count($rentries);
        
        if(isset($request->presidentcup)){
            $totalcount += count($pcentries);
            return view('tempadmin.tlists',['modelName'=>'entry','actions'=>$actions,'items'=>$iitems,'total'=>$totalcount,'events'=>$events,'eventnames'=>$eventnames,'stables'=>$tables,'entries'=>['final'=>$fentries,'pfa'=>$eentries,'pfr'=>$reventries,'prov'=>$pentries,'royprov'=>$pcentries,'re'=>$rentries]]);
        }
        return view('tempadmin.tlists',['modelName'=>'entry','actions'=>$actions,'items'=>$iitems,'total'=>$totalcount,'events'=>$events,'eventnames'=>$eventnames,'stables'=>$tables,'entries'=>['final'=>$fentries,'pfa'=>$eentries,'pfr'=>$reventries,'prov'=>$pentries,'re'=>$rentries]]);
    }

    public function accept(Request $request)
    {
        $entry = Fentry::where('code',$request->entrycode)->first();
        if($entry){
            $myRequest = new \Illuminate\Http\Request();
            $myRequest->setMethod('POST');
            $myRequest->request->add(['params' => [
                'EventID'=>$entry->eventcode,
                'SearchEntryID'=>$entry->code,
                'Entrystatus'=>'accepted',
                'Remarks'=>'Accepted Entry for Final List by Admin',]]);
            $data = (new FederationController)->updateentry($myRequest);
            Artisan::call('command:syncentries --ip=eievadmin --host=admineiev --entryid='.$entry->code);
        }
        if(isset($request->stablename)){
            return redirect('/rideslist?SearchEventID='.$entry->eventcode.'&stablename='.$request->stablename);
        }
        return redirect('/rideslist?SearchEventID='.$entry->eventcode);
    }
    public function mainlist(Request $request)
    {
        $entry = Fentry::where('code',$request->entrycode)->first();
        if($entry){
            $myRequest = new \Illuminate\Http\Request();
            $myRequest->setMethod('POST');
            $myRequest->request->add(['params' => [
                'EventID'=>$entry->eventcode,
                'SearchEntryID'=>$entry->code,]]);
            $data = (new FederationController)->moveentrytomain($myRequest);
            Artisan::call('command:syncentries --ip=eievadmin --host=admineiev --entryid='.$entry->code);
        }
        if(isset($request->stablename)){
            return redirect('/rideslist?SearchEventID='.$entry->eventcode.'&stablename='.$request->stablename);
        }
        return redirect('/rideslist?SearchEventID='.$entry->eventcode);
    }

    public function moveall(Request $request)
    {
        if(isset($request->list) && isset($request->eventid)){
            switch ($request->list) {
                case 'main':
                    $entries = Fentry::where('status',"Pending")->where('review','<>','0')->where('eventcode','like','%'.strval(intval($request->eventid)))->get();
                    $plist = array();
                    if($entries){
                        foreach ($entries as $entry) {
                            $myRequest = new \Illuminate\Http\Request();
                            $myRequest->setMethod('POST');
                            $myRequest->request->add(['params' => [
                            'EventID'=>$entry->eventcode,
                            'SearchEntryID'=>$entry->code,]]);
                            $data = (new FederationController)->moveentrytomain($myRequest);
                            array_push($plist,$data);
                        }
                        Artisan::call('command:syncentries --ip=eievadmin --host=admineiev');
                        return response()->json(['msg'=>sprintf('Process %s entries',count($plist)),'data'=>$plist]);
                    }
                        break;
                case 'final':
                    $entries = Fentry::where('status',"Eligible")->where('eventcode','like','%'.strval(intval($request->eventid)))->get();
                    if($entries){
                        $plist = array();
                        foreach ($entries as $entry) {
                            $myRequest = new \Illuminate\Http\Request();
                            $myRequest->setMethod('POST');
                            $myRequest->request->add(['params' => [
                                'EventID'=>$entry->eventcode,
                                'SearchEntryID'=>$entry->code,
                                'Entrystatus'=>'accepted',
                                'Remarks'=>'Accepted Entry for Final List by Admin',]]);
                            $data = (new FederationController)->updateentry($myRequest);
                            array_push($plist,$data);
                        }
                        Artisan::call('command:syncentries --ip=eievadmin --host=admineiev');
                        return response()->json(['msg'=>sprintf('Process %s entries',count($plist)),'data'=>$plist]);
                    }
                    break;
            }
        }
        return response()->json(['msg'=>'Nothing to do'],400);
    }

    public function assignStartNo(Request $request)
    {
        
        if(isset($request->startno) && isset($request->entryCode) && isset($request->eventid)){
            if($request->startno == "-2"){
                $entry = Fentry::where('status','Accepted')->where('eventcode','like','%'.$request->eventid)->where('code',$request->entryCode)->update(['startno'=>NULL]);
                return response()->json(['success'=>true]);
            }else{
                $entry = Fentry::where('status','Accepted')->where('eventcode','like','%'.$request->eventid)->where('code',$request->entryCode)->first();
                if($entry){
                    $entry->startno = $request->startno;
                    $success = $entry->save();
                    if($success){
                        return response()->json(['success'=>true]);
                    }
                }
            }
        }
        return response()->json(['success'=>false]);
    }

    public function getEntry(Request $request)
    {
        if(isset($request->entryCode)){
            $entry= Fentry::where('code',$request->entryCode)->first();
            if($entry){
                return response()->json(['entry'=>$entry]);
            }
        }
        return response()->json(['entry'=>null]);
    }

    public function getAvailSnos(Request $request)
    {
        if(isset($request->eventid)){
            $entries = Fentry::where('status','Accepted')->where('eventcode','like','%'.$request->eventid)->count();
            $pentries = Fentry::where('status','Pending')->where('review','<>',0)->where('eventcode','like','%'.$request->eventid)->count();
            $exclude = Snpool::where('active',0)->pluck('startno')->toArray();
            $existingnos = Fentry::where('status','Accepted')->where('eventcode','like','%'.$request->eventid)->whereNotNull('startno')->pluck('startno')->toArray();
            $collection = collect(range(1,$entries+$pentries+count($exclude)))->map(function ($n)use ($exclude,$existingnos){ if(!in_array($n,$exclude) && !in_array($n,$existingnos)) return $n;})->reject(function($n){return empty($n);})->sort()->values()->all();
            return response()->json(['startnos'=>$collection]);
        }
    }



    public function reject(Request $request)
    {
        $entry = Fentry::where('code',$request->entrycode)->first();
        if($entry){
            $myRequest = new \Illuminate\Http\Request();
            $myRequest->setMethod('POST');
            $myRequest->request->add(['params' => [
                'EventID'=>$entry->eventcode,
                'SearchEntryID'=>$entry->code,
                'Entrystatus'=>'rejected',
                'Remarks'=>'Rejected Entry by Admin',]]);
            // if($entry->startno){
            //     Fentry::where('code',$request->entrycode)->update(['startno'=>NULL]);
            // }
            $data = (new FederationController)->updateentry($myRequest);
            Artisan::call('command:syncentries --ip=eievadmin --host=admineiev --entryid='.$entry->code);
        }
        if(isset($request->stablename)){
            return redirect('/rideslist?SearchEventID='.$entry->eventcode.'&stablename='.$request->stablename);
        }
        return redirect('/rideslist?SearchEventID='.$entry->eventcode);
    }

    public function withdraw(Request $request)
    {
        $entry = Fentry::where('code',$request->entrycode)->first();
        if($entry){
            $myRequest = new \Illuminate\Http\Request();
            $myRequest->setMethod('POST');
            $myRequest->request->add(['params' => [
                'EventID'=>$entry->eventcode,
                'SearchEntryID'=>$entry->code,
                'Entrystatus'=>'withdrawn',
                'Remarks'=>'Withdrawn by Admin',]]);
            // if($entry->startno){
            //     Fentry::where('code',$request->entrycode)->update(['startno'=>NULL]);
            // }
            $data = (new FederationController)->updateentry($myRequest);
            Artisan::call('command:syncentries --ip=eievadmin --host=admineiev --entryid='.$entry->code);
        }
        if(isset($request->stablename)){
            return redirect('/rideslist?SearchEventID='.$entry->eventcode.'&stablename='.$request->stablename);
        }
        return redirect('/rideslist?SearchEventID='.$entry->eventcode);
    }

    public function addentry(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'params.EventID'=>'required',
            'params.HorseID'=>'required',
            'params.RiderID'=>'required',
            'params.UserID'=>'required',
          ]);
          if($validator->fails()){
            return response()->json(["error" => $validator->errors()]);
          }
        $myRequest = new \Illuminate\Http\Request();
        $myRequest->setMethod('POST');
        $myRequest->request->add(['params' => [
            'EventID'=>$request->params['EventID'],
            'HorseID'=>$request->params['HorseID'],
            'RiderID'=>$request->params['RiderID'],
            'UserID'=>$request->params['UserID'],]]);
        $data = (new FederationController)->addentry($myRequest);
        if($data['entrycode'] != "0"){
            Multi::insertOrUpdate([["riderid"=>$request->params['RiderID'],"horseid"=>$request->params['HorseID'],"userid"=>$request->params['UserID'],"code"=>$data['entrycode'],"eventcode"=>$request->params['EventID']]],'fentries');
            Artisan::call('command:syncentries --ip=eievadmin --host=admineiev --entryid='.$data['entrycode']);
            $this->flashMsg(sprintf('Entry added successfully. Entry Code: %s',$data['entrycode']), 'success');
        }else{
            if(isset($data['msgs'])){
                $this->flashMsg(sprintf('%s', implode('\n',$data['msgs'])), 'warning');
            }else{
                $this->flashMsg(sprintf('%s', 'Entry not added.'), 'warning');
            }
        }
        return redirect('/submitentry');
    }
    

    public function entryadd(Request $request)
    {
        $ppage= 15;
        if(isset($request->ppage)){
            $ppage = $request->ppage;
        }
        $profiles = Userprofile::query();
        $profiles = $profiles->where('stableid','like','E%');
        if($request->SearchEmail){
            $profiles = $profiles->where('eventcode','like',"%".$request->SearchEventID."%")->where('status', 'Accepted');
        }
        $profiles =isset($request->ppage)? $profiles->paginate($ppage): $profiles->get();
        return view('tempadmin.tentry',['modelName'=>'submitentry','profiles'=>$profiles]);
    }

    public function actions(Request $request)
    {
        if(isset($request->code)){
            $profile = Userprofile::where('uniqueid',$request->code)->first();
            if($profile){
                $entries = Fentry::where('userid',$profile->userid)->where('stableid',$profile->stableid)->get();
                return view('tempadmin.tactions',['actions'=>['Add Entry','Swap Entry','Update Entry'],'profile'=>$profile,'entries'=>$entries]);
            }
        }
        return view('tempadmin.tactions',['actions'=>[],'profile'=>[],'entries'=>[]]);
    }



    public function syncfromcloud(Request $request)
    {

        $api_url = 'https://devregistration.eiev-app.ae/api/getentries/';

        $options = [
            'headers' => [
                "38948f839e704e8dbd4ea2650378a388" => "0b5e7030aa4a4ee3b1ccdd4341ca3867"
            ],
        ];
        $httpClient = new \GuzzleHttp\Client();
        $client = new Client();
        $response = $client->request('GET',$api_url, $options);
        $data = json_decode($response->getBody(),true);
        if(count($data["entries"])>0){
            Multi::insertOrUpdate($data["entries"],'fentries');
            return response()->json(['msg'=>sprintf('Updated %s entries',count($data['entries']))]);
        }
        return response()->json(['msg'=>'No action done.']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fentry  $fentry
     * @return \Illuminate\Http\Response
     */
    public function show(Fentry $fentry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fentry  $fentry
     * @return \Illuminate\Http\Response
     */
    public function edit(Fentry $fentry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fentry  $fentry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fentry  $fentry
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fentry $fentry)
    {
        //
    }
}
