<?php

namespace App\Http\Controllers;

use App\Models\Fowner;
use Illuminate\Http\Request;

class FownerController extends Controller
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
        $owners = Fowner::query();
        if($request->OwnerID){
            $owners = $owners->where('ownerid','like',"%".$request->OwnerID."%");
        }
        if($request->AdminUserID){
            $owners = $owners->where('adminuser','like',"%".$request->AdminUserID."%");
        }
        if($request->StableID){
            $owners = $owners->where('stableid','like',"%".$request->StableID."%");
        }
        if($request->SearchFirstName){
            $owners = $owners->where('firstx0020name','like',"%".$request->SearchFirstName."%");
        }
        if($request->SearchLastName){
            $owners = $owners->where('familyx0020name','like',"%".$request->SearchLastName."%");
        }
        if($request->SearchFEIID){
            $owners = $owners->where('feix0020reg','like',"%".$request->SearchFEIID."%");
        }
        if($request->SearchEEFID){
            $owners = $owners->where('nfx0020license','like',"%".$request->SearchEEFID."%");
        }
        if($request->SearchStable){
            $owners = $owners->where('stable','like',"%".$request->SearchStable."%");
        }
        if($request->SearchNationality){
            $owners = $owners->where('nationality','like',"%".$request->SearchNationality."%")->orWhere('nationalityshort','like',"%".$request->SearchNationality."%");
        }
        if($request->SearchGender){
            $owners = $owners->where('gender','like',"%".$request->SearchGender."%");
        }
        if($request->SearchDiscipline){
            $owners = $owners->where('division','like',"%".$request->SearchDiscipline."%");
        }
        if($request->SearchDisciplineID){
            $owners = $owners->where('divisionid','like',"%".$request->SearchDisciplineID."%");
        }
        $owners = $owners->paginate($ppage);
        return responses()->json(['owners'=>$owners]);
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
     * @param  \App\Models\Fowner  $fowner
     * @return \Illuminate\Http\Response
     */
    public function show(Fowner $fowner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fowner  $fowner
     * @return \Illuminate\Http\Response
     */
    public function edit(Fowner $fowner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fowner  $fowner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fowner $fowner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fowner  $fowner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fowner $fowner)
    {
        //
    }
}
