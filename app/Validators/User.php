<?php

namespace App\Validators;

use App\Models\User as UserModel;
use App\Exceptions\FieldException;
use App\Exceptions\MsgException;

use Hash;

class User {
    
    // LOGIN ERROR MESSAGES
    const ERR_EMPTY_EMAIL = 'Username / Email cannot be empty.';
    const ERR_EMPTY_PASS = 'Password cannot be empty.';
    const ERR_INVALID_CREDS = 'Invalid username and password.';
    const ERR_NO_ROLE = 'You don\'t have any permission yet.';
    const ERR_PENDING_STATUS = 'Your account is not yet approved.';
    const ERR_REJECTED_STATUS = 'Your account has been rejected.';
    const ERR_SUSPENDED_STATUS = 'Your account has been suspended.';
    
    public static function login(UserModel $user) {
        $err = [];
        if (!$user->username) {
            $err['username'] = self::ERR_EMPTY_EMAIL;
        }

        if (!$user->password) {
            $err['password'] = self::ERR_EMPTY_PASS;
        }

        if (!empty($err)) {
            throw new FieldException(json_encode($err));
        }

        $userModel = UserModel::where('username', $user->username)
              ->first();

        if (empty($userModel)) {
            throw new FieldException(json_encode([
                'username' => self::ERR_INVALID_CREDS
            ]));
        }

        if (!Hash::check($user->password, $userModel->password)) {
            throw new FieldException(json_encode([
                'username' => self::ERR_INVALID_CREDS
            ]));
        }

        $errStatMap = [
            'P' => 'PENDING',
            'S' => 'SUSPENDED',
            'R' => 'REJECTED'];
        
        if (isset($errStatMap[$userModel->status])) {
            $varName = sprintf('ERR_%s_STATUS', $errStatMap[$userModel->status]);
            throw new FieldException(json_encode([
                'username' => constant('self::'. $varName)
            ]));
        }
        
        $role = $userModel->role()->first();
        if (!$role) {
            throw new FieldException(json_encode([
                'username' => self::ERR_NO_ROLE
            ]));
        }
        
        if (!empty($err)) {
            throw new FieldException(json_encode($err));
        }

        return [$userModel, $role];
    }
    
    public static function register(UserModel $user, $extra = []) {
        $errors = [];

        // required fields
        $required = [
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'email' => 'Email Address',
            'username' => 'Username',
            'password' => 'Password',
            'phone' => 'Phone/Mobile No.',
            'emirates_id' => 'Emirates ID',
            'dob' => 'Date of Birth'
        ];

        // validate required fields
        foreach ($required as $field => $label) {
            if (!$user->$field || !trim($user->$field)) {
                $errors[$field] = "$label is required";
            }
        }

        if (!isset($extra['c_pass']) || $extra['c_pass'] != $user->password) {
            $errors['c_pass'] = 'Password did not match';
        }

        if (!empty($errors)) {
            throw new FieldException(json_encode($errors));
        }

        // unique values
        $uniques = [
            'username' => 'Username',
            'email' => 'Email Address',
            'phone' => 'Phone/Mobile No.'
        ];

        // validate unique values
        foreach ($uniques as $field => $label) {
            $exist = UserModel::where($field, $user->$field)->first();
            if (!empty($exist)) {
                $errors[$field] = "$label already exist";
            }
        }

        if (!empty($errors)) {
            throw new FieldException(json_encode($errors));
        }

        return true;
    }
    
    public function getById($userId) {
        $user = UserModel::find($userId);
        if (empty($user)) {
            throw new MsgException('Cannot find user', '/user');
        }

        return $user;
    }
}
