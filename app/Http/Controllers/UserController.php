<?php

namespace App\Http\Controllers;

use App\CreditLog;
use App\Http\Resources\CreditLogResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class UserController extends Controller
{
    public function getCreditLogs()
    {
        $user = Config::get('auth_user');
        return CreditLogResource::collection(CreditLog::where('from', $user->id)->orWhere('to', $user->id)->get());
    }

    public function getCredit()
    {
        $user = Config::get('auth_user');
        return $user->credit ?? 0;
    }

    public function updateProfile($field, Request $request)
    {
        $user = Config::get('auth_user');

        if (!in_array($field, ['name', 'password']))
            return response('Field doesn\'t exist', 404);

        if ($field === 'name') {
            if (!isset($request->name))
                return response('Name field is required', 400);
            $user->name = $request->name;
            $user->save();
        }

        if ($field === 'password') {
            if (!isset($request->new_password))
                return response('Password field is required', 400);
            $user->password = bcrypt($request->new_password);
            $user->save();
        }
        return response('User updated successfully', 200);
    }
}
