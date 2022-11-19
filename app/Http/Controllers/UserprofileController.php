<?php

namespace App\Http\Controllers;

use App\Models\Userprofile;
use App\Models\Reusable;
use App\Models\Multi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserprofileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $ppage = 15;
        if(isset($request->ppage)){
            $ppage = $request->ppage;
        }
        $userprofiles = Userprofile::paginate($ppage); 
        if(isset($request->SearchEmail)){
            $userprofiles = Userprofile::where('email','like','%'.$request->SearchEmail.'%')->paginate($ppage);
        }
        return response()->json(['userprofiles'=>$userprofiles]);
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

    public function generateUnique(Request $request)
    {
        $profiles = Userprofile::query();
        if(isset($request->userid)){
            $profiles = $profiles->where('userid',$request->userid);
        }
        if(isset($request->stableid)){
            $profiles = $profiles->where('stableid',$request->stableid);
        }
        $ids = $profiles->pluck('userid');
        $uniqueids = array();
        foreach ($ids as $id) {
            $up = array();
            $up['uniqueid'] = Reusable::generateReusableUnique('',sprintf('EIEV-%s',Str::padLeft($id, 8, '0')),'',true,'ouuid');
            $up['userid'] = $id;
            array_push($uniqueids,$up);
        }
        if(count($uniqueids)>0){
            Multi::insertOrUpdate($uniqueids,'userprofiles');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Userprofile  $userprofile
     * @return \Illuminate\Http\Response
     */
    public function show(Userprofile $userprofile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Userprofile  $userprofile
     * @return \Illuminate\Http\Response
     */
    public function edit(Userprofile $userprofile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Userprofile  $userprofile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Userprofile  $userprofile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Userprofile $userprofile)
    {
        //
    }
}
