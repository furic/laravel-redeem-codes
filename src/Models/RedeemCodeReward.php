<?php

namespace Furic\RedeemCodes\Models;

use Illuminate\Database\Eloquent\Model;

class RedeemCodeReward extends Model
{

    protected $fillable = ['redeem_code_id', 'type', 'amount', 'item_id'];
    protected $visible = ['type', 'amount', 'item_id'];

    public function redeemCode()
    {
        return $this->belongsTo('Furic\RedeemCodes\Models\RedeemCode');
    }

    public function event()
    {
        return $this->belongsTo('Furic\RedeemCodes\Models\Event');
    }
    
}
