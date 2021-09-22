<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function welcome()
    {
        return view('welcome');
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            if ((!is_array($request->customers) || (count($request->customers)) < 1) && $user->first_time)
                return response('you should put customers at your first login', 403);
            else if ($user->first_time && isset($request->customers)) {
                $user->first_time = false;
                $user->customers = $request->customers;
                $user->save();
            }

            return response()->json([
                'data' => new UserResource($user)
            ], 200);
        } else {
            return response('Unauthorised.', 401);
        }
    }

    public function logout()
    {
        if ($user = Auth::guard('api')->user()) {
            $user->api_token = Str::random(60);
            $user->save();
            return response('logged out successfully');
        } else
            return response('problem with authentication', 500);
    }
}
