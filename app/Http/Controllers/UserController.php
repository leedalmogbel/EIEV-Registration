<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User as UserModel;
use App\Exceptions\FieldException;
use App\Services\ServiceProvider;

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
        // EEF API call
        $httpClient = new \GuzzleHttp\Client();
        $api_url = 'https://ebe.eiev-app.ae/api/uaeerf/userlogin';
        $options = [
            'headers' => [
                "38948f839e704e8dbd4ea2650378a388" => "0b5e7030aa4a4ee3b1ccdd4341ca3867"
            ],
            'form_params' => [
                "username" => "admin@alwathbastables.com",
                "password" => "eiev123456"
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
