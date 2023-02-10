<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User as UserModel;
use App\Models\Userprofile as Userprofile;
use App\Exceptions\FieldException;
use App\Services\ServiceProvider;
use Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Str;

class UserController extends Controller
{
    protected $modelName = 'user';
    
    /**
     * Triggers GET /login
     *
     * @return view()
     */
    public function loginForm() {
        return view('login');
    }

    /**
     * Triggers POST /login
     *
     * @param Illuminate\Http\Request $request
     */
    public function login (Request $request) {
        // EEF API CALL

        
        $validator = Validator::make($request->all(), [
            'username'=>'required',
            'password'=>'required',
        ]);
        if ($validator->fails()) {
            throw new FieldException(json_encode([
                'username' => 'Invalid username and password.',
                'password' => 'Invalid username and password.',
            ]));
        }

        $username = Str::lower($request->username);
        $password = $request->password;
        if($username != "eiev@eiev.ae"){

            $httpClient = new \GuzzleHttp\Client();
            $api_url = 'https://ebe.eiev-app.ae/api/uaeerf/userlogin';
            // $api_url = 'http:/192.168.1.161:8000/api/uaeerf/userlogin';
            $options = [
                'headers' => [
                    "38948f839e704e8dbd4ea2650378a388" => "0b5e7030aa4a4ee3b1ccdd4341ca3867"
                ],
                'form_params' => [
                    "username" => $username,
                    "password" => $password
                ]
            ];
            
            $response = $httpClient->request('POST', $api_url, $options);
            $loginSuccess = json_decode($response->getBody());
            info($request->all());
            info(strval($loginSuccess->success));
            if (!$loginSuccess->success) {
                throw new FieldException(json_encode([
                    'username' => 'Invalid username and password.',
                    'password' => 'Invalid username and password.',
                ]));
            }
    
            $checkIfExist = UserModel::where('email', $username)->first();
            if(!$checkIfExist) {
                $uDetail = $loginSuccess->uprofile;
                $newUser = array(
                    'email' => $username,
                    'username' => $username,
                    'password' => Hash::make($password),
                    'firstname' => $uDetail->fname,
                    'lastname' => $uDetail->lname,
                    'dob' => $uDetail->bday,
                    'phone' => $uDetail->mobileno,
                    'location' => 'Abu Dhabi',
                    'role' => 3,
                    'status' => 'A',
                    'disciplines' => 'E',
                    'emirates_id' => '123456789',
                    'pwd' => $password,
                );
                $checkIfExist = UserModel::create($newUser);
            } else {
                $uDetail = $loginSuccess->uprofile;
                $newUser = array(
                    'email' => $username,
                    'username' => $username,
                    'password' => Hash::make($password),
                    'firstname' => $uDetail->fname,
                    'lastname' => $uDetail->lname,
                    'dob' => $uDetail->bday,
                    'phone' => $uDetail->mobileno,
                    'pwd' => $password,
                );
    
                $checkIfExist = $checkIfExist->update($newUser);
            }
            try {
                Auth::login($checkIfExist,$request->has('remember'));
            } catch (\Throwable $th) {
    
            }
    
            // login service
            $user = ServiceProvider::userAuth($request->except('_token'))
                  ->login();
    
            $url = '/dashboard';
            if (isset(session()->get('role')->home_url)) {
                $url = session()->get('role')->home_url;
            }

            $dbprofile = Userprofile::where('userid',$loginSuccess->uprofile->userid)->first();            
            if($dbprofile){
                $loginSuccess->uprofile->uniqueid = $dbprofile->uniqueid;
            }

            session()->put('profile', $loginSuccess->uprofile);

            return redirect($url);
        }else{
            $user = ServiceProvider::userAuth($request->except('_token'))
                  ->login();
    
            $url = '/dashboard';
            if (isset(session()->get('role')->home_url)) {
                $url = session()->get('role')->home_url;
            }
    
            session()->put('profile', (object) array(
                "latestupdate"=>"2022-11-14T19:56:01",
                "isactive"=>"true",
                "email"=>"oecryk@gmail.com",
                "userid"=>"6671",
                "fname"=>"Amer",
                "lname"=>"Ali Amer",
                "mobileno"=>"00971529550336",
                "bday"=>"1998-10-21T00:00:00",
                "stableid"=>"E0000014",
                "stablename"=>"Private Individual Stables"
            ));
            return redirect($url);
        }
        // TODO: redirect to dashboard
    }

    /**
     * Triggers GET /register
     *
     * @return view()
     */
    public function registerForm() {
        $tpl_vars = [];
        // get available locations
        // and discipline
        $tpl_vars['locations'] = UserModel::AVAILABLE_LOCATIONS;
        $tpl_vars['disciplines'] = UserModel::AVAILABLE_DISCIPLINES;
        
        return view('register', $tpl_vars);
    }

    /**
     * Triggers POST /register
     *
     * @param Illuminate\Http\Request $request
     */
    public function register(Request $request) {
        // TODO: Documents upload;

        // register service
        $user = ServiceProvider::userAuth($request->except('_token'))
              ->register($request->input('c_pass'));

        // It passed here, so this must be success
        $this->flashMsg('Registration complete', 'success');
        // then redirect to login
        return redirect('/login');
    }

    public function me() {
        $uprofile = session()->get('profile');
        $modelName = 'profile';
        if (empty($uprofile)) {
            $this->flashMsg('Forbidden Action', 'warning');
            // then redirect to login
            return redirect('/dashboard');
        }

        return view('pages.userprofile', [
            'profile' => $uprofile,
            'modelName' => $modelName
        ]);
    }

    public function downloadQRCode(Request $request)
    {
        $profile = session()->get('profile');

        $headers    = array('Content-Type' => ['png','svg','eps']);
        $contents = file_get_contents("https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=".$profile->uniqueid."&choe=UTF-8");

        $filename = 'qrEntry.png';
        \Storage::disk('public')->put($filename, $contents);

        $qrFile = \Storage::path('public/'.$filename);

        return response()->download($qrFile)->deleteFileAfterSend(true);
    }
}
