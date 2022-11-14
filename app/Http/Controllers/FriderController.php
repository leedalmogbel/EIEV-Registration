<?php

namespace App\Http\Controllers;

use App\Models\Frider;
use Illuminate\Http\Request;

class FriderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ppage = 15;
        if(isset($request->ppage)){
            $ppage = $request->ppage;
        }
        $riders = Frider::query();
        if($request->RiderID){
            $riders = $riders->where('riderid','like',"%".$request->RiderID."%");
        }
        if($request->AdminUserID){
            $riders = $riders->where('adminuser','like',"%".$request->AdminUserID."%");
        }
        if($request->StableID){
            $riders = $riders->where('stableid','like',"%".$request->StableID."%");
        }
        if($request->SearchFullName){
            $riders = $riders->where('firstx0020name','like',"%".$request->SearchFullName."%")->orwhere('familyx0020name','like',"%".$request->SearchFullName."%");
        }
        if($request->SearchFirstName){
            $riders = $riders->where('firstx0020name','like',"%".$request->SearchFirstName."%");
        }
        if($request->SearchLastName){
            $riders = $riders->where('familyx0020name','like',"%".$request->SearchLastName."%");
        }
        if($request->SearchFEIID){
            $riders = $riders->where('feix0020reg','like',"%".$request->SearchFEIID."%");
        }
        if($request->SearchEEFID){
            $riders = $riders->where('nfx0020license','like',"%".$request->SearchEEFID."%");
        }
        if($request->SearchStable){
            $riders = $riders->where('stable','like',"%".$request->SearchStable."%");
        }
        if($request->SearchNationality){
            $riders = $riders->where('nationality','like',"%".$request->SearchNationality."%")->orWhere('nationalityshort','like',"%".$request->SearchNationality."%");
        }
        if($request->SearchGender){
            $riders = $riders->where('gender','like',"%".$request->SearchGender."%");
        }
        if($request->SearchDiscipline){
            $riders = $riders->where('division','like',"%".$request->SearchDiscipline."%");
        }
        if($request->SearchDisciplineID){
            $riders = $riders->where('divisionid','like',"%".$request->SearchDisciplineID."%");
        }
        $riders = $riders->paginate($ppage);
        return response()->json(['riders'=>$riders]);
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
     * @param  \App\Models\Frider  $frider
     * @return \Illuminate\Http\Response
     */
    public function show(Frider $frider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Frider  $frider
     * @return \Illuminate\Http\Response
     */
    public function edit(Frider $frider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Frider  $frider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Frider  $frider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Frider $frider)
    {
        //
    }
}
