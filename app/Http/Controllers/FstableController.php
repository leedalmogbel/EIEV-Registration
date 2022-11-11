<?php

namespace App\Http\Controllers;

use App\Models\Fstable;
use Illuminate\Http\Request;

class FstableController extends Controller
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
        $stables = Fstable::query();
        if($request->StableID){
            $stables = $stables->where('stableid','like',"%".$request->StableID."%");
        }
        $stables = $stables->paginate($ppage);
        return responses()->json(['stables'=>$stables]);
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
     * @param  \App\Models\Fstable  $fstable
     * @return \Illuminate\Http\Response
     */
    public function show(Fstable $fstable)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fstable  $fstable
     * @return \Illuminate\Http\Response
     */
    public function edit(Fstable $fstable)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fstable  $fstable
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fstable $fstable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fstable  $fstable
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fstable $fstable)
    {
        //
    }
}
