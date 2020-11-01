<?php

namespace Furic\RedeemCodes;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    protected $fillable = ['type', 'name', 'started_at', 'ended_at'];

    public function redeemCodes()
    {
        return $this->hasMany('Furic\RedeemCodes\RedeemCode');
    }

    public function redeemCodeRewards()
    {
        return $this->hasMany('Furic\RedeemCodes\RedeemCodeReward');
    }

}
