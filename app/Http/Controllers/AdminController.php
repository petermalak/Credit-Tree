<?php

namespace App\Http\Controllers;

use App\CreditLog;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class AdminController extends Controller
{
    public function addCredit(Request $request, $user_id)
    {
        if (!$user = User::where('id', $user_id)->first())
            return response('User not found', 404);
        if (!isset($request->amount))
            return response('amount is empty', 400);

        $user->credit += (int)$request->amount;
        $user->save();

        CreditLog::create([
            'type' => 'AdminAddCredit',
            'from' => Config::get('auth_user')->id,
            'to' => $user_id,
            'amount' => '+' . $request->amount
        ]);

        return response('Credit added successfully');
    }

    public function removeCredit(Request $request, $user_id)
    {
        if (!$user = User::where('id', $user_id)->first())
            return response('User not found', 404);
        if (!isset($request->amount))
            return response('amount is empty', 400);

        $user->credit -= (int)$request->amount;
        $user->save();

        CreditLog::create([
            'type' => 'AdminRemoveCredit',
            'from' => Config::get('auth_user')->id,
            'to' => $user_id,
            'amount' => '-' . $request->amount
        ]);

        return response('Credit removed successfully');
    }

    public function getAllUsers()
    {
        $users = User::all();
        return response()->json(UserResource::collection($users), 200);
    }
}
