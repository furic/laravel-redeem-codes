<?php

namespace Furic\RedeemCodes;

use App\RedeemCode;
use App\RedeemCodeHistory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

// The controller for redeem code api calls.
class RedeemController extends Controller
{

    public function redeem($code)
    {
        $validator = Validator::make(['code' => $code], ['code' => 'exists:redeem_codes,code']);
        if ($validator->fails()) {
            return response([
                'error' => 'No redeem code found.'
            ], 400);
        }

        $redeemCode = RedeemCode::findByCode($code);

        if ($redeemCode->redeemed != false) {
            return response([
                'error' => 'Redeem code has already redeemed.'
            ], 400);
        }

        // Check event valid date
        $event = $redeemCode->event;
        if ($event != null) {
            $validator = Validator::make($event->toArray(), ['start_at' => 'before:tomorrow']);
            if ($validator->fails()) {
                return response([
                    'error' => 'Redeem code cannot be used yet.'
                ], 400);
            }
            $validator = Validator::make($event->toArray(), ['end_at' => 'after:yesterday']);
            if ($validator->fails()) {
                return response([
                    'error' => 'Redeem code has expired.'
                ], 400);
            }
        }

        if ($redeemCode->reusable == false) {
            $redeemCode->setRedeemed();
        }

        // Add a redeem code history
        $data['redeem_code_id'] = $redeemCode->id;
        $data['ip'] = filter_input(INPUT_SERVER, "REMOTE_ADDR");
        $data['agent'] = filter_input(INPUT_SERVER, "HTTP_USER_AGENT");
        RedeemCodeHistory::create($data);

        return $redeemCode;
    }

}
