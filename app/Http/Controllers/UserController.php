<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User as UserModel;
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

        session()->put('profile', $loginSuccess->uprofile);
        // TODO: redirect to dashboard
        return redirect($url);
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
}
