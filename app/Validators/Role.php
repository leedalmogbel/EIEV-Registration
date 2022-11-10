<?php

namespace App\Validators;

use App\Models\Role as RoleModel;
use App\Exceptions\FieldException;
use App\Exceptions\MsgException;

class Role {
    
    public static function updateForm($roleId) {
        // check if role exist
        $role = RoleModel::where('role_id', $roleId)->first();

        // throw MsgException
        if (empty($role)) {
            throw new MsgException('Cannot find role', '/role');
        }

        return $role;
    }
}
