<?php

namespace App\Http\Controllers;

use App\Models\Fevent;
use Illuminate\Http\Request;

class FeventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $fieldlist = ["SearchSeasonCode","SearchEventCode"];
      $ppage = 15;
			if(isset($request->ppage)){
				$ppage = $request->ppage;
			}
			$events = Fevent::query();
			if($request->SearchSeasonCode){
					$events = $events->where('seasonid','like',"%".$request->SearchSeasonCode."%");
			}
			if($request->SearchEventCode){
					$events = $events->where('raceid','like',"%".$request->SearchEventCode."%");
			}
			$events = $events->paginate($ppage);
			return response()->json(['events'=>$events]);
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
     * @param  \App\Models\Fevent  $fevent
     * @return \Illuminate\Http\Response
     */
    public function show(Fevent $fevent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fevent  $fevent
     * @return \Illuminate\Http\Response
     */
    public function edit(Fevent $fevent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fevent  $fevent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fevent  $fevent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fevent $fevent)
    {
        //
    }
}
