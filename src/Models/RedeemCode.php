<?php

namespace Furic\RedeemCodes\Models;

use Illuminate\Database\Eloquent\Model;

class RedeemCode extends Model
{

    protected $fillable = ['event_id', 'code', 'redeemed', 'reusable'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $appends = ['rewards'];

    public static function findByCode($code)
    {
        return SELF::where('code', $code)->first();
    }

    public function event()
    {
        return $this->belongsTo('Furic\RedeemCodes\Models\Event');
    }
    
    public function rewards()
    {
        if ($this->event != null) {
            return $this->event->hasMany('Furic\RedeemCodes\Models\RedeemCodeReward');
        } else {
            return $this->hasMany('Furic\RedeemCodes\Models\RedeemCodeReward');
        }
    }
    
    public function getRewardsAttribute()
    {
        return $this->rewards()->get();
    }

    public function setRedeemed()
    {
        $this->redeemed = true;
        $this->save();
    }

}
