<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_admin', 'credit', 'parent_id', 'first_time'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'customers' => 'array',
    ];

    public function childes()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function isAdmin()
    {
        if ($this->is_admin)
            return true;
        else return false;
    }

    public function calculateProfits($child_id)
    {
        return $this->profitRec($this->parent, $child_id, 5);
    }

    private function profitRec(User $user, $newUser_id, $number)
    {
        if ($number === 1 || !$user->parent) {
            $user->credit += 100;
            $user->save();

            CreditLog::create([
                'type' => 'UserProfit',
                'from' => $newUser_id,
                'to' => $user->id,
                'amount' => '+100'
            ]);
            return 'done';
        } else {
            $user->credit += 100;
            $user->save();

            CreditLog::create([
                'type' => 'UserProfit',
                'from' => $newUser_id,
                'to' => $user->id,
                'amount' => '+100'
            ]);

            return $this->profitRec($user->parent, $newUser_id, $number - 1);
        }
    }
}
