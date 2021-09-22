<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditLog extends Model
{
    protected $fillable = ['type', 'from', 'to', 'amount'];

    public function sender()
    {
        return $this->hasOne(User::class, 'id', 'from');
    }

    public function receiver()
    {
        return $this->hasOne(User::class, 'id', 'to');
    }
}
