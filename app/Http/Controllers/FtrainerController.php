<?php

namespace App\Http\Controllers;

use App\Models\Ftrainer;
use Illuminate\Http\Request;

class FtrainerController extends Controller
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
        $trainers = Ftrainer::query();
        if($request->TrainerID){
            $trainers = $trainers->where('trainerid','like',"%".$request->TrainerID."%");
        }
        if($request->AdminUserID){
            $trainers = $trainers->where('adminuser','like',"%".$request->AdminUserID."%");
        }
        if($request->StableID){
            $trainers = $trainers->where('stableid','like',"%".$request->StableID."%");
        }
        if($request->SearchFirstName){
            $trainers = $trainers->where('firstx0020name','like',"%".$request->SearchFirstName."%");
        }
        if($request->SearchLastName){
            $trainers = $trainers->where('familyx0020name','like',"%".$request->SearchLastName."%");
        }
        if($request->SearchFEIID){
            $trainers = $trainers->where('feix0020reg','like',"%".$request->SearchFEIID."%");
        }
        if($request->SearchEEFID){
            $trainers = $trainers->where('nfx0020license','like',"%".$request->SearchEEFID."%");
        }
        if($request->SearchStable){
            $trainers = $trainers->where('stable','like',"%".$request->SearchStable."%");
        }
        if($request->SearchNationality){
            $trainers = $trainers->where('nationality','like',"%".$request->SearchNationality."%")->orWhere('nationalityshort','like',"%".$request->SearchNationality."%");
        }
        if($request->SearchGender){
            $trainers = $trainers->where('gender','like',"%".$request->SearchGender."%");
        }
        if($request->SearchDiscipline){
            $trainers = $trainers->where('division','like',"%".$request->SearchDiscipline."%");
        }
        if($request->SearchDisciplineID){
            $trainers = $trainers->where('divisionid','like',"%".$request->SearchDisciplineID."%");
        }
        $trainers = $trainers->paginate($ppage);
        return response()->json(['trainers'=>$trainers]);
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
     * @param  \App\Models\Ftrainer  $ftrainer
     * @return \Illuminate\Http\Response
     */
    public function show(Ftrainer $ftrainer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ftrainer  $ftrainer
     * @return \Illuminate\Http\Response
     */
    public function edit(Ftrainer $ftrainer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ftrainer  $ftrainer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ftrainer $ftrainer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ftrainer  $ftrainer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ftrainer $ftrainer)
    {
        //
    }
}
