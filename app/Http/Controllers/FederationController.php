<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
class FederationController extends Controller
{
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
                    // if(Str::contains($options,":")){
                    //   $idval = Str::of($options)->explode(":");
                      
                    //   $id = $idval[0];
                      
                    //   $vals = Str::of($idval[1])->explode(",");
                    //   $count = count($vals)-1;
                    //   $optionval = '<option value="'.$recordarray[$id].'">';
                    //   for ($i=0;$i<$count; $i++) {
                    //     if($i == $count){
                    //       $optionval .= $recordarray[$vals[$i]];
                    //     }else{
                    //       if(Str::contains($xml,'<SearchHorseListResponse') && Str::contains($xml,'<Table')){
                    //         $optionval .= $recordarray[$vals[$i]]. " / ";
                    //       }else{
                    //         if($vals[$i] == "stable"){
                    //           $optionval .="(".$recordarray[$vals[$i]].") ";
                    //         }else{
                    //           if((Str::contains($vals[$i],"family") && Str::contains($vals[$i],"name")) || (Str::contains($vals[$i],"first")&&Str::contains($vals[$i],"name"))){
                    //             $optionval .= $recordarray[$vals[$i]]." ";
                    //           }else{
                    //             $optionval .= $recordarray[$vals[$i]]. " / ";
                    //           }
                    //         }
                    //       }
                    //     }
                    //   }
                    //   $optionval .= '</option>';
                      
                    //   array_push($optlist,$optionval);
                      
                    // }else{
                    //   array_push($list,$recordarray);
                    // }
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
    
