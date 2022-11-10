<?php

namespace App\Services\User;

use App\Models\User;
use App\Validators\User as UserValidator;
use Hash;

class Auth extends User {
    
    protected $table = 'users'; // need to manualy declare table
    
    /**
     * logs in a user
     *
     * @return bool
     */
    public function login() {
        // validate login data
        list($user, $role) = UserValidator::login($this);

        // set session for login
        session()->put('user', $user);
        session()->put('role', $role);

        return true;
        
    }

    /**
     * creates new user data
     *
     * @param String confirm password
     * @param Array document data
     */
    public function register($c_pass, array $document = []) {
        // validate register user data
        UserValidator::register($this, ['c_pass' => $c_pass]);

        // password should be hash
        $this->password = Hash::make($this->password);
        // documents
        $this->documents = json_encode($document);

        // save new user data
        $this->save();
        
        return $this;
    }
}
