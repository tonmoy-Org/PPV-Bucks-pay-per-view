<?php

namespace App\Http\Controllers\Advertiser;

use App\Models\Ptc;
use App\Models\Form;
use App\Models\Plan;
use App\Models\Order;
use App\Models\Hiring;
use App\Models\Deposit;
use App\Lib\FormProcessor;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Lib\GoogleAuthenticator;
use App\Http\Controllers\Controller;

class AdvertiserController extends Controller
{

    public function home() {
        $pageTitle = "Dashboard";
        $advertiser =  authAdvertiser();
        $data['total_ads'] = Ptc::where('user_id',$advertiser->id)->count();
        $data['pending_ads'] = Ptc::where('user_id',$advertiser->id)->where('status',0)->count();
        $data['total_points'] =  authAdvertiser()->ptc_point;

        $deposits = Deposit::selectRaw("SUM(amount) as amount, MONTHNAME(created_at) as month_name, MONTH(created_at) as month_num")
        ->whereYear('created_at', date('Y'))
        ->whereStatus(1)
        ->where('user_id',$advertiser->id)
        ->groupBy('month_name', 'month_num')
        ->orderBy('month_num')
        ->get();
        $depositsChart['labels'] = $deposits->pluck('month_name');
        $depositsChart['values'] = $deposits->pluck('amount');


        $withdrawalsReport = Withdrawal::selectRaw("SUM(amount) as amount, MONTHNAME(created_at) as month_name, MONTH(created_at) as month_num")
            ->whereYear('created_at', date('Y'))
            ->whereStatus(1)
            ->where('advertiser_id',$advertiser->id)
            ->groupBy('month_name', 'month_num')
            ->orderBy('month_num')
            ->get();
        $withdrawalsChart['labels'] = $withdrawalsReport->pluck('month_name');
        $withdrawalsChart['values'] = $withdrawalsReport->pluck('amount');

        $ptcs = Ptc::where('user_id', $advertiser->id)->latest()->paginate(getPaginate());

        return view($this->activeTemplate .'advertiser.dashboard', compact('pageTitle','advertiser','data','depositsChart','withdrawalsChart','ptcs'));

    }

    public function plan()
    {
        $pageTitle = 'Membership Plans';
        $plans = \App\Models\Plan::where('status', 1)->orderBy('price', 'asc')->get();
        return view($this->activeTemplate . 'advertiser.plan', compact('pageTitle', 'plans'));
    }

    public function show2faForm() {
        $general    = gs();
        $ga         = new GoogleAuthenticator();
        $advertiser = authAdvertiser();
        $secret     = $ga->createSecret();
        $qrCodeUrl  = $ga->getQRCodeGoogleUrl($advertiser->username . '@' . $general->site_name, $secret);
        $pageTitle  = '2FA Security';
        return view($this->activeTemplate . 'advertiser.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request) {
        $advertiser = authAdvertiser();
        $this->validate($request, [
            'key'  => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($advertiser, $request->code, $request->key);

        if ($response) {
            $advertiser->tsc = $request->key;
            $advertiser->ts  = 1;
            $advertiser->save();
            $notify[] = ['success', 'Google authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }

    }

    public function disable2fa(Request $request) {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $advertiser = authAdvertiser();
        $response   = verifyG2fa($advertiser, $request->code);

        if ($response) {
            $advertiser->tsc = null;
            $advertiser->ts  = 0;
            $advertiser->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }

        return back()->withNotify($notify);
    }

    public function advertiserData() {
        $advertiser = authAdvertiser();

        if ($advertiser->reg_step == 1) {
            return to_route('advertiser.home');
        }

        $pageTitle = 'Advertiser Data';
        return view($this->activeTemplate .'advertiser.advertiser_data', compact('pageTitle', 'advertiser'));
    }

    public function advertiserDataSubmit(Request $request) {
        $advertiser = authAdvertiser();

        if ($advertiser->reg_step == 1) {
            return to_route('advertiser.home');
        }

        $request->validate([
            'firstname' => 'required',
            'lastname'  => 'required',
        ]);
        $advertiser->firstname = $request->firstname;
        $advertiser->lastname  = $request->lastname;
        $advertiser->address   = [
            'country' => @$advertiser->address->country,
            'address' => $request->address,
            'state'   => $request->state,
            'zip'     => $request->zip,
            'city'    => $request->city,
        ];
        $advertiser->reg_step = 1;
        $advertiser->save();

        $notify[] = ['success', 'Registration process completed successfully'];
        return to_route('advertiser.home')->withNotify($notify);
    }

    public function transactions(Request $request) {
        $pageTitle    = 'Transactions';
        $remarks      = Transaction::where('advertiser_id', authAdvertiserId())->distinct('remark')->orderBy('remark')->get('remark');
        $transactions = Transaction::where('advertiser_id', authAdvertiserId());

        if ($request->search) {
            $transactions = $transactions->where('trx', $request->search);
        }

        if ($request->type) {
            $transactions = $transactions->where('trx_type', $request->type);
        }

        if ($request->remark) {
            $transactions = $transactions->where('remark', $request->remark);
        }

        $transactions = $transactions->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'advertiser.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function depositHistory(Request $request)
    {
        $pageTitle = 'Deposit History';
        $deposits = authAdvertiser()->deposits();
        if ($request->search) {
            $deposits = $deposits->where('trx',$request->search);
        }
        $deposits = $deposits->with(['gateway'])->orderBy('id','desc')->paginate(getPaginate());
        return view($this->activeTemplate.'advertiser.deposit_history', compact('pageTitle', 'deposits'));
    }

    public function packages(){
        $pageTitle = "Packages";
        $packages =  Plan::where('status',1)->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'advertiser.package',compact('pageTitle','packages'));
    }

}
