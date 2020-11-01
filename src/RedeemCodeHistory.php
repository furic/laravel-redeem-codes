<?php

namespace Furic\RedeemCodes;

use Illuminate\Database\Eloquent\Model;

class RedeemCodeHistory extends Model
{

    protected $fillable = ['redeem_code_id', 'ip', 'agent'];

    public function redeemCode()
    {
        return $this->belongsTo('Furic\RedeemCodes\RedeemCode');
    }

}
