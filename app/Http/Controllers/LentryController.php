<?php

namespace App\Http\Controllers;

use App\Models\Lentry;
use App\Models\Fevent;
use Illuminate\Http\Request;
use App\Models\Userprofile;
use GuzzleHttp\Client;
use App\Models\Multi;
use Illuminate\Support\Str;

class LentryController extends Controller
{
    

    public function actions(Request $request)
    {
        if(isset($request->code)){
            $profile = Userprofile::where('uniqueid',$request->code)->first();
            $iitems = [
                [
                    "flds"=>["pStartNo","pStartCode","pOwnerName","pTrainerName","pStableName"],
                    "cnames"=>["col-1","col-1","col-3","col-3","col-4"],
                    "lbls"=>["SN","SC","OWNER","TRAINER","STABLE"]
                ],
                [
                    "flds"=>["pRiderName","pRiderFname","pRiderLname"],
                    "cnames"=>["col-4","col-4","col-4"],
                    "lbls"=>["RNAME","FNAME","LNAME"]
                ],
                [
                    "flds"=>["pRiderLicenseFei","pRiderLicenseEef","pRiderNationality","pRiderGender"],
                    "cnames"=>["col-3","col-3","col-3","col-3"],
                    "lbls"=>["RFEI","REEF","RNAT","RGENDER"]
                ],
                [
                    "flds"=>["pHorseName","pHorseLicenseFei","pHorseLicenseEef","pHorseOrigin"],
                    "cnames"=>["col-3","col-3","col-3","col-3"],
                    "lbls"=>["HNAME","HFEI","HEEF","HORIGIN"]
                ],
                [
                    "flds"=>["pHorseYear","pHorseGender","pHorseColor","pHorseBreed","pHorseChip"],
                    "cnames"=>["col-2","col-2","col-2","col-2","col-4"],
                    "lbls"=>["HYOB","HGENDER","HCOLOR","HBREED","HCHIP"]
                ],
                [
                    "flds"=>["pContactPerson","pContactNumber","pEvtCateg","pIdCode"],
                    "cnames"=>["col-4","col-4","col-2","col-2"],
                    "lbls"=>["CPERSON","CNUMBER","EVTCAT","IDCODE"]
                ],
            ];
            $actions = [
                'add'=>["cname"=>"btn btn-success","lbl"=>"Add Entry"],
                'update'=>["cname"=>"btn btn-primary","lbl"=>"Update Entry"],
                'swap'=>["cname"=>"btn btn-warning d-none","lbl"=>"Swap Entries"],
                'swap-toggle'=>["cname"=>"btn btn-warning","lbl"=>"Enable Swap Mode"],
                'delete'=>["cname"=>"btn btn-danger","lbl"=>"Delete Entry"]
            ];
            if($profile){
                $entries = Lentry::where('userid',$profile->userid)->where('stableid',$profile->stableid)->where('status','Accepted')->orderByRaw('CAST(startno as SIGNED)')->get();
                return view('tempadmin.tactions',['actions'=>$actions,'profile'=>$profile,'entries'=>$entries,'inputs'=>$iitems]);
            }
        }
        return view('tempadmin.tactions',['actions'=>[],'profile'=>[],'entries'=>[]]);
    }



    public function syncentriesfromcloud(Request $request)
    {

        $api_url = 'https://devregistration.eiev-app.ae/api/getentries/';

        $options = [
            'headers' => [
                "38948f839e704e8dbd4ea2650378a388" => "0b5e7030aa4a4ee3b1ccdd4341ca3867"
            ],
        ];
        $httpClient = new \GuzzleHttp\Client();
        $client = new Client();
        $response = $client->request('GET',$api_url, $options);
        $data = json_decode($response->getBody(),true);
        
        if(count($data["entries"])>0){
            Multi::insertOrUpdate($data["entries"],'lentries');
            return response()->json(['msg'=>sprintf('Updated %s entries',count($data['entries']))]);
        }
        return response()->json(['msg'=>'No action done.']);
    }

