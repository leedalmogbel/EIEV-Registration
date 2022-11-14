<?php

namespace App\Http\Controllers;

use App\Models\Fentry;
use Illuminate\Http\Request;

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
        $fieldlist = ["SearchEventID",'ClassCode'];
        $ppage= 15;
        if(isset($request->ppage)){
            $ppage = $request->ppage;
        }
        $fentries = Fentry::query();
        if($request->SearchEventID){
            $fentries = $fentries->where('eventcode','like',"%".$request->SearchEventID."%")->where('classcode',1)->where('status', 'Accepted');
        }
        $fentries =isset($request->ppage)? $fentries->paginate($ppage): $fentries->get();

        $eentries = Fentry::query();
        if($request->SearchEventID){
            $eentries = $eentries->where('eventcode','like',"%".$request->SearchEventID."%")->where('classcode',1)->where('status', 'Eligible');
        }
        $eentries =isset($request->ppage)? $eentries->paginate($ppage): $eentries->get();
        $pentries = Fentry::query();
        if($request->SearchEventID){
            $pentries = $pentries->where('eventcode','like',"%".$request->SearchEventID."%")->where('classcode',3);
        }
        $pentries =isset($request->ppage)? $pentries->paginate($ppage): $pentries->get();

        $pcentries = Fentry::query();
        if(isset($request->presidentcup)){
            if($request->SearchEventID){
                $pcentries = $pcentries->where('eventcode','like',"%".$request->SearchEventID."%")->where('classcode',4);
            }
            $pcentries =isset($request->ppage)? $pcentries->paginate($ppage): $pcentries->get();
            return response()->json(['modelName'=>'entry','entries'=>['final entry'=>$fentries,'main entry'=>$eentries,'private entry'=>$pentries,'royal entry'=>$pcentries]]);
        }
        // return response()->json(['modelName'=>'entry','entries'=>['final entry'=>$fentries,'pending entry'=>$pentries]]);
        // dd(['entries'=>['final entry'=>$fentries,'pending entry'=>$pentries]]);
        // if(session()->get('role')->role_id != 1){
        //     return redirect('/dashboard');
        // }
        return view('tempadmin.tlists',['modelName'=>'entry','entries'=>['final entry'=>$fentries,'main entry'=>$eentries,'private entry'=>$pentries]]);
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
