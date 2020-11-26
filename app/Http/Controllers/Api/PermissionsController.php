<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\PermissionResource;

class PermissionsController extends Controller
{
    // 获取权限列表
    public function index(Request $request)
    {
        $permissions = $request->user()->getAllPermissions();

        PermissionResource::wrap('data');
        return PermissionResource::collection($permissions);
    }
}
