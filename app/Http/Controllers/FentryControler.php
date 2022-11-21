<?php

namespace App\Http\Controllers;

use App\Models\Fentry;
use App\Models\Fevent;
use App\Models\Userprofile;
use App\Models\Snpool;
use App\Models\Multi;
use App\Models\Fstable;
use Illuminate\Http\Request;
use App\Http\Controllers\FederationController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;

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
          $entries = $entries->where('code','like',"%".$request->SearchEntryID."%");
      }
      if(isset($request->SearchEventID)){
          $entries = $entries->where('eventcode','like',"%".$request->SearchEventID."%");
      }
      if(isset($request->SearchHorseID)){
          $entries = $entries->where('horseid','like',"%".$request->SearchHorseID."%");
      }
      if(isset($request->SearchRiderID)){
          $entries = $entries->where('riderid','like',"%".$request->SearchRiderID."%");
      }
      if(isset($request->SearchUserID)){
          $entries = $entries->where('userid','like',"%".$request->SearchUserID."%");
      }
      if(isset($request->SearchStableID)){
          $entries = $entries->where('stableid','like',"%".$request->SearchStableID."%");
      }
      $entries = $entries->paginate($ppage);
      return response()->json(['entries'=>$entries]);

    }

    public function generateStartnumber(Request $request)  
    {
        $totalentries = Fentry::where('eventcode',$request->eventId)->where('status','Pending')->where('review','<>','0')->count();
        if(isset($request->eventId) && isset($request->action)){
            switch ($request->action) {
                case 'royal':
                    $royalstables = Fstable::where('category','Royal')->pluck('stableid')->toArray();
                    $entries = Fentry::whereIn('stableid',$royalstables)->where('eventcode',$request->eventId)->where('status','Pending')->where('review','<>','0')->whereNull('startno')->orderByRaw('CAST(code as INT)')->get();
                    $rsnupdates = array();
                    foreach ($entries as $entry) {
                        $snum = array();
                        $startno = Snpool::where('stableid',$entry->stableid)->where('userid',$entry->userid)->whereRaw('IFNULL(JSON_EXTRACT(assigned,"$.'.$request->eventId.'"),-1) <0')->orderBy('startno')->get()->first();
                        if($startno <= $totalentries){
                            $snum['code']=$entry->code;
                            $snum['startno']=$startno->startno;
                            array_push($rsnupdates,$snum);
                            $startassigned = json_decode($startno->assigned ?? '{}',true);
                            $startassigned[$request->eventId]=1;
                            $startno->assigned = $startassigned;
                            $startno->save();
                        }
                    }
                    if(count($rsnupdates)>0){
                        Multi::insertOrUpdate($rsnupdates,'fentries');
                        return response()->json(['msg'=>sprintf('Updated %s entries',count($rsnupdates)), 'entries'=>$rsnupdates]);
                    }
                    return response()->json(['msg'=>'No entries updated.']);
                    break;
                case 'others':
                    $exclude = [1,2,3,7,10,4,41,5,6,9,8,11,12,13,14,15,16,17,18,19,20,86,21,22,23,87,88,89,42,39,43,48,40,49,50,51,52,56,53,54,55,57,58,59,60,61,62,63,64,65,66,44,45,46,47,67,68,69,70,80,81,82,83,84,85,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124,125,130,131,132,133,134,135,136,137,138,139,140,141,145,142,143,144,157,158,159,146,160,161,162,163,164,165,166,167,168,169,170,171,172,173,174,175,176,177,178,179,180,181,182,183,184,185,186,187,188,189,190,191,192,193,194,147,148,34,35,36,37,195,196,197,198,199,200,149,150,151,152,153,201,202,203,204,154,205,206,155,156,207,208,209,210,211,212,213,214,215,216,217,218,219,220,221,222,223,224,225,226,227,228,229,230,231,232,233,234,235,236,237,238,239,240,241,242,243,244,245,246,247,248,249,250,251,38,252,253,254,256,255,257,258,79,259,260,261,24,25,262,263,264,72,27,28,29,76,73,31,32,33,75,127,128,129,71
                    ,265,78,74,35,36,37,126,77,30,266,38,26,34,267,268];
                    $collection = collect(range(1,$totalentries+100))->map(function ($n)use ($exclude){ if(!in_array($n,$exclude)) return $n;})->reject(function($n){return empty($n);})->sort()->values()->all();
                    $entries = Fentry::where('eventcode',$request->eventId)->where('status','Pending')->where('review','<>','0')->whereNull('startno')->orderByRaw('CAST(code as INT)')->get();
                    $osnupdates = array();
                    if($entries){
                        for($i=0;$i<count($entries);$i++){
                            $snum['code']=$entries[$i]->code;
                            $snum['startno']=$collection[$i]."W";
                            array_push($osnupdates,$snum);
                        }
                        if(count($osnupdates)>0){
                            Multi::insertOrUpdate($osnupdates,'fentries');
                            return response()->json(['msg'=>sprintf('Updated %s entries',count($osnupdates)), 'entries'=>$osnupdates]);
                        }

                    }
                    break;
            }
            // return response()->json(['entries'=>$entries,'count'=>$tentries]);
        }
    }

    public function getlists(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'SearchEventID'=>'required',
          ]);
        $totalcount = 0;
        $tables = Fentry::where('eventcode','like',"%".strval(intval($request->SearchEventID)))->groupBy('stablename')->pluck('stablename')->toArray();
        $events = Fevent::selectRaw('CONCAT( CAST(raceid as INT), " : ", racename, "    |   Event Date - ", DATE_FORMAT( CAST(racefromdate as DATETIME),"%Y-%m-%d"), "    |   Opening - ", DATE_FORMAT( CAST(openingdate as DATETIME),"%Y-%m-%d %H:%i:%s"), "    |   Closing - ", DATE_FORMAT( CAST(closingdate as DATETIME),"%Y-%m-%d %H:%i:%s") ) as race, CAST(raceid as INT) as raceid')->where('statusname','like','%Entries%')->orWhere('statusname','like','%Closed%')->pluck('race','raceid')->toArray();
        $eventnames = Fevent::selectRaw('CAST(raceid as INT) as raceid,racename')->pluck('racename','raceid')->toArray();
        if($validator->fails()){
            return view('tempadmin.tlists',['modelName'=>'entry','stables'=>$tables,'events'=>$events,'eventnames'=>$eventnames,'entries'=>[]]);
        }
        
        $ppage= 15;
        if(isset($request->ppage)){
            $ppage = $request->ppage;
        }

        
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
        $fentries = $fentries->where('eventcode','like',"%".strval(intval($request->SearchEventID)))->where('status', 'Accepted')->orderByRaw('CAST(REPLACE(startno,"W","") as INT) asc');
        $eentries = $eentries->where('eventcode','like',"%".strval(intval($request->SearchEventID)))->where('classcode',"1")->whereIn('status',['Pending','Eligible'])->orderByRaw('CAST(REPLACE(startno,"W","") as INT) asc');
        $pentries = $pentries->where('eventcode','like',"%".strval(intval($request->SearchEventID)))->where('classcode',"3")->where('status', 'Pending')->where('review','<>','0')->orderByRaw('CAST(REPLACE(startno,"W","") as INT) asc');
        $reventries = $reventries->where('eventcode','like','%'.strval(intval($request->SearchEventID)))->where('status','Pending')->where('review','0')->orderByRaw('CAST(REPLACE(startno,"W","") as INT) asc');
        $rentries = $rentries->where('eventcode','like','%'.strval(intval($request->SearchEventID)))->whereIn('status',['Rejected','Withdrawn'])->orderByRaw('CAST(REPLACE(startno,"W","") as INT) asc');
        $pcentries = $pcentries->where('eventcode','like',"%".strval(intval($request->SearchEventID)))->where('classcode',"4")->where('status', 'Pending')->where('review','<>','0')->orderByRaw('CAST(REPLACE(startno,"W","") as INT) asc');
        if(isset($request->stablename)){
            $fentries = $fentries->whereIn('stablename',explode(',',$request->stablename))->orderByRaw('CAST(REPLACE(startno,"W","") as INT) asc');
            $eentries = $eentries->whereIn('stablename',explode(',',$request->stablename))->orderByRaw('CAST(REPLACE(startno,"W","") as INT) asc');
            $pentries = $pentries->whereIn('stablename',explode(',',$request->stablename))->orderByRaw('CAST(REPLACE(startno,"W","") as INT) asc');
            $reventries = $reventries->whereIn('stablename',explode(',',$request->stablename))->orderByRaw('CAST(REPLACE(startno,"W","") as INT) asc');
            $rentries = $rentries->whereIn('stablename',explode(',',$request->stablename))->orderByRaw('CAST(REPLACE(startno,"W","") as INT) asc');
            $pcentries = $pcentries->whereIn('stablename',explode(',',$request->stablename))->orderByRaw('CAST(REPLACE(startno,"W","") as INT) asc');
        }
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
            return view('tempadmin.tlists',['modelName'=>'entry','total'=>$totalcount,'events'=>$events,'eventnames'=>$eventnames,'stables'=>$tables,'entries'=>['final'=>$fentries,'pfa'=>$eentries,'pfr'=>$reventries,'prov'=>$pentries,'royprov'=>$pcentries,'re'=>$rentries]]);
        }
        return view('tempadmin.tlists',['modelName'=>'entry','total'=>$totalcount,'events'=>$events,'eventnames'=>$eventnames,'stables'=>$tables,'entries'=>['final'=>$fentries,'pfa'=>$eentries,'pfr'=>$reventries,'prov'=>$pentries,'re'=>$rentries]]);
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
        return redirect('/rideslist?SearchEventID='.$entry->eventcode);
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
            $data = (new FederationController)->updateentry($myRequest);
            Artisan::call('command:syncentries --ip=eievadmin --host=admineiev --entryid='.$entry->code);
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
            $data = (new FederationController)->updateentry($myRequest);
            Artisan::call('command:syncentries --ip=eievadmin --host=admineiev --entryid='.$entry->code);
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
        if($request->SearchEmail){
            $profiles = $profiles->where('eventcode','like',"%".$request->SearchEventID."%")->where('status', 'Accepted');
        }
        $profiles =isset($request->ppage)? $profiles->paginate($ppage): $profiles->get();
        return view('tempadmin.tentry',['modelName'=>'submitentry','profiles'=>$profiles]);
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
