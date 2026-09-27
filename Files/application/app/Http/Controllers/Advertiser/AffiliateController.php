<?php

namespace App\Http\Controllers\Advertiser;

use Illuminate\Http\Request;
use App\Models\CommissionLog;
use App\Http\Controllers\Controller;

class AffiliateController extends Controller
{
    public function reffered(){
        $pageTitle = 'Reffered';
        $user      = authAdvertiser();
        $maxLevel  = CommissionLog::max('level');
        return view($this->activeTemplate.'advertiser.affiliate.reffered',compact('pageTitle','user','maxLevel'));
    }

    public function refferedCommission(){
        $pageTitle = "Commission Logs";
        $user      = authAdvertiser();
        $commissions =  CommissionLog::with('reffer')->where('user_id', $user->id)->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'advertiser.affiliate.index',compact('pageTitle','commissions'));
    }

    public function refferlinkSend(Request $request) {
        $request->validate([
            'reffer_link' => 'required',
            'email' => 'required|email',
        ]);

        if(authAdvertiser()->email == $request->email){
            $notify[] = ['error', 'You can not reffer yourself'];
            return back()->withNotify($notify)->withInput();
        }

        $receiverName = explode('@', $request->email)[0];

        $user = [
            'username'=>authAdvertiser()->username,
            'email'=>$request->email,
            'fullname'=>$receiverName,
        ];

        $user = json_decode(json_encode($user),false);



        notify($user,'REFFERAL_LINK',[
            'username' => $user->username,
            'link' => $request->reffer_link
        ],['email']);


        $notify[] = ['success', 'successfully send email to ' . $user->email];
        return back()->withNotify($notify)->withInput();

    }

}