    public function syncprofilesfromcloud(Request $request)
    {

        $api_url = 'https://devregistration.eiev-app.ae/api/getprofiles/';

        $options = [
            'headers' => [
                "38948f839e704e8dbd4ea2650378a388" => "0b5e7030aa4a4ee3b1ccdd4341ca3867"
            ],
        ];
        $httpClient = new \GuzzleHttp\Client();
        $client = new Client();
        $response = $client->request('GET',$api_url, $options);
        $data = json_decode($response->getBody(),true);
        if(count($data["userprofiles"])>0){
            Multi::insertOrUpdate($data["userprofiles"],'userprofiles');
            return response()->json(['msg'=>sprintf('Updated %s profiles',count($data['userprofiles']))]);
        }
        return response()->json(['msg'=>'No action done.']);
    }

    public function uploadAll(Request $request)
    {
        if(!isset($request->eventid)){
            return response()->json(['msg'=>'No action done.'],400);
        }
        $entries = Lentry::where('status','Accepted')->where('inserted',0)->where('eventcode',$request->eventid)->get();
        $event = Fevent::where('raceid','like','%'.strval(intval($request->eventid)))->first();

        if($entries){
            $process = array();

            foreach ($entries as $entry) {
                $profile = Userprofile::where('userid',$entry->userid)->first();
                
                $cperson="";
                $cmobile="";
                if($profile){
                    $cperson = $profile->fname ." ". $profile->lname;
                    $cmobile = $profile->mobileno;
                }
                $myRequest = new \Illuminate\Http\Request();
                $myRequest->setMethod('POST');
                $myRequest->request->add([
                    'action'=>'InsertEntriesV2',
                    'showraw'=>$request->has('showraw') ? true:false,
                    'params'=>['pStartNo'=>$entry->startno,
                    'pStartCode'=>$entry->racestartcode,
                    'pOwnerName'=>$entry->ownername,
                    'pTrainerName'=>$entry->trainername,
                    'pStableName'=>$entry->stablename,
                    'pRiderName'=>$entry->ridername,
                    'pRiderFname'=>$entry->rfname,
                    'pRiderLname'=>$entry->rlname,
                    'pRiderLicenseFei'=>$entry->riderfeiid,
                    'pRiderLicenseEef'=>$entry->ridernfid,
                    'pRiderNationality'=>$entry->rcountry,
                    'pRiderGender'=>$entry->rgender,
                    'pHorseName'=>$entry->horsename,
                    'pHorseLicenseFei'=>$entry->horsefeiid,
                    'pHorseLicenseEef'=>$entry->horsenfid,
                    'pHorseOrigin'=>$entry->horigin,
                    'pHorseYear'=>$entry->yob,
                    'pHorseGender'=>$entry->hgender,
                    'pHorseColor'=>$entry->color,
                    'pHorseBreed'=>$entry->breed,
                    'pHorseChip'=>$entry->microchip,
                    'pContactPerson'=>$cperson,
                    'pRiderImage'=>null,
                    'pContactNumber'=>$cmobile,
                    'pEvtCateg'=>$event->typename == "National" ? "1":"2",
                    'pIdCode'=>$request->eventid,
                    'pBarcodeValue'=>$entry->qrval],
                ]);
                $data = (new LentryController)->soapCall($myRequest);
                if(isset($data['insertentriesv2result'])){
                    if($data['insertentriesv2result']['result']=="true"){
                        $entryinserted = 1;
                        $entry->save();
                    }
                }
                array_push($process,$data);
            }
            return response()->json(['msg'=>sprintf('Inserted %s entries successfully',count($process)),'process'=>$process]);
        }
    }

