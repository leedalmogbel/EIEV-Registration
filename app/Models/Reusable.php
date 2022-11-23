<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Reusable extends Model
{
    public static function generateReusableUnique($data,$prefix='',$suffix='',$encrypt=false,$type)
    {
        $funique = $data;
        if($encrypt){
            switch ($type) {
                case 'ouuid':
                    $ouuids = explode('-',Reusable::generateOrderuuid());
                    $ouuids = array_slice($ouuids, -2, 2);
                    $funique = implode('-',$ouuids);
                    break;
                case 'mdf':
                    $funique = Reusable::generateMd5($funique);
                    break;
            }
        }
        if ($prefix != ''){
            $funique = $prefix."-".$funique;
        }
        if ($suffix != ''){
            $funique .=  "-".$suffix;
        }
        return $funique;
    }
    public static function generateMd5($data)
    {
        return md5($data);
    }
    public static function verifyMd5($enc,$real)
    {
        return $enc == md5($real);
    }
    public static function generateOrderuuid()
    {
        return (string) Str::orderedUuid();;
    }

    public static function soapCall($action,$data,$fieldlist,$result,$showraw=false)
    {
      if(isset($data)){
        $arrkeys= array_keys($data);
        $validationResult=$this->validateData($fieldlist,$arrkeys);
        if(!$validationResult['allow']){
          return response()->json(['error'=>$validationResult['msg']],400);
        }
      }
      $xml='<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Body>
          <'.$action.' xmlns="http://tempuri.org/">';
          if(isset($data)){
            $keys = array_keys($data);
            foreach ($keys as $key) {
                $xml.='<'.$key.'>'.$data[$key].'</'.$key.'>';
            }
          }
          $xml.='<msg></msg>
          </'.$action.'>
          </soap:Body>
        </soap:Envelope>';

        $options = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=utf-8',
                "SOAPAction"=>"http://tempuri.org/".$action,
                "User-Agent" => "EIEV/1.0",
                "Accept"=>"*/*",
                "Host"=>env('SOAP_HOST')
            ],
            'body' => $xml
        ];
        $client = new Client();
        $response = $client->post(env("SOAP_BASE_URL"), $options);
        if($showraw){
            return $response->getBody();
        }
        return $this->extractData((string)$response->getBody(),'getStableListResult|stables#Stables#LastestUpdate|lastestupdate&Stable_ID|stableid&Name|name&Address|address&Zip|zip&City|city&Country|country&Phone|phone&Email|email&Remarks|remarks&Owner|owner&Discipline|discipline&Category|category&DIVISION|division');
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
