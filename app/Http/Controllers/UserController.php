<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User as UserModel;
use App\Exceptions\FieldException;
use App\Services\ServiceProvider;
use Hash;

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
        $httpClient = new \GuzzleHttp\Client();
        $api_url = 'https://ebe.eiev-app.ae/api/uaeerf/userlogin';
        $options = [
            'headers' => [
                "38948f839e704e8dbd4ea2650378a388" => "0b5e7030aa4a4ee3b1ccdd4341ca3867"
            ],
            'form_params' => [
                "username" => $request->username,
                "password" => $request->password
            ]
        ];
        
        $response = $httpClient->request('POST', $api_url, $options);
        $loginSuccess = json_decode($response->getBody());

        if (!$loginSuccess->success) {
            throw new FieldException(json_encode([
                'username' => 'Invalid username and password.',
                'password' => 'Invalid username and password.',
            ]));
        }

        $checkIfExist = UserModel::where('email', $request->username)->first();
        if(!$checkIfExist) {
            $uDetail = $loginSuccess->uprofile;
            $newUser = array(
                'email' => $request->username,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'firstname' => $uDetail->fname,
                'lastname' => $uDetail->lname,
                'dob' => $uDetail->bday,
                'phone' => $uDetail->mobileno,
                'location' => 'Abu Dhabi',
                'role' => 3,
                'status' => 'A',
                'disciplines' => 'E',
                'emirates_id' => '123456789'
            );

            $checkIfExist = UserModel::create($newUser);
            
        }
        try {
            Auth::login($checkIfExist,$request->has('remember'));
        } catch (\Throwable $th) {
            info(str($th));
        }


        // login service
        $user = ServiceProvider::userAuth($request->except('_token'))
              ->login();

        // $user = array(
        //     "user_id" => 1,
        //     "email" => "jp7Q4W11qG@gmail.com",
        //     "username" => "superadmin",
        //     "password" => '$2y$10$IJ4hZcW5eyyANLEZZs0OweRtBhc3hnD4yxhmFZ2Pf/j5yVCmlXIva',
        //     "firstname" => "DpRkFyQHIf",
        //     "lastname" => "epZHg9q2vd",
        //     "active" => 1,
        //     "emirates_id" => "XXX12340",
        //     "dob" => "2022-10-04 06:16:52",
        //     "status" => "A",
        //     "discipline" => "E",
        //     "documents" => null,
        //     "elve_id" => null,
        //     "eef_id" => null,
        //     "fei_id" => null,
        //     "stable_name" => null,
        //     "role" => 1,
        //     "created_at" => null,
        //     "updated_at" => null,
        //     "phone" => "",
        //     "location" => "AUH"
        // );
        
        // $role = array(
        //     "role_id" => 1,
        //     "role" => "superadmin",
        //     "active" => 1,
        //     "access" => '{"*": "*"}',
        //     "created_at" => null,
        //     "updated_at" => null,
        //     "home_url" => "/race"
        // );

        // set session for login
        // session()->put('user', $user);
        // session()->put('role', $role);

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
