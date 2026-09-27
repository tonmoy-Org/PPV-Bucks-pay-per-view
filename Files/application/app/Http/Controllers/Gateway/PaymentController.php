<?php

namespace App\Http\Controllers\Gateway;

use App\Models\Plan;
use App\Models\User;
use App\Models\Deposit;
use App\Lib\FormProcessor;
use App\Models\Advertiser;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\GatewayCurrency;
use App\Models\AdminNotification;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{

    // plan
    public function payment($id){

        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->with('method')->orderby('method_code')->get();
        $pageTitle = 'Payment Methods';

        $plan = Plan::findOrFail($id);
        $user = authAdvertiser() ?? auth()->user();
        if(!$user) {
            return to_route('user.login');
        }

        $layout = authAdvertiser() ? $this->activeTemplate . 'layouts.advertiser.master' : $this->activeTemplate . 'layouts.user.master';

        return view($this->activeTemplate . 'advertiser.payment.payment', compact('gatewayCurrency', 'pageTitle', 'plan', 'layout', 'user'));
    }

    public function deposit()
    {
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->with('method')->orderby('method_code')->get();
        $pageTitle = 'Deposit Methods';
        $layout = authAdvertiser() ? $this->activeTemplate . 'layouts.advertiser.master' : $this->activeTemplate . 'layouts.user.master';
        return view($this->activeTemplate . 'advertiser.payment.deposit', compact('gatewayCurrency', 'pageTitle', 'layout'));
    }

    public function depositInsert(Request $request)
    {
        $user = authAdvertiser() ?? auth()->user();
        if(!$user) {
            return to_route('user.login');
        }

        if($request->gateway == 'balance'){
            $plan = Plan::findOrFail($request->plan_id);

            if($user->balance < $request->amount){
                $notify[] = ['error', 'Insufficient Balance'];
                return back()->withNotify($notify);
            }

            if ($user instanceof \App\Models\Advertiser) {
                $user->ptc_point += $plan->point;
            }

            $user->balance -= $plan->price;
            $user->plan_id = $plan->id;
            $user->save();

            $trx = getTrx();

            $transaction                =   new Transaction();
            if($user instanceof \App\Models\Advertiser) {
                $transaction->advertiser_id = $user->id;
            } else {
                $transaction->user_id       = $user->id;
            }
            $transaction->amount        =   $plan->price;
            $transaction->post_balance  =   $user->balance;
            $transaction->charge        =   0;
            $transaction->trx_type      =   '-';
            $transaction->details       =   'Subscribe plan ' . $plan->name;
            $transaction->trx           =   $trx;
            $transaction->remark        =   'Subscribe_Plan';
            $transaction->save();


            $adminNotification   = new AdminNotification();
            if($user instanceof \App\Models\Advertiser) {
                $adminNotification->advertiser_id  = $user->id;
                $adminNotification->click_url      = urlPath('admin.advertisers.detail', $user->id);
            } else {
                $adminNotification->user_id        = $user->id;
                $adminNotification->click_url      = urlPath('admin.users.detail', $user->id);
            }
            $adminNotification->title   = 'Plan Subscribe from '.$user->username;
            $adminNotification->save();

            notify($user, 'PLAN SUBSCRIBE', [
                'plan_name'=> $plan->name,
                'amount' => showAmount($plan->price),
                'trx' => $trx,
                'post_balance' => showAmount($user->balance)
            ]);

            levelCommision($user->id, $plan->price,'commission for plan subscribe',$plan->id);

            $notify[] = ['success', 'Plan Subscribe has been successful'];
            return ($user instanceof \App\Models\Advertiser) ? to_route('advertiser.home')->withNotify($notify) : to_route('user.home')->withNotify($notify);
        }

        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'method_code' => 'required',
            'currency' => 'required',
        ]);

        $planId = $request->plan_id;
        $gate = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->where('method_code', $request->method_code)->where('currency', $request->currency)->first();
        if (!$gate) {
            $notify[] = ['error', 'Invalid gateway'];
            return back()->withNotify($notify);
        }

        if ($gate->min_amount > $request->amount || $gate->max_amount < $request->amount) {
            $notify[] = ['error', 'Please follow deposit limit'];
            return back()->withNotify($notify);
        }

        $charge = $gate->fixed_charge + ($request->amount * $gate->percent_charge / 100);
        $payable = $request->amount + $charge;
        $final_amo = $payable * $gate->rate;

        $data = new Deposit();
        $data->user_id = $user->id;
        $data->method_code = $gate->method_code;
        $data->method_currency = strtoupper($gate->currency);
        $data->amount = $request->amount;
        $data->plan_id = $planId;
        $data->charge = $charge;
        $data->rate = $gate->rate;
        $data->final_amo = $final_amo;
        $data->btc_amo = 0;
        $data->btc_wallet = "";
        $data->trx = getTrx();
        $data->try = 0;
        $data->status = 0;
        $data->detail = ['user_type' => ($user instanceof \App\Models\Advertiser) ? 'advertiser' : 'user'];
        $data->save();
        session()->put('Track', $data->trx);
        return authAdvertiser() ? to_route('advertiser.deposit.confirm') : to_route('user.deposit.confirm');
    }


    public function appDepositConfirm($hash)
    {
        try {
            $id = decrypt($hash);
        } catch (\Exception $ex) {
            return "Sorry, invalid URL.";
        }
        $data = Deposit::where('id', $id)->where('status', 0)->orderBy('id', 'DESC')->firstOrFail();
        $user = Advertiser::findOrFail($data->user_id);
        auth()->guard('advertiser')->login($user);
        session()->put('Track', $data->trx);
        return to_route('advertiser.deposit.confirm');
    }


    public function depositConfirm()
    {
        $track = session()->get('Track');
        $deposit = Deposit::where('trx', $track)->where('status',0)->orderBy('id', 'DESC')->with('gateway')->firstOrFail();

        if ($deposit->method_code >= 1000) {
            return authAdvertiser() ? to_route('advertiser.deposit.manual.confirm') : to_route('user.deposit.manual.confirm');
        }


        $dirName = $deposit->gateway->alias;
        $new = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';

        $data = $new::process($deposit);
        $data = json_decode($data);


        if (isset($data->error)) {
            $notify[] = ['error', $data->message];
            return to_route(gatewayRedirectUrl())->withNotify($notify);
        }
        if (isset($data->redirect)) {
            return redirect($data->redirect_url);
        }

        // for Stripe V3
        if(@$data->session){
            $deposit->btc_wallet = $data->session->id;
            $deposit->save();
        }

        $pageTitle = 'Payment Confirm';
        return view($this->activeTemplate . $data->view, compact('data', 'pageTitle', 'deposit'));
    }


    public static function userDataUpdate($deposit,$isManual = null)
    {
        if ($deposit->status == 0 || $deposit->status == 2) {
            $deposit->status = 1;
            $deposit->save();

            $isAdvertiser = false;
            if (isset($deposit->detail->user_type)) {
                $isAdvertiser = ($deposit->detail->user_type == 'advertiser');
                $user = $isAdvertiser ? Advertiser::find($deposit->user_id) : User::find($deposit->user_id);
            } else {
                $user = User::find($deposit->user_id) ?? Advertiser::find($deposit->user_id);
                $isAdvertiser = ($user instanceof Advertiser);
            }

            if (!$user) {
                return;
            }

            if (!isset($deposit->plan_id) || !$deposit->plan_id) {
                $user->balance += $deposit->amount;
                $user->save();
            }

            if (isset($deposit->plan_id) && $deposit->plan_id) {
                $plan = Plan::find($deposit->plan_id);
                if ($plan) {
                    if ($isAdvertiser) {
                        $user->ptc_point += $plan->point;
                    }
                    $user->plan_id = $plan->id;
                    $user->save();

                    $adminNotification = new AdminNotification();
                    if ($isAdvertiser) {
                        $adminNotification->advertiser_id = $user->id;
                        $adminNotification->click_url = urlPath('admin.advertisers.detail', $user->id);
                    } else {
                        $adminNotification->user_id = $user->id;
                        $adminNotification->click_url = urlPath('admin.users.detail', $user->id);
                    }
                    $adminNotification->title = 'Plan Subscribe from ' . $user->username;
                    $adminNotification->save();

                    levelCommision($user->id, $plan->price, 'commission for plan subscribe', $plan->id);

                    notify($user, 'PLAN SUBSCRIBE', [
                        'plan_name' => $plan->name,
                        'amount' => showAmount($plan->price),
                        'trx' => $deposit->trx,
                        'post_balance' => showAmount($user->balance)
                    ]);
                }
            }


            $transaction = new Transaction();
            if ($isAdvertiser) {
                $transaction->advertiser_id = $deposit->user_id;
            } else {
                $transaction->user_id = $deposit->user_id;
            }
            $transaction->amount = $deposit->amount;
            $transaction->post_balance = $user->balance;
            $transaction->charge = $deposit->charge;
            $transaction->trx_type = '+';
            $transaction->details = 'Deposit Via ' . ($deposit->gatewayCurrency() ? $deposit->gatewayCurrency()->name : 'Gateway');
            $transaction->trx = $deposit->trx;
            $transaction->remark = 'deposit';
            $transaction->save();

            if (!$isManual) {
                $adminNotification = new AdminNotification();
                if ($isAdvertiser) {
                    $adminNotification->advertiser_id = $user->id;
                } else {
                    $adminNotification->user_id = $user->id;
                }
                $adminNotification->title = 'Deposit successful via ' . ($deposit->gatewayCurrency() ? $deposit->gatewayCurrency()->name : 'Gateway');
                $adminNotification->click_url = urlPath('admin.deposit.successful');
                $adminNotification->save();
            }

            notify($user, $isManual ? 'DEPOSIT_APPROVE' : 'DEPOSIT_COMPLETE', [
                'method_name' => $deposit->gatewayCurrency() ? $deposit->gatewayCurrency()->name : 'Gateway',
                'method_currency' => $deposit->method_currency,
                'method_amount' => showAmount($deposit->final_amo),
                'amount' => showAmount($deposit->amount),
                'charge' => showAmount($deposit->charge),
                'rate' => showAmount($deposit->rate),
                'trx' => $deposit->trx,
                'post_balance' => showAmount($user->balance)
            ]);


        }
    }

    public function manualDepositConfirm()
    {
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', 0)->where('trx', $track)->first();
        if (!$data) {
            return to_route(gatewayRedirectUrl());
        }
        if ($data->method_code > 999) {

            $pageTitle = 'Deposit Confirm';
            $method = $data->gatewayCurrency();
            $gateway = $method->method;
            $layout = authAdvertiser() ? $this->activeTemplate . 'layouts.advertiser.master' : $this->activeTemplate . 'layouts.user.master';
            return view($this->activeTemplate . 'advertiser.payment.manual', compact('data', 'pageTitle', 'method','gateway', 'layout'));
        }
        abort(404);
    }

    public function manualDepositUpdate(Request $request)
    {
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', 0)->where('trx', $track)->first();
        if (!$data) {
            return to_route(gatewayRedirectUrl());
        }
        $gatewayCurrency = $data->gatewayCurrency();
        $gateway = $gatewayCurrency->method;
        $formData = $gateway->form->form_data;

        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);


        $detailArr = [];
        if (isset($data->detail->user_type)) {
            $detailArr['user_type'] = $data->detail->user_type;
        } else {
            $currentUser = authAdvertiser() ?? auth()->user();
            $detailArr['user_type'] = ($currentUser instanceof Advertiser) ? 'advertiser' : 'user';
        }
        if (is_array($userData) || is_object($userData)) {
            foreach ($userData as $k => $v) {
                $detailArr[$k] = $v;
            }
        }
        $data->detail = $detailArr;
        $data->status = 2; // pending
        $data->save();

        $isAdvertiser = (isset($detailArr['user_type']) && $detailArr['user_type'] == 'advertiser');
        $user = $isAdvertiser ? Advertiser::find($data->user_id) : (User::find($data->user_id) ?? Advertiser::find($data->user_id));
        if($user) {
            $adminNotification = new AdminNotification();
            if ($user instanceof Advertiser) {
                $adminNotification->advertiser_id = $user->id;
            } else {
                $adminNotification->user_id = $user->id;
            }
            $adminNotification->title = 'Deposit request from '.$user->username;
            $adminNotification->click_url = urlPath('admin.deposit.details',$data->id);
            $adminNotification->save();

            notify($user, 'DEPOSIT_REQUEST', [
                'method_name' => $data->gatewayCurrency()->name,
                'method_currency' => $data->method_currency,
                'method_amount' => showAmount($data->final_amo),
                'amount' => showAmount($data->amount),
                'charge' => showAmount($data->charge),
                'rate' => showAmount($data->rate),
                'trx' => $data->trx
            ]);
        }

        $notify[] = ['success', 'You have deposit request has been taken'];
        return authAdvertiser() ? to_route('advertiser.deposit.history')->withNotify($notify) : to_route('user.deposit.history')->withNotify($notify);
    }


}
