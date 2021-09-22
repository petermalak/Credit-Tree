<?php

namespace App\Http\Controllers;

use App\CreditLog;
use App\Http\Resources\ChildResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TreeController extends Controller
{
    public function addToTree(Request $request)
    {
        $user = Config::get('auth_user');

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        if (User::where('email', $request->email)->first())
            return response('email already exists', 400);

        if ($user->isAdmin() || $user->credit >= 1500) {
            if ($user->childes()->get()->count() < 5) {
                if (!$user->isAdmin()) {
                    $user->credit -= 1500;
                    $user->save();
                    CreditLog::create([
                        'type' => 'SystemWithdraw',
                        'from' => $user->id,
                        'to' => 1,
                        'amount' => '-1500'
                    ]);
                }

                $child = new User();
                $child->name = $request->name;
                $child->email = $request->email;
                $child->password = bcrypt($request->password);
                $child->code = Str::random(7);
                $child->first_time = true;
                $child->api_token = Str::random(60);
                $child->parent_id = $user->id;
                $child->save();

                $child->calculateProfits($child->id);

                return response('User added successfully', 200);
            } else return response('You have already 5 childs', 403);
        } else return response('You don\'t have enough credit', 403);
    }

    public function getChildes($user_id)
    {
        if (!$user = User::find($user_id))
            return response('User not found', 404);

        return response()->json(ChildResource::collection($user->childes), 200);
    }

    public function transferCredit(Request $request)
    {
        $user = Config::get('auth_user');

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'amount' => 'required|integer',
        ]);

        if ($validator->fails())
            return response()->json($validator->errors(), 400);
        if (!$receiver = User::where('email', $request->email)->first())
            return response('Email not found', 404);
        if (($user->credit < $request->amount) && !$user->isAdmin())
            return response('You don\'nt have enough credit to transfer', 400);

        $user->credit -= $request->amount;
        $user->save();
        $receiver->credit += $request->amount;
        $receiver->save();

        CreditLog::create([
            'type' => 'CreditTransfer',
            'from' => $user->id,
            'to' => $receiver->id,
            'amount' => (string)$request->amount
        ]);

        return response('Transfer completed successfully');
    }
}
