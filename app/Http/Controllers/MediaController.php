<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $list = Media::all();
        return view('pages.media.medialist', ['lists' => $list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createMediaForm()
    {
        //
        return view('media_register');
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
        $validator = Validator::make($request->all(), [
            'emirates_id' => 'required',
            'mobile' => 'required',
            'email' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
        ]);

        if ($validator->fails()) {
            $this->flashMsg('Required Info must be filled out.', 'warning');
            return redirect()->back()->withInput();
        }


        try {
            $media_uuid = (string) Str::uuid();
            $photo = "";
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $fileName = time() . rand(100, 999) . $file->getClientOriginalName();
                $destinationPath = public_path() . "/img/" . $request->emirates_id . "/media-" . $media_uuid;
                $file->move($destinationPath, $fileName);
                $photo = '/img/' . $request->emirates_id . "/media-" . $media_uuid . "/" . $fileName;
            }

            $media = new Media;
            $media->firstname = $request->firstname ?? '';
            $media->lastname = $request->lastname ?? '';
            $media->mobile = $request->mobile ?? '';
            $media->email = $request->email ?? '';
            $media->company = $request->company ?? '';
            $media->photo =  $photo ?? '';
            $media->emirates_id = $request->emirates_id ?? '';

            $media->save();
            $this->flashMsg(sprintf('Data has been saved successfully.'), 'success');
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return redirect('/media');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function show(Media $media)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function edit(Media $media)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function updateMedia(Request $request, Media $media)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function destroy(Media $media)
    {
        //
    }
}
