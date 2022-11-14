<?php

namespace App\Http\Controllers;

use App\Models\Psetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Crypt;
class FederationSyncController extends Controller
{
    public function syncentries(Request $request)
    {
        ob_end_clean();
        ignore_user_abort();
        ob_start();
        header("Connection: close");
        header("Content-Length: " . ob_get_length());
        ob_end_flush();
        flush();
        $ip = $request->ip();
        $host_agent = Str::slug(Str::of($request->server('HTTP_ORIGIN').'|'.$request->server('HTTP_HOST') .'|'.$request->server('HTTP_USER_AGENT'))->trim(),'|');
        $settings = Psetting::where('ipaddress',$ip)->where('host',$host_agent)->first();
        if($settings){
            if($settings->allowed){
                if(!$settings->processing_entries){
                    Artisan::call('command:syncentries --ip='.$ip.' --host='.$host_agent);
                }
            }
        }else{
            $data = array();
            $data['ipaddress'] = $ip;
            $data['host']=$host_agent;
            Psetting::create($data);
        }
    }
    public function syncevents(Request $request)
    {
        ob_end_clean();
        ignore_user_abort();
        ob_start();
        header("Connection: close");
        header("Content-Length: " . ob_get_length());
        ob_end_flush();
        flush();
        $ip = $request->ip();
        $host_agent = Str::slug(Str::of($request->server('HTTP_ORIGIN').'|'.$request->server('HTTP_HOST') .'|'.$request->server('HTTP_USER_AGENT'))->trim(),'|');
        $settings = Psetting::where('ipaddress',$ip)->where('host',$host_agent)->first();
        if($settings){
            if($settings->allowed){
                if(!$settings->processing_events){
                    Artisan::call('command:syncevents --ip='.$ip.' --host='.$host_agent);
                }
            }
        }else{
            $data = array();
            $data['ipaddress'] = $ip;
            $data['host']=$host_agent;
            Psetting::create($data);
        }
    }
    public function synchorses(Request $request)
    {
        ob_end_clean();
        ignore_user_abort();
        ob_start();
        header("Connection: close");
        header("Content-Length: " . ob_get_length());
        ob_end_flush();
        flush();
        $ip = $request->ip();
        $host_agent = Str::slug(Str::of($request->server('HTTP_ORIGIN').'|'.$request->server('HTTP_HOST') .'|'.$request->server('HTTP_USER_AGENT'))->trim(),'|');
        $settings = Psetting::where('ipaddress',$ip)->where('host',$host_agent)->first();
        if($settings){
            if($settings->allowed){
                if(!$settings->processing_horses){
                    Artisan::call('command:synchorses --ip='.$ip.' --host='.$host_agent);
                }
            }
        }else{
            $data = array();
            $data['ipaddress'] = $ip;
            $data['host']=$host_agent;
            Psetting::create($data);
        }
    }
    public function syncowners(Request $request)
    {
        ob_end_clean();
        ignore_user_abort();
        ob_start();
        header("Connection: close");
        header("Content-Length: " . ob_get_length());
        ob_end_flush();
        flush();
        $ip = $request->ip();
        $host_agent = Str::slug(Str::of($request->server('HTTP_ORIGIN').'|'.$request->server('HTTP_HOST') .'|'.$request->server('HTTP_USER_AGENT'))->trim(),'|');
        $settings = Psetting::where('ipaddress',$ip)->where('host',$host_agent)->first();
        if($settings){
            if($settings->allowed){
                if(!$settings->processing_owners){
                    Artisan::call('command:syncowners --ip='.$ip.' --host='.$host_agent);
                }
            }
        }else{
            $data = array();
            $data['ipaddress'] = $ip;
            $data['host']=$host_agent;
            Psetting::create($data);
        }
    }
    public function syncprofiles(Request $request)
    {
        ob_end_clean();
        ignore_user_abort();
        ob_start();
        header("Connection: close");
        header("Content-Length: " . ob_get_length());
        ob_end_flush();
        flush();
        $ip = $request->ip();
        $host_agent = Str::slug(Str::of($request->server('HTTP_ORIGIN').'|'.$request->server('HTTP_HOST') .'|'.$request->server('HTTP_USER_AGENT'))->trim(),'|');
        $settings = Psetting::where('ipaddress',$ip)->where('host',$host_agent)->first();
        if($settings){
            if($settings->allowed){
                if(!$settings->processing_profiles){
                    Artisan::call('command:syncprofiles --ip='.$ip.' --host='.$host_agent);
                }
            }
        }else{
            $data = array();
            $data['ipaddress'] = $ip;
            $data['host']=$host_agent;
            Psetting::create($data);
        }
    }
    public function syncriders(Request $request)
    {
        ob_end_clean();
        ignore_user_abort();
        ob_start();
        header("Connection: close");
        header("Content-Length: " . ob_get_length());
        ob_end_flush();
        flush();
        $ip = $request->ip();
        $host_agent = Str::slug(Str::of($request->server('HTTP_ORIGIN').'|'.$request->server('HTTP_HOST') .'|'.$request->server('HTTP_USER_AGENT'))->trim(),'|');
        $settings = Psetting::where('ipaddress',$ip)->where('host',$host_agent)->first();
        if($settings){
            if($settings->allowed){
                if(!$settings->processing_riders){
                    Artisan::call('command:syncriders --ip='.$ip.' --host='.$host_agent);
                }
            }
        }else{
            $data = array();
            $data['ipaddress'] = $ip;
            $data['host']=$host_agent;
            Psetting::create($data);
        }
    }
    public function syncstables(Request $request)
    {
        ob_end_clean();
        ignore_user_abort();
        ob_start();
        header("Connection: close");
        header("Content-Length: " . ob_get_length());
        ob_end_flush();
        flush();
        $ip = $request->ip();
        $host_agent = Str::slug(Str::of($request->server('HTTP_ORIGIN').'|'.$request->server('HTTP_HOST') .'|'.$request->server('HTTP_USER_AGENT'))->trim(),'|');
        $settings = Psetting::where('ipaddress',$ip)->where('host',$host_agent)->first();
        if($settings){
            if($settings->allowed){
                if(!$settings->processing_stables){
                    Artisan::call('command:syncstables --ip='.$ip.' --host='.$host_agent);
                }
            }
        }else{
            $data = array();
            $data['ipaddress'] = $ip;
            $data['host']=$host_agent;
            Psetting::create($data);
        }
    }
    public function synctrainers(Request $request)
    {
        ob_end_clean();
        ignore_user_abort();
        ob_start();
        header("Connection: close");
        header("Content-Length: " . ob_get_length());
        ob_end_flush();
        flush();
        $ip = $request->ip();
        $host_agent = Str::slug(Str::of($request->server('HTTP_ORIGIN').'|'.$request->server('HTTP_HOST') .'|'.$request->server('HTTP_USER_AGENT'))->trim(),'|');
        $settings = Psetting::where('ipaddress',$ip)->where('host',$host_agent)->first();
        if($settings){
            if($settings->allowed){
                if(!$settings->processing_trainers){
                    Artisan::call('command:synctrainers --ip='.$ip.' --host='.$host_agent);
                }
            }
        }else{
            $data = array();
            $data['ipaddress'] = $ip;
            $data['host']=$host_agent;
            Psetting::create($data);
        }
    }
}