    public function soapCall(Request $request)
    {
      $fieldlist = [
        'pRaceId',
        'pEvtCateg',
        'pIdCode',
        'pStartNo',
        'pStartNo1',
        'pStartNo2',
        'pStartCode',
        'pRiderName',
        'pRiderFname',
        'pRiderLname',
        'pRiderLicenseFei',
        'pRiderLicenseEef',
        'pRiderNationality',
        'pHorseName',
        'pHorseYear',
        'pHorseGender',
        'pHorseColor',
        'pHorseBreed',
        'pHorseLicenseFei',
        'pHorseLicenseEef',
        'pHorseChip',
        'pOwnerName',
        'pTrainerName',
        'pStableName',
        'pContactPerson',
        'pContactNumber',
        'pRiderImage',
        'pExecutedBy',
        'pHorseOrigin',
        'pRiderGender',
        'pBarcodeValue',
      ];
      if(isset($request->params)){
        $arrkeys= array_keys($request->params);
        $validationResult=$this->validateData($fieldlist,$arrkeys);
        if(!$validationResult['allow']){
          return response()->json(['error'=>$validationResult['msg']],400);
        }
      }

        $xml = new \SimpleXMLElement('<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"/>');

        $body = $xml->addChild('Body');
        $action = $body->addChild($request->action,"","http://tempuri.org/");
        if(isset($request->params)){
            $keys = array_keys($request->params);
            foreach ($keys as $key) {
                $action->addChild($key,$request->params[$key]);
            }
        }
        $dom = dom_import_simplexml($xml);
        $cleanXML = preg_replace('/xmlns[^=]*="[a-z]*"/i', '',
            $dom->ownerDocument
            ->saveXML($dom->ownerDocument->documentElement)
        );
        $options = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=utf-8',
                "SOAPAction"=>"http://tempuri.org/".$request->action,
            ],
            'body' =>$cleanXML
        ];
        // return $cleanXML;
        $client = new Client();
        $response = $client->post(env("SOAP_BASE_URL"), $options);
        if($request->showraw){
            return $response->getBody();
        }
        $result = '!'.$request->action.'Result|'.strtolower($request->action).'result-Result|result-ErrorMessage|errormsg-Status|status-Remarks|remarks';
        if(isset($request->includes)){
            if(Str::contains($request->includes,",")){
                $rlist = explode(',',$request->includes);
                foreach ($rlist as $r) {
                    $result .= "-".$r.'|'.strtolower($r);
                }
            }else{
                $result .= "-".$request->includes.'|'.strtolower($request->includes);
            }
        }
        // return $result;
        return $this->extractData((string)$response->getBody()->getContents(),$result);
    }
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lentry  $lentry
     * @return \Illuminate\Http\Response
     */
    public function show(Lentry $lentry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lentry  $lentry
     * @return \Illuminate\Http\Response
     */
    public function edit(Lentry $lentry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lentry  $lentry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lentry  $lentry
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lentry $lentry)
    {
        //
    }

    function validateData($fieldlist,$paramlist)
    {
      $unnecessary=[];
      $necessary=[];
      foreach ($paramlist as $key) {
        if(in_array($key,$fieldlist)){
          array_push($necessary,$key);
        }else{
          array_push($unnecessary,$key);
        }
      }
      $allow = count($necessary) == count($paramlist);
      return ["allow"=>$allow, "msg"=>$allow ? "proceed" :"Unexpected key/s: ".implode(',',$unnecessary)];
    }
    function extractData($xml,$params,$debug=false,$options=false){
        $data = array();
        $xml = Str::of($xml)->remove(' xmlns=""');
        
        if(!Str::contains($params,'#')){
            
            if(Str::contains($params,'-')){
                
                $parampipes = Str::of($params)->explode('-');
                $cont =  false;
                $datakey="";
                if(Str::contains($params,"!")){
                    $fkey = Str::of($params)->explode('-')[0];
                    $ekl = Str::of(Str::of($fkey)->remove("!"))->explode("|");
                    $datakc = $ekl[0];
                    if(Str::contains($xml,'<'.$datakc.'>','</'.$datakc.'>')){
                        $datakey = $ekl[1];
                        $cont = true;
                        $data['success']=true;
                    }else{
                        $data['success']=false;
                        $data[$ekl[1]]=null;
                    }
                }

               
                foreach ($parampipes as $param) {
                    if(Str::contains($param,"@")){
                        
                        $bkl = Str::of($param)->explode('|');
                        
                        $boolparamkey = Str::of($bkl[0])->remove("@");
                        $boolparamval = Str::of(Str::between($xml,'<'.$boolparamkey.'>','</'.$boolparamkey.'>'))->trim();
                        
                        if(in_array($boolparamval,['true','True','1'])){
                            $cont = true;
                            $data['success'] = true; 
                            $datakey = $bkl[1];
                        }else{
                            $data['success']=false;
                        }
                    }else{
                        if($cont){
                            if(Str::contains($param,"!")){
                                continue;
                            }
                            $paramkl = Str::of($param)->explode('|');
                            $pkey = $paramkl[0];
                            $plabel = $paramkl[1];
                            if(Str::contains($xml,'<'.$pkey.'>') && Str::contains($xml,'</'.$pkey.'>')){
                              $datafields = Str::of(Str::between($xml,'<'.$datakey.'>','</'.$datakey.'>'))->trim();
                              $data[$datakey][$plabel] = Str::of(Str::between($datafields,'<'.$pkey.'>','</'.$pkey.'>'))->trim();
                            }else{
                              $data[$datakey][$plabel] = null;
                            }
                        }
                    }
                }

            }else{
                $keypipe = Str::of($params)->explode('|');
                $key = $keypipe[0];
                $label = $keypipe[1];
                $data[$label] = Str::of(Str::between($xml, '<'.$key.'>','</'.$key.'>'))->trim();
            }
        }else{
            $keypipe = Str::of($params)->explode('#');
            $keylabel = Str::of($keypipe[0])->explode('|');
            $key = $keylabel[0];
            $label = $keylabel[1];
            $recordkey = $keypipe[1];
            $list = [];
            $optlist = [];
            $sbstrc = 0;
            if(Str::contains($xml,'<'.$key.'>') && Str::contains($xml,'</'.$key.'>')){
                
                $fields = Str::of($keypipe[2])->explode('&');
                $result = Str::between($xml, '<'.$key.'>','</'.$key.'>');
                $records = Str::of($result)->explode('</'.$recordkey.'>');
                $sbstrc = count($records) - 1;
                for ($i=0;$i<$sbstrc;$i++) {
                    $recordarray = array();
                    foreach ($fields as $field) {
                        $fieldpipe = Str::of($field)->explode('|');
                        $fieldkey = $fieldpipe[0];
                        $fieldlabel = $fieldpipe[1];
                        if(Str::contains($records[$i],'<'.$fieldkey.'>') &&Str::contains($records[$i],'</'.$fieldkey.'>')){
                            $recordarray[$fieldlabel]=Str::of(Str::between($records[$i], '<'.$fieldkey.'>','</'.$fieldkey.'>'))->trim();
                        }else{
                            $recordarray[$fieldlabel]=null;
                        }
                    }
                    if($options){
                      if(Str::contains($xml,'<SearchRiderListV5Response') && Str::contains($xml,'<Table')){
                        array_push($optlist, '<option value="'.$recordarray['riderid'].'">'.$recordarray['firstx0020name'].' '.$recordarray['familyx0020name'].' ('.$recordarray['stable'].') '.$recordarray['nfx0020license'].' / '.$recordarray['feix0020reg'].' / '.$recordarray['nationalityshort'].'</option>');
                      }else{
                        array_push($optlist, '<option value="'.$recordarray['horseid'].'">'.$recordarray['name'].' / '.$recordarray['nfregistration'].' / '.$recordarray['gender'].' / '.$recordarray['color'].'</option>');
                      }
                    }else{
                      array_push($list,$recordarray);
                    }
                }
            }
            
            if(count($optlist)>0){
              // $data[$label]['options'] = $optlist;
              $data['options'] = collect($optlist)->implode('');
            }else{
              $data[$label]['count'] = $sbstrc;
              $data[$label]['data'] = $list;
            }
        }
        if(Str::contains($xml,'<msg>') && Str::contains($xml,'</msg>')){
          $msgtag = Str::of(Str::between($xml,'<msg>','</msg>'))->trim();
          $msgs= Str::of($msgtag)->explode('</string>');
          $sbstrc = count($msgs) - 1;
          $msgarray = array();
          for ($i=0;$i<$sbstrc;$i++) {
              array_push($msgarray,Str::of(Str::of($msgs[$i])->remove('<string>'))->trim());
          }
          if(count($msgarray) > 0){
            $data['msgs'] = $msgarray;
          }else{
            $data['msgs']=null;
          }
        }
       
        return $data;
    }
}
