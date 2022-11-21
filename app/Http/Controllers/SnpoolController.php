<?php

namespace App\Http\Controllers;

use App\Models\Snpool;
use Illuminate\Http\Request;

class SnpoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function addToPool(Request $request)
    {
        if(isset($request->userid) && isset($request->stableid) && isset($request->sns)){
            $snlist = explode(',',$request->sns);
            foreach ($snlist as $sn) {
                $snarr = array();
                $snarr['userid'] = $request->userid;
                $snarr['stableid'] = $request->stableid;
                $snarr['startno'] = $sn;
                $snexist = Snpool::where('userid',$request->userid)->where('stableid',$request->stableid)->where('startno',$sn)->first();
                if(!$snexist){
                    Snpool::create($snarr);
                }
            }
            return response()->json(['msg'=>sprintf('Added %s numbers',count($snlist))]);
        }
        return response()->json(['msg'=>'Nothing to do.'],400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Snpool  $snpool
     * @return \Illuminate\Http\Response
     */
    public function show(Snpool $snpool)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Snpool  $snpool
     * @return \Illuminate\Http\Response
     */
    public function edit(Snpool $snpool)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Snpool  $snpool
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Snpool  $snpool
     * @return \Illuminate\Http\Response
     */
    public function destroy(Snpool $snpool)
    {
        //
    }
}
