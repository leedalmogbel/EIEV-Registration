<?php

namespace App\Http\Controllers;

use App\Models\Fentry;
use App\Models\Fevent;
use App\Models\Userprofile;
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
        $royallist = array();
        $royallist["E0000004"]=[336,337,338];
        $royallist["E0000006"]=[35,36];
        $royallist["E0000012"]=[155,156];
        $royallist["E0000037"]=[28,126,127,128,179];
        $royallist["E0000052"]=[327,328];
        $royallist["E0000061"]=[80,81];
        $royallist["E0000086"]=[207,285,286];
        $royallist["E0000103"]=[6,65];
        $royallist["E0000111"]=[99,228];
        $royallist["E0000112"]=[61,62,329];
        $royallist["E0000115"]=[224,225];
        $royallist["E0000117"]=[248,249];
        $royallist["E0000158"]=[16,17];
        $royallist["E0000172"]=[15,71,88];
        $royallist["E0000190"]=[8,9];
        $royallist["E0000191"]=[218,219];
        $royallist["E0000192"]=[220,221];
        $royallist["E0000193"]=[74,165,];
        $royallist["E0000195"]=[4,5,348];
        $royallist["E0000214"]=[318,319];
        $royallist["E0000253"]=[161,162];
        $royallist["E0000267"]=[22,24,25,27,184,204];
        $royallist["E0000268"]=[26,205,243,259];
        $royallist["E0000269"]=[19,178,];
        $royallist["E0000270"]=[29,20,30,21,206];
        $royallist["E0000275"]=[201,202];
        $royallist["E0000283"]=[];
        $royallist["E0000292"]=[];
        $royallist["E0000293"]=[260,261];
        $royallist["E0000316"]=[250,251];
        $royallist["E0000318"]=[32,33,87,23];
        $royallist["E0000321"]=[];
        $royallist["E0000327"]=[];
        $royallist["E0000330"]=[244,147,146,149,145];
        $royallist["E0000342"]=[];
        $royallist["E0000344"]=[306,307,305,308];
        $royallist["E0000374"]=[321,322];
        $royallist["E0000375"]=[55,54];
        $royallist["E0000379"]=[18,31,37,14];
        $royallist["E0000381"]=[50,52,49,152,51,53];
        $royallist["E0000384"]=[143,94,97,96,153,98];
        $royallist["E0000386"]=[91,95,93,90,92];
        $royallist["E0000403"]=[57];
        $royallist["E0000409"]=[];
        $royallist["E0000410"]=[302,309];
        $royallist["E0000427"]=[39,34,38];
        $royallist["E0000434"]=[79,72,78,77];
        $royallist["E0000443"]=[316,317];
        $royallist["E0000454"]=[];
        $royallist["E0000468"]=[312,314,313,315];
        $royallist["E0000486"]=[217,215];
        $royallist["E0000491"]=[262,263];
        $royallist["E0000501"]=[157,154];
        $royallist["E0000527"]=[292,291];
        $royallist["E0000540"]=[];
        $royallist["E0000542"]=[311,310];
        $royallist["E0000567"]=[];
        $royallist["E0000568"]=[];
        $royallist["E0000596"]=[];
        $royallist["E0000612"]=[];
        $royallist["E0000613"]=[];
        $royallist["E0000617"]=[];
        $royallist["E0000639"]=[];
        if(isset($request->eventId) && isset($request->action)){
            switch ($request->action) {
                case 'royal':
                    $royalstables = Fstable::where('category','Royal')->pluck('stableid')->toArray();
                    $entries = Fentry::whereIn('stableid',$royalstables)->where('eventcode',$request->eventId)->where('status','Pending')->where('review','<>','0')->whereNull('startno')->orderByRaw('CAST(code as INT)')->get();
                    $rsnupdates = array();
                    foreach ($entries as $entry) {
                        if(isset($royallist[$entry->stableid])){
                            if(count($royallist[$entry->stableid]) > 0){
                                $snum = array();
                                $snumlist = Arr::sort($royallist[$entry->stableid]);
                                $snum['code']=$entry->code;
                                $snum['startno']=$snumlist[0];
                                array_push($rsnupdates,$snum);
                                $royallist[$entry->stableid] = array_splice($snumlist,0,0);
                            }
                        }
                    }
                    if(count($rsnupdates)>0){
                        // Multi::insertOrUpdate($rsnupdates,'fentries');
                        return response()->json(['msg'=>sprintf('Updated %s entries',count($rsnupdates)), 'entries'=>$rsnupdates]);
                    }
                    return response()->json(['msg'=>'No entries updated.']);
                    break;
                case 'others':
                    $exclude = [1,2,3,7,10];
                    $collection = collect(range(1,$request->size??400))->map(function ($n){ if(!in_array($n,$exclude)) return $n;})->reject(function($n){return empty($n);})->sort()->values()->all();
                    $entries = Fentry::where('eventcode',$request->eventId)->where('status','Pending')->where('review','<>','0')->whereNull('startno')->orderByRaw('CAST(code as INT)')->get();
                    if($entries){
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
        $fentries = $fentries->where('eventcode','like',"%".strval(intval($request->SearchEventID)))->where('status', 'Accepted');
        $eentries = $eentries->where('eventcode','like',"%".strval(intval($request->SearchEventID)))->where('classcode',"1")->whereIn('status',['Pending','Eligible']);
        $pentries = $pentries->where('eventcode','like',"%".strval(intval($request->SearchEventID)))->where('classcode',"3")->where('status', 'Pending')->where('review','<>','0');
        $reventries = $reventries->where('eventcode','like','%'.strval(intval($request->SearchEventID)))->where('status','Pending')->where('review','0');
        $rentries = $rentries->where('eventcode','like','%'.strval(intval($request->SearchEventID)))->whereIn('status',['Rejected','Withdrawn']);
        $pcentries = $pcentries->where('eventcode','like',"%".strval(intval($request->SearchEventID)))->where('classcode',"4")->where('status', 'Pending')->where('review','<>','0');
        if(isset($request->stablename)){
            $fentries = $fentries->whereIn('stablename',explode(',',$request->stablename));
            $eentries = $eentries->whereIn('stablename',explode(',',$request->stablename));
            $pentries = $pentries->whereIn('stablename',explode(',',$request->stablename));
            $reventries = $reventries->whereIn('stablename',explode(',',$request->stablename));
            $rentries = $rentries->whereIn('stablename',explode(',',$request->stablename));
            $pcentries = $pcentries->whereIn('stablename',explode(',',$request->stablename));
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
