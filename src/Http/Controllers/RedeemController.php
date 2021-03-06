<?php

namespace Furic\RedeemCodes\Http\Controllers;

use Furic\RedeemCodes\Models\RedeemCode;
use Furic\RedeemCodes\Models\RedeemCodeHistory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

// The controller for redeem code api calls.
class RedeemController extends Controller
{

    /**
     * Check validation and redeem a given redeem code.
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function redeem($code)
    {
        $validator = Validator::make(['code' => $code], ['code' => 'exists:redeem_codes,code']);
        if ($validator->fails()) {
            return response(['error' => $validator->errors()->first()], 400); // "The selected code is invalid."
        }

        $redeemCode = RedeemCode::findByCode($code);

        if ($redeemCode->redeemed !== false) {
            return response(['error' => 'The selected code has already been redeemed.'], 400);
        }

        // Check event valid date
        $event = $redeemCode->event;
        if ($event != null) {
            if ($event->start_at != null) {
                $validator = Validator::make($event->toArray(), ['start_at' => 'before:tomorrow']);
                if ($validator->fails()) {
                    return response(['error' => 'The selected code cannot be used yet.'], 400);
                }
            }
            if ($event->end_at != null) {
                $validator = Validator::make($event->toArray(), ['end_at' => 'after:yesterday']);
                if ($validator->fails()) {
                    return response(['error' => 'The selected code has expired.'], 400);
                }
            }
        }

        if ($redeemCode->reusable === false) {
            $redeemCode->setRedeemed();
        }

        // Add a redeem code history
        $data = array();
        $data['redeem_code_id'] = $redeemCode->id;
        $data['ip'] = filter_input(INPUT_SERVER, "REMOTE_ADDR");
        $data['agent'] = filter_input(INPUT_SERVER, "HTTP_USER_AGENT");
        RedeemCodeHistory::create($data);

        return response($redeemCode, 200);
    }

}