<?php

namespace App\Http\Controllers;

use App\Models\Fhorse;
use Illuminate\Http\Request;

class FhorseController extends Controller
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
        $horses = Fhorse::query();
        if($request->HorseID){
            $horses = $horses->where('horseid','like',"%".$request->HorseID."%");
        }
        if($request->AdminUserID){
            $horses = $horses->where('adminuser','like',"%".$request->AdminUserID."%");
        }
        if($request->StableID){
            $horses = $horses->where('stableid','like',"%".$request->StableID."%");
        }
        if($request->SearchName){
            $horses = $horses->where('name','like',"%".$request->SearchName."%");
        }
        if($request->SearchFEIID){
            $horses = $horses->where('feipassport','like',"%".$request->SearchFEIID."%");
        }
        if($request->SearchEEFID){
            $horses = $horses->where('nfregistration','like',"%".$request->SearchEEFID."%");
        }
        if($request->SearchOwner){
            $horses = $horses->where('owner','like',"%".$request->SearchOwner."%");
        }
        if($request->SearchTrainer){
            $horses = $horses->where('trainer','like',"%".$request->SearchTrainer."%");
        }
        if($request->SearchStable){
            $horses = $horses->where('stable','like',"%".$request->SearchStable."%");
        }
        if($request->SearchNFPassport){
            $horses = $horses->where('nfpassportnumber','like',"%".$request->SearchNFPassport."%");
        }
        if($request->SearchMicrochip){
            $horses = $horses->where('microchip','like',"%".$request->SearchMicrochip."%");
        }
        if($request->SearchDiscipline){
            $horses = $horses->where('division','like',"%".$request->SearchDiscipline."%");
        }
        if($request->SearchDisciplineID){
            $horses = $horses->where('divisionid','like',"%".$request->SearchDisciplineID."%");
        }
        $horses = $horses->paginate($ppage);
        return responses()->json(['horses'=>$horses]);
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
     * @param  \App\Models\Fhorse  $fhorse
     * @return \Illuminate\Http\Response
     */
    public function show(Fhorse $fhorse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fhorse  $fhorse
     * @return \Illuminate\Http\Response
     */
    public function edit(Fhorse $fhorse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fhorse  $fhorse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fhorse $fhorse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fhorse  $fhorse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fhorse $fhorse)
    {
        //
    }
}
