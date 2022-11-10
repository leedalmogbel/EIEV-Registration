<?php

namespace App\Services;

use App\Models\Role as RoleModel;
use App\Validators\Role as RoleValidator;

use App\Exceptions\MsgException;

class Role extends RoleModel {
    const PAGINATION_ENTRY = 15;
    
    public function listing () {
        $roles = $this->paginate(self::PAGINATION_ENTRY);
        
        return $roles;
    }

    public function updateForm($roleId) {
        return RoleValidator::updateForm($roleId);
    }
}