    public function execute(Request $request)
    {
      $debug= false;
        $xml = '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Header>
          <SecuredToken xmlns="http://ws.uaeerf.ae/">
            <username>'.env("WS_UNAME").'</username>
            <password>'.env("WS_PWORD").'</password>
            <AuthenticationToken>'.$this->wslogin()['token'].'</AuthenticationToken>
          </SecuredToken>
        </soap:Header>
        <soap:Body>';
        if(Str::contains($request->action,',')){
          $actions = Str::of($request->action)->explode(',');
          foreach ($actions as $action) {
            if($action == "WSLogin"){
              $xml.='<'.$action.' xmlns="http://ws.uaeerf.ae/"/>';
            }else{
              $xml.='<'.$action.' xmlns="http://ws.uaeerf.ae/">';
              if(isset($request->actionparams[$action])){
                $keys = array_keys($request->actionparams[$action]);
                foreach ($keys as $key) {
                    $xml.='<'.$key.'>'.$request->actionparams[$action][$key].'</'.$key.'>';
                }
              }
              $xml.='</'.$action.'>';
            }
          }
          
        }else{
          if($request->action == "WSLogin"){
            $xml.='<'.$request->action.' xmlns="http://ws.uaeerf.ae/"/>';
          }else{
            $xml.='<'.$request->action.' xmlns="http://ws.uaeerf.ae/">';
            if(isset($request->params)){
              $keys = array_keys($request->params);
              foreach ($keys as $key) {
                  $xml.='<'.$key.'>'.$request->params[$key].'</'.$key.'>';
              }
            }
            if($request->action == "UpdateEntry"){
              $xml.='<msg></msg>';
            }
            $xml.='</'.$request->action.'>';
          }
        }
        if($request->action == "UpdateEntry"){
          $xml .='</soap:Body>
       </soap:Envelope>';
        }else{
          $xml.='<msg></msg>
          </soap:Body>
       </soap:Envelope>';
        }
        
        $options = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=utf-8',
                "SOAPAction"=>"http://ws.uaeerf.ae/".$request->action,
                "User-Agent" => "EIEV/1.0",
                "Accept"=>"*/*",
                "Host"=>"ws.uaeerf.ae"
            ],
            'body' => $xml
        ];
        $client = new Client();
        $response = $client->post(env("UAEERF_BASE_URL"), $options);
        if(isset($request->showraw)){
          return $response->getBody();
        }
        switch ($request->action) {
          case 'AddEntry':
            return $this->extractData((string)$response->getBody(),'AddEntryResult|entrycode',$debug);
            break;
          case 'GetEIEVEventList':
            return $this->extractData((string)$response->getBody(),'GetEIEVEventListResult|events#Events#StatusID|statusid&StatusName|statusname&TypeID|typeid&TypeName|typename&DivisionID|divisionid&DivisionName|divisionname&RaceCity|racecity&RaceCountry|racecountry&SeasonID|seasonid&SeasonName|seasonname&RaceID|raceid&RaceName|racename&RaceLocation|racelocation&RaceClub|raceclub&RaceFromDate|racefromdate&RaceTOdate|racetodate',$debug);
            break;
          case 'GetEntries':
            return $this->extractData((string)$response->getBody(),'GetEntriesResult|entries#Entries#HorseName|horsename&HorseNFID|horsenfid&HorseFEIID|horsefeiid&RiderName|ridername&RiderNFID|ridernfid&RiderFEIID|riderfeiid&TrainerName|trainername&TrainerNFID|trainernfid&TrainerFEIID|trainerfeiid&OwnerName|ownername&StableName|stablename&EventCode|eventcode&ClassCode|classcode&Code|code&UserId|userid&RiderID|riderid&RiderStableID|riderstableid&HorseID|horseid&StableID|stableid&TrainerID|trainerid&OwnerID|ownerid&isFetched|isfetched&Status|status&Remarks|remarks&dateSubmit|datesubmit&IsLate|islate&IsPreRideLate|ispreridelate&FEIRemarks|feiremarks&IsFEIValid|isfeivalid&SeasonCode|seasoncode&Fee|fee&FeeStatus|feestatus&Reference|reference&AcceptTerms|acceptterms&Review|review&PayType|paytype&Posted|posted&Chargeable|chargeable&ParentID|parentid&WithdrawDate|withdrawdate&SubstiDate|substidate&DocsUploaded|docsuploaded',$debug);
            break;
          case 'IsHorseEligibleChecking':
              return $this->extractData((string)$response->getBody(),'IsHorseEligibleCheckingResult|horseeligibility'); 
              break;
          case 'IsRiderEligibleChecking':
            return $this->extractData((string)$response->getBody(),'IsRiderEligibleCheckingResult|ridereligibility');
            break;
          case 'GetUserProfile':
            if(!isset($request->params['SearchEmail'])){
              return $this->extractData((string)$response->getBody(),'GetUserProfileResult|profiles#UserProfile#LastestUpdate|latestupdate&IsActive|isactive&Email|email&UserId|userid&Fname|fname&Lname|lname&MobileNo|mobileno&Dob|bday&Stable_ID|stableid',$debug); 
            }
            return $this->extractData((string)$response->getBody(),'!GetUserProfileResult|uprofile-LastestUpdate|latestupdate-IsActive|isactive-Email|email-UserId|userid-Fname|fname-Lname|lname-MobileNo|mobileno-Dob|bday-Stable_ID|stableid',$debug);
            break;
          case 'SearchHorseListV5': 
            return $this->extractData((string)$response->getBody(),'NewDataSet|horses#Table#NFPassportNumber|nfpassportnumber&Active|active&HorseID|horseid&NF_Registration|nfregistration&Name|name&Breed|breed&Country_Origin|countryorigin&Country_Origin_SHORT|countryoriginshort&DOB|dob&Gender|gender&Colour|color&Trainer|trainer&OWNER|owner&Stable|stable&FEIPassport|feipassport&MicrochipNo|microchip&DIVISION|division&StableID|stableid&DivisionID|divisionid&AdminUser|adminuser&breedid|breedid&colourid|colourid&genderid|genderid&CountryOfOriginID|countryoforiginid&trainerid|trainerid&ownerid|ownerid',$debug);
            break;
          case 'SearchOwnerListV5':
            return $this->extractData((string)$response->getBody(),'NewDataSet|owners#Table#ownerID|ownerid&adminUser|adminuser&NF_x0020_LICENSE|nfx0020license&First_x0020_Name|firstx0020name&Family_x0020_Name|familyx0020name&Gender|gender&NATIONALITY|nationality&NATIONALITY_Short|nationalityshort&DOB|dob&STABLE|stable&FEI_x0020_REG|feix0020reg&TELEPHONE|telephone&MOBILE|mobile&EMAIL|email&DIVISION|division&RegisteredSeasonCode|registeredseasoncode&Registered_x0020_Season|registeredx0020season&Active|active&RIDERID|riderid&StableID|stableid&DivisionID|divisionid&NationalityID|nationalityid&Address|address&POBox|pobox&City|city&Country|country&Country_short|countryshort&HomeAddress|homeaddress&HomeCity|homecity&HomeCountry|homecountry&HomeCountry_short|homecountryshort',$debug); 
            break;
          case 'SearchRiderListV5':
            return $this->extractData((string)$response->getBody(),'NewDataSet|riders#Table#adminUser|adminuser&NF_x0020_LICENSE|nfx0020license&First_x0020_Name|firstx0020name&Family_x0020_Name|familyx0020name&Gender|gender&NATIONALITY|nationality&NATIONALITY_Short|nationalityshort&DOB|dob&STABLE|stable&FEI_x0020_REG|feix0020reg&TELEPHONE|telephone&MOBILE|mobile&EMAIL|email&DIVISION|division&RegisteredSeasonCode|registeredseasoncode&Registered_x0020_Season|registeredx0020season&Active|active&RIDERID|riderid&StableID|stableid&DivisionID|divisionid&NationalityID|nationalityid&Address|address&POBox|pobox&City|city&Country|country&Country_short|countryshort&HomeAddress|homeaddress&HomeCity|homecity&HomeCountry|homecountry&HomeCountry_short|homecountryshort',$debug);
            break;
          case 'SearchTrainerListV5':
            return $this->extractData((string)$response->getBody(),'@UserLoginResult|uprofile-LastestUpdate|latestupdate-IsActive|isactive-Email|email-UserId|userid-Fname|fname-Lname|lname-MobileNo|mobileno-Dob|bday-Stable_ID|stableid-Stable_Name|stablename',$debug);
            break;
          case 'UpdateEntry':
            return $this->extractData((string)$response->getBody(),'UpdateEntryResult|updated',$debug); 
            break;
          case 'UserLogin':
            return $this->extractData((string)$response->getBody(),'@UserLoginResult|uprofile-LastestUpdate|latestupdate-IsActive|isactive-Email|email-UserId|userid-Fname|fname-Lname|lname-MobileNo|mobileno-Dob|bday-Stable_ID|stableid-Stable_Name|stablename',$debug);
            break;
          case 'WSLogin':
            return $this->extractData((string)$response->getBody(),'WSLoginResult|token',$debug); 
            break;
          case 'getStableList':
            return $this->extractData((string)$response->getBody(),'getStableListResult|stables#Stables#LastestUpdate|lastestupdate&Stable_ID|stableid&Name|name&Address|address&Zip|zip&City|city&Country|country&Phone|phone&Email|email&Remarks|remarks&Owner|owner&Discipline|discipline&Category|category&DIVISION|division',$debug); 
            break;
        }    
    }

    public function addentry(Request $request)
    {
      $debug= false;
      $validator = Validator::make($request->all(),[
        'params.EventID'=>'required',
        'params.HorseID'=>'required',
        'params.RiderID'=>'required',
        'params.UserID'=>'required',
      ]);
      if($validator->fails()){
        return response()->json(["error" => $validator->errors()]);
      }
        $xml='<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Header>
          <SecuredToken xmlns="http://ws.uaeerf.ae/">
            <username>?</username>
            <password>?</password>
            <AuthenticationToken>'.$this->wslogin()['token'].'</AuthenticationToken>
          </SecuredToken>
        </soap:Header>
        <soap:Body>
        <AddEntry xmlns="http://ws.uaeerf.ae/">';
          if(isset($request->params)){
            $keys = array_keys($request->params);
            foreach ($keys as $key) {
                $xml.='<'.$key.'>'.$request->params[$key].'</'.$key.'>';
            }
          }
          $xml.='<msg></msg>
          </AddEntry>
          </soap:Body>
        </soap:Envelope>';
        
        $options = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=utf-8',
                "SOAPAction"=>"http://ws.uaeerf.ae/AddEntry",
                "User-Agent" => "EIEV/1.0",
                "Accept"=>"*/*",
                "Host"=>"ws.uaeerf.ae"
            ],
            'body' => $xml
        ];
       
        $client = new Client();
        $response = $client->post(env("UAEERF_BASE_URL"), $options);
        if(isset($request->showraw)){
            return $response->getBody();
        }
        return $this->extractData((string)$response->getBody(),'AddEntryResult|entrycode',$debug);
    }

    public function geteieveventlist(Request $request)
    {
      $debug= false;
      $fieldlist = ["SearchSeasonCode","SearchEventCode"];
      if(isset($request->params)){
        $arrkeys= array_keys($request->params);
        $validationResult=$this->validateData($fieldlist,$arrkeys);
        if(!$validationResult['allow']){
          return response()->json(['error'=>$validationResult['msg']],400);
        }
      }
      $xml = '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
      <soap:Header>
        <SecuredToken xmlns="http://ws.uaeerf.ae/">
          <username>?</username>
          <password>?</password>
          <AuthenticationToken>'.$this->wslogin()['token'].'</AuthenticationToken>
        </SecuredToken>
      </soap:Header>
      <soap:Body>
        <GetEIEVEventList xmlns="http://ws.uaeerf.ae/">';
        if(isset($request->params)){
          $keys = array_keys($request->params);
          foreach ($keys as $key) {
              $xml.='<'.$key.'>'.$request->params[$key].'</'.$key.'>';
          }
        }
       $xml.= '<msg></msg>
       </GetEIEVEventList>
      </soap:Body>
      </soap:Envelope>';
      $options = [
          'headers' => [
              'Content-Type' => 'text/xml; charset=utf-8',
              "SOAPAction"=>"http://ws.uaeerf.ae/GetEIEVEventList",
              "User-Agent" => "EIEV/1.0",
              "Accept"=>"*/*",
              "Host"=>"ws.uaeerf.ae"
          ],
          'body' => $xml
      ];
      $client = new Client();
      $response = $client->post(env("UAEERF_BASE_URL"), $options);
      if(isset($request->showraw)){
          return $response->getBody();
      }
      return $this->extractData((string)$response->getBody(),'GetEIEVEventListResult|events#Events#OpeningDate|openingdate&ClosingDate|closingdate&StatusID|statusid&StatusName|statusname&TypeID|typeid&TypeName|typename&DivisionID|divisionid&DivisionName|divisionname&RaceCity|racecity&RaceCountry|racecountry&SeasonID|seasonid&SeasonName|seasonname&RaceID|raceid&RaceName|racename&RaceLocation|racelocation&RaceClub|raceclub&RaceFromDate|racefromdate&RaceTOdate|racetodate',$debug);
    }

    public function getentries(Request $request,$entryid=null)
    {
      $debug= false;
      $fieldlist = ["SearchEntryID","SearchEventID","SearchHorseID","SearchRiderID","SearchUserID","SearchStableID"];
      if(isset($request->params)){
        $arrkeys= array_keys($request->params);
        $validationResult=$this->validateData($fieldlist,$arrkeys);
        if(!$validationResult['allow']){
          return response()->json(['error'=>$validationResult['msg']],400);
        }
      }
        $xml='<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Header>
          <SecuredToken xmlns="http://ws.uaeerf.ae/">
            <username></username>
            <password></password>
            <AuthenticationToken>'.$this->wslogin()['token'].'</AuthenticationToken>
          </SecuredToken>
        </soap:Header>
        <soap:Body>
          <GetEntries xmlns="http://ws.uaeerf.ae/">';
          if($entryid!= null && $entryid!="null"){
            $xml.='<SearchEntryID>'.$entryid.'</SearchEntryID>';
          }
          if(isset($request->params)){
            $keys = array_keys($request->params);
            foreach ($keys as $key) {
                $xml.='<'.$key.'>'.$request->params[$key].'</'.$key.'>';
            }
          }
          $xml.='<msg></msg>
          </GetEntries>
        </soap:Body>
      </soap:Envelope>';
        $options = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=utf-8',
                "SOAPAction"=>"http://ws.uaeerf.ae/GetEntries",
                "User-Agent" => "EIEV/1.0",
                "Accept"=>"*/*",
                "Host"=>"ws.uaeerf.ae"
            ],
            'body' => $xml
        ];
        $client = new Client();
        $response = $client->post(env("UAEERF_BASE_URL"), $options);
        if(isset($request->showraw)){
            return $response->getBody();
        }
        return $this->extractData((string)$response->getBody(),'GetEntriesResult|entries#Entries#HorseName|horsename&HorseNFID|horsenfid&HorseFEIID|horsefeiid&RiderName|ridername&RiderNFID|ridernfid&RiderFEIID|riderfeiid&TrainerName|trainername&TrainerNFID|trainernfid&TrainerFEIID|trainerfeiid&OwnerName|ownername&StableName|stablename&EventCode|eventcode&ClassCode|classcode&Code|code&UserId|userid&RiderID|riderid&RiderStableID|riderstableid&HorseID|horseid&StableID|stableid&TrainerID|trainerid&OwnerID|ownerid&isFetched|isfetched&Status|status&Remarks|remarks&dateSubmit|datesubmit&IsLate|islate&IsPreRideLate|ispreridelate&FEIRemarks|feiremarks&IsFEIValid|isfeivalid&SeasonCode|seasoncode&Fee|fee&FeeStatus|feestatus&Reference|reference&AcceptTerms|acceptterms&Review|review&PayType|paytype&Posted|posted&Chargeable|chargeable&ParentID|parentid&WithdrawDate|withdrawdate&SubstiDate|substidate&DocsUploaded|docsuploaded',$debug);
    }

    public function getuserprofile(Request $request)
    {
      $debug= false;
      $fieldlist = ["SearchEmail"];
      if(isset($request->params)){
        $arrkeys= array_keys($request->params);
        $validationResult=$this->validateData($fieldlist,$arrkeys);
        if(!$validationResult['allow']){
          return response()->json(['error'=>$validationResult['msg']],400);
        }
      }
        $xml='<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Header>
          <SecuredToken xmlns="http://ws.uaeerf.ae/">
            <username>?</username>
            <password>?</password>
            <AuthenticationToken>'.$this->wslogin()['token'].'</AuthenticationToken>
          </SecuredToken>
        </soap:Header>
        <soap:Body>
          <GetUserProfile xmlns="http://ws.uaeerf.ae/">';
          if(isset($request->params)){
            $keys = array_keys($request->params);
            foreach ($keys as $key) {
                $xml.='<'.$key.'>'.$request->params[$key].'</'.$key.'>';
            }
          }
          $xml.='<msg></msg>
          </GetUserProfile>
        </soap:Body>
      </soap:Envelope>';

        $options = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=utf-8',
                "SOAPAction"=>"http://ws.uaeerf.ae/GetUserProfile",
                "User-Agent" => "EIEV/1.0",
                "Accept"=>"*/*",
                "Host"=>"ws.uaeerf.ae"
            ],
            'body' => $xml
        ];
        $client = new Client();
        $response = $client->post(env("UAEERF_BASE_URL"), $options);
        if(isset($request->showraw)){
            return $response->getBody();
        }
        if(!isset($request->params['SearchEmail'])){
          return $this->extractData((string)$response->getBody(),'GetUserProfileResult|profiles#UserProfile#LastestUpdate|latestupdate&IsActive|isactive&Email|email&UserId|userid&Fname|fname&Lname|lname&MobileNo|mobileno&Dob|bday&Stable_ID|stableid',$debug); 
        }
        return $this->extractData((string)$response->getBody(),'!GetUserProfileResult|uprofile-LastestUpdate|latestupdate-IsActive|isactive-Email|email-UserId|userid-Fname|fname-Lname|lname-MobileNo|mobileno-Dob|bday-Stable_ID|stableid',$debug);
    }

    public function moveentrytomain(Request $request)
    {
      $debug= false;
      $validator = Validator::make($request->all(),[
        'params.SearchEntryID'=>'required',
        'params.EventID'=>'required',
      ]);
      if($validator->fails()){
        return response()->json(["error" => $validator->errors()]);
      }
      $fieldlist = ["SearchEntryID","EventID"];
      if(isset($request->params)){
        $arrkeys= array_keys($request->params);
        $validationResult=$this->validateData($fieldlist,$arrkeys);
        if(!$validationResult['allow']){
          return response()->json(['error'=>$validationResult['msg']],400);
        }
      }
        $xml='<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Header>
          <SecuredToken xmlns="http://ws.uaeerf.ae/">
            <username>?</username>
            <password>?</password>
            <AuthenticationToken>'.$this->wslogin()['token'].'</AuthenticationToken>
          </SecuredToken>
        </soap:Header>
        <soap:Body>
          <MoveEntryToMain xmlns="http://ws.uaeerf.ae/">';
          if(isset($request->params)){
            $keys = array_keys($request->params);
            foreach ($keys as $key) {
                $xml.='<'.$key.'>'.$request->params[$key].'</'.$key.'>';
            }
          }
          $xml.='<msg></msg>
          </MoveEntryToMain>
        </soap:Body>
      </soap:Envelope>';

        $options = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=utf-8',
                "SOAPAction"=>"http://ws.uaeerf.ae/MoveEntryToMain",
                "User-Agent" => "EIEV/1.0",
                "Accept"=>"*/*",
                "Host"=>"ws.uaeerf.ae"
            ],
            'body' => $xml
        ];
        $client = new Client();
        $response = $client->post(env("UAEERF_BASE_URL"), $options);
        if(isset($request->showraw)){
            return $response->getBody();
        }
        return $this->extractData((string)$response->getBody(),'MoveEntryToMainResult|moved',$debug);
    }

    public function searchhorselist(Request $request,$horseid=null)
    {
      $debug= false;
      $fieldlist = ["options","HorseID","AdminUserID","StableID","SearchName","SearchFEIID","SearchEEFID","SearchOwner","SearchTrainer","SearchStable","SearchNFPassport","SearchMicrochip","SearchDiscipline","SearchDisciplineID",];
      if(isset($request->params)){
        $arrkeys= array_keys($request->params);
        $validationResult=$this->validateData($fieldlist,$arrkeys);
        if(!$validationResult['allow']){
          return response()->json(['error'=>$validationResult['msg']],400);
        }
      }
      $xml='<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Header>
          <SecuredToken xmlns="http://ws.uaeerf.ae/">
            <username>?</username>
            <password>?</password>
            <AuthenticationToken>'.$this->wslogin()['token'].'</AuthenticationToken>
          </SecuredToken>
        </soap:Header>
        <soap:Body>
          <SearchHorseListV5 xmlns="http://ws.uaeerf.ae/">';
          if($horseid!=null && $horseid!='null'){
            $xml.='<HorseID>'.$horseid.'</HorseID>';
          }
          if(isset($request->params)){
            $keys = array_keys($request->params);
            foreach ($keys as $key) {
                $xml.='<'.$key.'>'.$request->params[$key].'</'.$key.'>';
            }
          }
          $xml.='<msg></msg>
          </SearchHorseListV5>
          </soap:Body>
        </soap:Envelope>';

        $options = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=utf-8',
                "SOAPAction"=>"http://ws.uaeerf.ae/SearchHorseListV5",
                "User-Agent" => "EIEV/1.0",
                "Accept"=>"*/*",
                "Host"=>"ws.uaeerf.ae"
            ],
            'body' => $xml
        ];
        $client = new Client();
        $response = $client->post(env("UAEERF_BASE_URL"), $options);
        if(isset($request->showraw)){
            return $response->getBody();
        }
        return $this->extractData((string)$response->getBody(),'NewDataSet|horses#Table#NFPassportNumber|nfpassportnumber&Active|active&HorseID|horseid&NF_Registration|nfregistration&Name|name&Breed|breed&Country_Origin|countryorigin&Country_Origin_SHORT|countryoriginshort&DOB|dob&Gender|gender&Colour|color&Trainer|trainer&OWNER|owner&Stable|stable&FEIPassport|feipassport&MicrochipNo|microchip&DIVISION|division&StableID|stableid&DivisionID|divisionid&AdminUser|adminuser&breedid|breedid&colourid|colourid&genderid|genderid&CountryOfOriginID|countryoforiginid&trainerid|trainerid&ownerid|ownerid&Registered_x0020_Season|registeredx0020season',$debug,isset($request->params['options']));
    }

    public function searchownerlist(Request $request)
    {
      $debug= false;
      $fieldlist = ["OwnerID","AdminUserID","StableID","SearchFirstName","SearchLastName","SearchFEIID","SearchEEFID","SearchStable","SearchNationality","SearchGender","SearchDiscipline","SearchDisciplineID",];
      if(isset($request->params)){
        $arrkeys= array_keys($request->params);
        $validationResult=$this->validateData($fieldlist,$arrkeys);
        if(!$validationResult['allow']){
          return response()->json(['error'=>$validationResult['msg']],400);
        }
      }
      $xml='<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Header>
          <SecuredToken xmlns="http://ws.uaeerf.ae/">
            <username>?</username>
            <password>?</password>
            <AuthenticationToken>'.$this->wslogin()['token'].'</AuthenticationToken>
          </SecuredToken>
        </soap:Header>
        <soap:Body>
          <SearchOwnerListV5 xmlns="http://ws.uaeerf.ae/">';
          if(isset($request->params)){
            $keys = array_keys($request->params);
            foreach ($keys as $key) {
                $xml.='<'.$key.'>'.$request->params[$key].'</'.$key.'>';
            }
          }
          $xml.='<msg></msg>
          </SearchOwnerListV5>
          </soap:Body>
        </soap:Envelope>';

        $options = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=utf-8',
                "SOAPAction"=>"http://ws.uaeerf.ae/SearchOwnerListV5",
                "User-Agent" => "EIEV/1.0",
                "Accept"=>"*/*",
                "Host"=>"ws.uaeerf.ae"
            ],
            'body' => $xml
        ];
        $client = new Client();
        $response = $client->post(env("UAEERF_BASE_URL"), $options);
        if(isset($request->showraw)){
            return $response->getBody();
        }
        return $this->extractData((string)$response->getBody(),'NewDataSet|owners#Table#ownerID|ownerid&adminUser|adminuser&NF_x0020_LICENSE|nfx0020license&First_x0020_Name|firstx0020name&Family_x0020_Name|familyx0020name&Gender|gender&NATIONALITY|nationality&NATIONALITY_Short|nationalityshort&DOB|dob&STABLE|stable&FEI_x0020_REG|feix0020reg&TELEPHONE|telephone&MOBILE|mobile&EMAIL|email&DIVISION|division&RegisteredSeasonCode|registeredseasoncode&Registered_x0020_Season|registeredx0020season&Active|active&RIDERID|riderid&StableID|stableid&DivisionID|divisionid&NationalityID|nationalityid&Address|address&POBox|pobox&City|city&Country|country&Country_short|countryshort&HomeAddress|homeaddress&HomeCity|homecity&HomeCountry|homecountry&HomeCountry_short|homecountryshort',$debug); 
    }

    public function searchriderlist(Request $request,$riderid=null)
    {
      $debug= false;
      $fieldlist = ["options","RiderID","AdminUserID","StableID","SearchFirstName","SearchLastName","SearchFEIID","SearchEEFID","SearchStable","SearchNationality","SearchGender","SearchDiscipline","SearchDisciplineID",];
      
      if(isset($request->params)){
        $arrkeys= array_keys($request->params);
        $validationResult=$this->validateData($fieldlist,$arrkeys);
        if(!$validationResult['allow']){
          return response()->json(['error'=>$validationResult['msg']],400);
        }
      }
        $xml='<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Header>
          <SecuredToken xmlns="http://ws.uaeerf.ae/">
            <username>?</username>
            <password>?</password>
            <AuthenticationToken>'.$this->wslogin()['token'].'</AuthenticationToken>
          </SecuredToken>
        </soap:Header>
        <soap:Body>
          <SearchRiderListV5 xmlns="http://ws.uaeerf.ae/">';
          if($riderid!=null && $riderid!="null"){
            $xml.='<RiderID>'.$riderid.'</RiderID>';
          }
          if(isset($request->params)){
            $keys = array_keys($request->params);
            foreach ($keys as $key) {
              if($key !="options"){
                $xml.='<'.$key.'>'.$request->params[$key].'</'.$key.'>';
              }
            }
          }
          $xml.='<msg></msg>
          </SearchRiderListV5>
          </soap:Body>
        </soap:Envelope>';
        $options = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=utf-8',
                "SOAPAction"=>"http://ws.uaeerf.ae/SearchRiderListV5",
                "User-Agent" => "EIEV/1.0",
                'Accept' => '*/*',
                "Host"=>"ws.uaeerf.ae"
            ],
            'body' => $xml
        ];
        $client = new Client();
        $response = $client->post(env("UAEERF_BASE_URL"), $options);
        if(isset($request->showraw)){ 
          return $response->getBody();
        }
        return $this->extractData((string)$response->getBody(),'NewDataSet|riders#Table#adminUser|adminuser&NF_x0020_LICENSE|nfx0020license&First_x0020_Name|firstx0020name&Family_x0020_Name|familyx0020name&Gender|gender&NATIONALITY|nationality&NATIONALITY_Short|nationalityshort&DOB|dob&STABLE|stable&FEI_x0020_REG|feix0020reg&TELEPHONE|telephone&MOBILE|mobile&EMAIL|email&DIVISION|division&RegisteredSeasonCode|registeredseasoncode&Registered_x0020_Season|registeredx0020season&Active|active&RIDERID|riderid&StableID|stableid&DivisionID|divisionid&NationalityID|nationalityid&Address|address&POBox|pobox&City|city&Country|country&Country_short|countryshort&HomeAddress|homeaddress&HomeCity|homecity&HomeCountry|homecountry&HomeCountry_short|homecountryshort',$debug,isset($request->params['options']));
    }

    public function searchtrainerlist(Request $request)
    {
      $debug= false;
      $fieldlist = ["TrainerID","AdminUserID","StableID","SearchFirstName","SearchLastName","SearchFEIID","SearchEEFID","SearchStable","SearchNationality","SearchGender","SearchDiscipline","SearchDisciplineID",];
      if(isset($request->params)){
        $arrkeys= array_keys($request->params);
        $validationResult=$this->validateData($fieldlist,$arrkeys);
        if(!$validationResult['allow']){
          return response()->json(['error'=>$validationResult['msg']],400);
        }
      }
        $xml='<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Header>
          <SecuredToken xmlns="http://ws.uaeerf.ae/">
            <username>?</username>
            <password>?</password>
            <AuthenticationToken>'.$this->wslogin()['token'].'</AuthenticationToken>
          </SecuredToken>
        </soap:Header>
        <soap:Body>
          <SearchTrainerListV5 xmlns="http://ws.uaeerf.ae/">';
          if(isset($request->params)){
            $keys = array_keys($request->params);
            foreach ($keys as $key) {
                $xml.='<'.$key.'>'.$request->params[$key].'</'.$key.'>';
            }
          }
         $xml.='<msg></msg>
         </SearchTrainerListV5>
        </soap:Body>
      </soap:Envelope>';

        $options = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=utf-8',
                "SOAPAction"=>"http://ws.uaeerf.ae/SearchTrainerListV5",
                "User-Agent" => "EIEV/1.0",
                "Accept"=>"*/*",
                "Host"=>"ws.uaeerf.ae"
            ],
            'body' => $xml
        ];
        $client = new Client();
        $response = $client->post(env("UAEERF_BASE_URL"), $options);
        if(isset($request->showraw)){
          return $response->getBody();
        }
        return $this->extractData((string)$response->getBody(),'NewDataSet|trainers#Table#Photograph|photograph&NF_x0020_LICENSE|nfx0020license&First_x0020_Name|firstx0020name&Family_x0020_Name|familyx0020name&Gender|gender&NATIONALITY|nationality&NATIONALITY_short|nationalityshort&DOB|dob&STABLE|stable&FEI_x0020_REG|feix0020reg&TELEPHONE|telephone&MOBILE|mobile&EMAIL|email&DIVISION|division&Registered_x0020_Season|registeredx0020season&Active|active&TRAINERID|trainerid&StableID|stableid&DivisionID|divisionid&adminUser|adminuser&NationalityID|nationalityid&Address|address&POBox|pobox&City|city&Country|country&Country_short|countryshort&HomeAddress|homeaddress&HomeCity|homecity&HomeCountry|homecountry&HomeCountry_short|homecountryshort&Weight|weight',$debug);
    }
    public function updateentry(Request $request)
    {
      $debug= false;
      $validator = Validator::make($request->all(),[
        'params.EventID'=>'required',
        'params.SearchEntryID'=>'required',
        'params.Entrystatus'=>'required|in:withdrawn,accepted,rejected',
        'params.Remarks'=>'required',
      ]);
      if($validator->fails()){
        return response()->json(["error" => $validator->errors()]);
      }
        $xml='<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Header>
          <SecuredToken xmlns="http://ws.uaeerf.ae/">
            <username>?</username>
            <password>?</password>
            <AuthenticationToken>'.$this->wslogin()['token'].'</AuthenticationToken>
          </SecuredToken>
        </soap:Header>
        <soap:Body>
        <UpdateEntryStatus xmlns="http://ws.uaeerf.ae/">';
          if(isset($request->params)){
            if(!is_array($request->params)){
              return response()->json(['error'=>'Unexpected value.'], 400);
            }
            $keys = array_keys($request->params);
            foreach ($keys as $key) {
                $xml.='<'.$key.'>'.$request->params[$key].'</'.$key.'>';
            }
          }
          $xml.='<msg></msg>
          </UpdateEntryStatus>
          </soap:Body>
        </soap:Envelope>';
        
        $options = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=utf-8',
                "SOAPAction"=>"http://ws.uaeerf.ae/UpdateEntryStatus",
                "User-Agent" => "EIEV/1.0",
                "Accept"=>"*/*",
                "Host"=>"ws.uaeerf.ae"
            ],
            'body' => $xml
        ];
       
        $client = new Client();
        $response = $client->post(env("UAEERF_BASE_URL"), $options);
        if(isset($request->showraw)){
            return $response->getBody();
        }
        return $this->extractData((string)$response->getBody(),'UpdateEntryStatusResult|updateresult',$debug);
    }

    public function userlogin(Request $request)
    {
      $debug= false;
        $validator  = Validator::make($request->all(),[
            'username'=>'required',
            'password'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()]);
        }
        $xml='<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Header>
          <SecuredToken xmlns="http://ws.uaeerf.ae/">
            <username>?</username>
            <password>?</password>
            <AuthenticationToken>'.$this->wslogin()['token'].'</AuthenticationToken>
          </SecuredToken>
        </soap:Header>
        <soap:Body>
          <UserLogin xmlns="http://ws.uaeerf.ae/">
            <email>'.$request->username.'</email>
            <password>'.$request->password.'</password>
            <msg>
            </msg>
          </UserLogin>
        </soap:Body>
      </soap:Envelope>';

        $options = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=utf-8',
                "SOAPAction"=>"http://ws.uaeerf.ae/UserLogin",
                "User-Agent" => "EIEV/1.0",
                "Accept"=>"*/*",
                "Host"=>"ws.uaeerf.ae"
            ],
            'body' => $xml
        ];
        $client = new Client();
        $response = $client->post(env("UAEERF_BASE_URL"), $options);
        if(isset($request->showraw)){
            return $response->getBody();
        }
        return $this->extractData((string)$response->getBody(),'@UserLoginResult|uprofile-LastestUpdate|latestupdate-IsActive|isactive-Email|email-UserId|userid-Fname|fname-Lname|lname-MobileNo|mobileno-Dob|bday-Stable_ID|stableid-Stable_Name|stablename',$debug);
    }

    public function wslogin()
    {
      $debug= false;
        $xml = '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Header>
          <SecuredToken xmlns="http://ws.uaeerf.ae/">
            <username>'.env("WS_UNAME").'</username>
            <password>'.env("WS_PWORD").'</password>
            <AuthenticationToken>?</AuthenticationToken>
          </SecuredToken>
        </soap:Header>
        <soap:Body>
          <WSLogin xmlns="http://ws.uaeerf.ae/" />
        </soap:Body>
      </soap:Envelope>';
        $options = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=utf-8',
                "SOAPAction"=>"http://ws.uaeerf.ae/WSLogin",
                "User-Agent" => "EIEV/1.0",
                "Accept"=>"*/*",
                "Host"=>"ws.uaeerf.ae"
            ],
            'body' => $xml
        ];
        $client = new Client();
        $response = $client->post(env("UAEERF_BASE_URL"), $options);
        return $this->extractData((string)$response->getBody(),'WSLoginResult|token',$debug);
    }

    public function getstablelist(Request $request)
    {
      $debug= false;
      $fieldlist = ["StableID"];
      if(isset($request->params)){
        $arrkeys= array_keys($request->params);
        $validationResult=$this->validateData($fieldlist,$arrkeys);
        if(!$validationResult['allow']){
          return response()->json(['error'=>$validationResult['msg']],400);
        }
      }
      $xml='<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Header>
          <SecuredToken xmlns="http://ws.uaeerf.ae/">
            <username>?</username>
            <password>?</password>
            <AuthenticationToken>'.$this->wslogin()['token'].'</AuthenticationToken>
          </SecuredToken>
        </soap:Header>
        <soap:Body>
          <getStableList xmlns="http://ws.uaeerf.ae/">';
          if(isset($request->params)){
            $keys = array_keys($request->params);
            foreach ($keys as $key) {
                $xml.='<'.$key.'>'.$request->params[$key].'</'.$key.'>';
            }
          }
          $xml.='<msg></msg>
          </getStableList>
          </soap:Body>
        </soap:Envelope>';

        $options = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=utf-8',
                "SOAPAction"=>"http://ws.uaeerf.ae/getStableList",
                "User-Agent" => "EIEV/1.0",
                "Accept"=>"*/*",
                "Host"=>"ws.uaeerf.ae"
            ],
            'body' => $xml
        ];
        $client = new Client();
        $response = $client->post(env("UAEERF_BASE_URL"), $options);
        if(isset($request->showraw)){
            return $response->getBody();
        }
        return $this->extractData((string)$response->getBody(),'getStableListResult|stables#Stables#LastestUpdate|lastestupdate&Stable_ID|stableid&Name|name&Address|address&Zip|zip&City|city&Country|country&Phone|phone&Email|email&Remarks|remarks&Owner|owner&Discipline|discipline&Category|category&DIVISION|division',$debug); 
    }
}
