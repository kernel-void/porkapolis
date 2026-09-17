<?php

namespace App\Http\Requests\Role;

use App\Http\Requests\BaseRequest;

class UpdateRoleRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'permissions'   => 'array',
            'permissions.*' => 'exists:permissions,name',
        ];
    }
}
