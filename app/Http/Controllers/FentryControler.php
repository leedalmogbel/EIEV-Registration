<?php

namespace App\Http\Controllers;

use App\Models\Fentry;
use App\Models\Fevent;
use App\Models\Userprofile;
use App\Models\Multi;
use Illuminate\Http\Request;
use App\Http\Controllers\FederationController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

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

    public function getlists(Request $request)
    {
        // $validator = Validator::make($request->all(),[
        //     'SearchEventID'=>'required',
        //   ]);
        //   if($validator->fails()){
        //     return response()->json(["error" => $validator->errors()]);
        //   }
        // $fieldlist = ["SearchEventID",'ClassCode'];
        
        $ppage= 15;
        if(isset($request->ppage)){
            $ppage = $request->ppage;
        }
        $stables = Fentry::groupBy('stablename')->pluck('stablename')->toArray();
        // $events= [];
        $events= Fevent::whereNotIn('statusname',['Pending','closed for entries','Closed for Entries'])->pluck('racename','raceid')->toArray();
        array_splice($stables,0,0,['All']);
        if(count($events)>0){
            //final list
            $fentries = Fentry::query();
            $fentries = $fentries->where('eventcode','like',"%".strval(intval(array_keys($events)[0])))->where('status', 'Accepted');
            $fentries =isset($request->ppage)? $fentries->paginate($ppage): $fentries->get();
            // return response()->json(['stables'=>$stables,'entries'=>$fentries]);
            //limited entries
            $eentries = Fentry::query();
            $eentries = $eentries->where('eventcode','like',"%".strval(intval(array_keys($events)[0])))->where('classcode',"1");
            $eentries =isset($request->ppage)? $eentries->paginate($ppage): $eentries->get();
            //regular / private entries
            $pentries = Fentry::query();
            $pentries = $pentries->where('eventcode','like',"%".strval(intval(array_keys($events)[0])))->where('classcode',"3")->where('status', 'Pending')->where('review','<>','0');
            $pentries =isset($request->ppage)? $pentries->paginate($ppage): $pentries->get();
            //pending for approval
            $reventries = Fentry::query();
            $reventries = $reventries->where('eventcode','like','%'.strval(intval(array_keys($events)[0])))->where('status','Pending')->where('review','0');
            $reventries = isset($request->ppage)? $reventries->paginate($ppage) : $reventries->get();
            //rejected entries
            $rentries = Fentry::query();
            $rentries = $rentries->where('eventcode','like','%'.strval(intval(array_keys($events)[0])))->whereIn('status',['Rejected','Withdrawn']);
            $rentries = isset($request->ppage)? $rentries->paginate($ppage) : $rentries->get();
            //royal for president cup
            $pcentries = Fentry::query();
            if(isset($request->presidentcup)){
                $pcentries = $pcentries->where('eventcode','like',"%".strval(intval(array_keys($events)[0])))->where('classcode',"4")->where('status', 'Pending')->where('review','<>','0');
                $pcentries =isset($request->ppage)? $pcentries->paginate($ppage): $pcentries->get();
                return view('tempadmin.tlists',['modelName'=>'entry','stables'=>$stables,'events'=>$events,'entries'=>['final'=>$fentries,'pfa'=>$eentries,'pfr'=>$reventries,'prov'=>$pentries,'royprov'=>$pcentries,'re'=>$rentries]]);
            }
            return view('tempadmin.tlists',['modelName'=>'entry','stables'=>$stables,'events'=>$events,'entries'=>['final'=>$fentries,'pfa'=>$eentries,'pfr'=>$reventries,'prov'=>$pentries,'re'=>$rentries]]);
        }

        return view('tempadmin.tlists',['modelName'=>'entry','entries'=>[]]);

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
