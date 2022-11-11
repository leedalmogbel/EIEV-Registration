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
      if($request->SearchEntryID){
          $entries = $entries->where('code','like',"%".$request->SearchEntryID."%");
      }
      if($request->SearchEventID){
          $entries = $entries->where('eventcode','like',"%".$request->SearchEventID."%");
      }
      if($request->SearchHorseID){
          $entries = $entries->where('horseid','like',"%".$request->SearchHorseID."%");
      }
      if($request->SearchRiderID){
          $entries = $entries->where('riderid','like',"%".$request->SearchRiderID."%");
      }
      if($request->SearchUserID){
          $entries = $entries->where('userid','like',"%".$request->SearchUserID."%");
      }
      if($request->SearchStableID){
          $entries = $entries->where('stableid','like',"%".$request->SearchStableID."%");
      }
      $entries = $entries->paginate($ppage);
      return responses()->json(['entries'=>$entries]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
    public function update(Request $request, Fentry $fentry)
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
