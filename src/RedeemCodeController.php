<?php

namespace Furic\RedeemCodes;

use Event;
use RedeemCode;
use RedeemCodeReward;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

// The controller for redeem code web console.
class RedeemCodeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $redeemCodes = RedeemCode::orderBy('created_at', 'desc')->get();
        foreach ($redeemCodes as $redeemCode) {
            $event = Event::find($redeemCode->event_id);
            if (!is_null($event)) {
                $redeemCode->description = $event->name;
            }
        }
        return view('furic.redeemcodes.index')->with('redeemCodes', $redeemCodes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'count' => 'required|numeric|min:1|max:500',
        ]);

        if ($validator->fails()) {
            return response([
                'error' => 'Data not valid.'
            ], 400);
        }

        $event = new Event;
        $event->name = $request->description;
        $event->save();

        $codes = [];

        if ($request->has('reusable')) { // Make sure reusable only generate one code only
            $request->count = 1;
        }

        for ($i = 0; $i < $request->count; $i++) {
            $redeemCode = new RedeemCode;
            $redeemCode->event_id = $event->id;
            if ($request->has('reusable')) {
                $redeemCode->reusable = 1;
            }
            if (empty($request->prefix)) {
                $redeemCode->code = $this->generateRandomString(12);
            } else {
                $redeemCode->code = strtoupper($request->prefix) . $this->generateRandomString(12 - strlen($request->prefix));
            }
            array_push($codes, $redeemCode->code);
            $redeemCode->save();
        }

        for ($i = 0; $i < count($request->reward_types); $i++) {
            $redeemCodeReward = new RedeemCodeReward;
            $redeemCodeReward->event_id = $event->id;
            $redeemCodeReward->type = $request->reward_types[$i];
            $redeemCodeReward->amount = $request->reward_amounts[$i];
            $redeemCodeReward->save();
        }

        return view('furic.redeemcodes.added')->with('codes', $codes);
    }

    private function generateRandomString($length = 10)
    {
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
