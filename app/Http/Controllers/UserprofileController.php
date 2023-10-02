<?php

namespace App\Http\Controllers;

use App\Models\Userprofile;
use App\Models\Reusable;
use App\Models\Multi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;


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
        $userprofiles = Userprofile::query(); 
        if(isset($request->SearchEmail)){
            $userprofiles = $userprofiles->where('email','like','%'.$request->SearchEmail.'%');
        }
        $userprofiles =isset($request->ppage)? $userprofiles->paginate($ppage):$userprofiles->get();
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

    public function getQr(Request $request)
    {
        $profile = UserProfile::where('userid',$request->userid)->first();
        if($profile){
            return QrCode::style($request->style??'square')->encoding('UTF-8')->size($request->size ?? 200)->generate($profile->uniqueid);
        //    return response(QrCode::encoding('UTF-8')->size(500)->generate('hello'))->header('Content-type','image/png');
        }
        return response()->json(['msg'=>'No profile found'],400);
    }

    public function generateQrCode() {
        //  TODO: generate qr code for jeffrey
        // redirect the user on url if its ios or android
        // $url = 'htts://registration.eiev-app.com/';
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
