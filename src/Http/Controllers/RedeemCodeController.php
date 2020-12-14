<?php

namespace Furic\RedeemCodes\Http\Controllers;

use Furic\RedeemCodes\Models\Event;
use Furic\RedeemCodes\Models\RedeemCode;
use Furic\RedeemCodes\Models\RedeemCodeReward;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

// The controller for redeem code web console.
class RedeemCodeController extends Controller
{

    /**
     * Display a listing of the redeem code resource.
     *
     * @return \Illuminate\View\View
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
        return view('redeem-codes::index', compact('redeemCodes'));
    }

    /**
     * Show the form for creating a new redeem code resource.
     * No need for the time being, simply redirect to index page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        return redirect()->route('redeem-codes.index');
    }

    /**
     * Generate a random string with given length.
     *
     * @param  int  $length
     * @return string
     */
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
     * Store a newly created redeem code resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'count' => 'required|numeric|min:1|max:500',
        ]);

        if ($validator->fails()) {
            return view('redeem-codes::index')->with(['redeemCode' => $request->all(), 'message' => 'Data not valid']);
        }

        $event = new Event;
        $event->name = $request->description;
        $event->save();

        $codes = [];

        if ($request->has('reusable')) { // Make sure reusable only generate one code only
            $request->merge(['count' => 1]);
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
                $redeemCode->code = strtoupper($request->prefix).$this->generateRandomString(12 - strlen($request->prefix));
            }
            array_push($codes, $redeemCode->code);
            $redeemCode->save();
        }

        $rewardTypesCount = count($request->reward_types);
        for ($i = 0; $i < $rewardTypesCount; $i++) {
            $redeemCodeReward = new RedeemCodeReward;
            $redeemCodeReward->event_id = $event->id;
            $redeemCodeReward->type = $request->reward_types[$i];
            $redeemCodeReward->amount = $request->reward_amounts[$i];
            $redeemCodeReward->save();
        }

        return view('redeem-codes::added', compact('codes'));
    }

    /**
     * Display the specified redeem code resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        return redirect()->route('redeem-codes.edit');
    }

    /**
     * Show the form for editing the specified redeem code resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $redeemCode = RedeemCode::findOrFail($id);
        $redeemCodesInEvent = $redeemCode->event->redeemCodes;
        return view('redeem-codes::edit', compact('redeemCode', 'redeemCodesInEvent'));
    }

    /**
     * Update the specified redeem code resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $redeemCode = RedeemCode::findOrFail($id);

        $redeemCode->fill($request->all());
        if ($request->has('reusable')) {
            $redeemCode->reusable = true;
        } else {
            $redeemCode->reusable = false;
        }
        if ($request->has('redeemed')) {
            $redeemCode->redeemed = $request->redeemed;
        } else {
            $redeemCode->redeemed = false;
        }
        $redeemCode->save();

        if ($request->has('description')) {
            $redeemCode->event->name = $request->description;
            $redeemCode->event->save();
        }
        if ($request->has('reward_types')) {
            $redeemCodeRewards = $redeemCode->rewards;
            $rewardTypesCount = count($request->reward_types);
            for ($i = 0; $i < $rewardTypesCount; $i++) {
                $redeemCodeReward = $i < $redeemCodeRewards->count() ? $redeemCodeRewards->slice($i, 1)->first() : new RedeemCodeReward;
                $redeemCodeReward->event_id = $redeemCode->event->id;
                $redeemCodeReward->type = $request->reward_types[$i];
                $redeemCodeReward->amount = $request->reward_amounts[$i];
                $redeemCodeReward->save();
            }
            for ($i = $rewardTypesCount; $i < $redeemCodeRewards->count(); $i++) {
                $redeemCodeReward = $redeemCodeRewards->slice($i, 1)->first();
                $redeemCodeReward->delete();
            }
        }
        return redirect()->route('redeem-codes.index')->with('message', 'Redeem code {$redeemCode->code} updated successfully.');
    }

    /**
     * Remove the specified redeem code resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $redeemCode = RedeemCode::findOrFail($id);
        $redeemCode->delete();
        return redirect()->route('redeem-codes.index')->with('message', 'Redeem code {$redeemCode->code} deleted successfully.');
    }
    
}