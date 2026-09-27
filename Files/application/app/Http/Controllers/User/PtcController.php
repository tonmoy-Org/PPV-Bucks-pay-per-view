<?php

namespace App\Http\Controllers\User;

use App\Models\Ptc;
use App\Models\PtcView;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PtcController extends Controller
{
    protected function getDailyAdLimit($user)
    {
        if ($user && $user->plan) {
            return (int) $user->plan->point;
        }
        $freePlan = \App\Models\Plan::where('status', 1)->where('price', 0)->first();
        if ($freePlan) {
            return (int) $freePlan->point;
        }
        return 0;
    }

    protected function getAdEarningRate($user)
    {
        if ($user && $user->plan && $user->plan->ad_rate !== null) {
            return (float) $user->plan->ad_rate;
        }
        $freePlan = \App\Models\Plan::where('status', 1)->where('price', 0)->first();
        if ($freePlan && $freePlan->ad_rate !== null) {
            return (float) $freePlan->ad_rate;
        }
        return (float) (gs()->ptc_amount ?? 0);
    }

    protected function getAdDuration($user, $ptc)
    {
        if ($user && $user->plan && (int)$user->plan->ad_duration > 0) {
            return (int) $user->plan->ad_duration;
        }
        $freePlan = \App\Models\Plan::where('status', 1)->where('price', 0)->first();
        if ($freePlan && (int)$freePlan->ad_duration > 0) {
            return (int) $freePlan->ad_duration;
        }
        return (int) ($ptc->duration ?? 10);
    }

    public function index(){

        $pageTitle      = 'Ads List';
        $user           = auth()->user();
        $ads            = Ptc::where('status',1)->where('remain','>',0)->inRandomOrder()->orderBy('remain','desc')->limit(100)->get();
        $resetHours     = (int) (gs()->ad_reset_hours ?? 12);
        $resetTime      = now()->subHours($resetHours);
        $viewAds        = PtcView::where('user_id', $user->id)->where('created_at', '>=', $resetTime)->get();
        $todayTotalView = $viewAds->count();
        $viewed         = $viewAds->pluck('ptc_id')->toArray();
        $dailyLimit     = $this->getDailyAdLimit($user);
        $adRate         = $this->getAdEarningRate($user);

        return view($this->activeTemplate.'user.ptc.index',compact('ads','pageTitle','viewed','todayTotalView','dailyLimit','adRate','resetHours'));
    }

    public function show($hash){

        $pageTitle = 'Show Advertisement';
        $user  = auth()->user();

        $id = $this->checkEligibleAd($hash,$user);

        if(!$id){
            $notify[] = ['error',"You are not eligible for this link"];
            return redirect()->route('user.home')->withNotify($notify);
        }

        $resetHours = (int) (gs()->ad_reset_hours ?? 12);
        $resetTime  = now()->subHours($resetHours);
        $ptc        = Ptc::where('id',$id)->where('remain','>',0)->where('status',1)->firstOrFail();
        $viewAds    = PtcView::where('user_id',$user->id)->where('created_at', '>=', $resetTime)->get();
        $dailyLimit = $this->getDailyAdLimit($user);

        if($viewAds->count() >= $dailyLimit){
            $notify[] = ['error','Opps! Your ad view limit ('.$dailyLimit.' ads/'.$resetHours.' hours) for your plan is over. Please upgrade your plan or wait to watch more ads.'];
            return back()->withNotify($notify);
        }

        if ($viewAds->where('ptc_id',$ptc->id)->first()) {
            $notify[] = ['error','You cannot see this ad before '.$resetHours.' hours'];
            return back()->withNotify($notify);
        }

        $duration = $this->getAdDuration($user, $ptc);
        session()->put('ptc_start_' . $ptc->id . '_' . $user->id, now()->timestamp);

        return view($this->activeTemplate.'user.ptc.show',compact('ptc','pageTitle','duration'));

    }

    public function todayClick(){
        $pageTitle      =   'Today Click';
        $user           =   auth()->user();
        $totalClicks     =   PtcView::where('user_id', $user->id)->where('view_date',Date('Y-m-d'))->selectRaw('DATE(view_date) as date')->groupBy('date')->selectRaw('count(id) as clicks, sum(amount) as earned')->orderBy('date', 'desc')->paginate(getPaginate());

        return view($this->activeTemplate.'user.ptc.today_click',compact('totalClicks','pageTitle'));

    }

    public function confirm(Request $request,$hash){

        $user = auth()->user();
        $id = $this->checkEligibleAd($hash,$user);

        if(!$id){
            $notify[] = ['error',"You are not eligible for this link"];
            return redirect()->route('user.home')->withNotify($notify);
        }

        $resetHours = (int) (gs()->ad_reset_hours ?? 12);
        $resetTime  = now()->subHours($resetHours);
        $ptc = Ptc::where('id',$id)->where('remain','>',0)->where('status',1)->firstOrFail();
        $viewAds = PtcView::where('user_id',$user->id)->where('created_at', '>=', $resetTime)->get();
        $dailyLimit = $this->getDailyAdLimit($user);

        if($viewAds->count() >= $dailyLimit){
            $notify[] = ['error','Opps! Your ad view limit ('.$dailyLimit.' ads/'.$resetHours.' hours) for your plan is over. Please upgrade your plan or wait to watch more ads.'];
            return back()->withNotify($notify);
        }

        if ($viewAds->where('ptc_id',$ptc->id)->first()) {
            $notify[] = ['error','You cannot see this ad before '.$resetHours.' hours'];
            return back()->withNotify($notify);
        }

        $duration   = $this->getAdDuration($user, $ptc);
        $sessionKey = 'ptc_start_' . $ptc->id . '_' . $user->id;
        $startTime  = session()->get($sessionKey);

        if (!$startTime || (now()->timestamp - $startTime) < ($duration - 2)) {
            $notify[] = ['error', 'You must stay on the ad window for the full ' . $duration . ' seconds. Reward cancelled!'];
            return redirect()->route('user.ptc.index')->withNotify($notify);
        }

        session()->forget($sessionKey);

        $ptc->increment('showed');
        $ptc->decrement('remain');
        $ptc->save();

        $adRate = $this->getAdEarningRate($user);
        $user->balance += $adRate;
        $user->save();


        $trx                            =   getTrx();
        $transection                    =   new Transaction();
        $transection->user_id           =   $user->id;
        $transection->amount            =   $adRate;
        $transection->post_balance      =   $user->balance;
        $transection->charge            =   0;
        $transection->trx_type          =   '+';
        $transection->trx               =   $trx;
        $transection->details           =   'Earn amount from ads';
        $transection->remark            =   'PPV earn';
        $transection->save();

        $PtcView                        =   New PtcView();
        $PtcView->user_id               =   $user->id;
        $PtcView->ptc_id                =   $ptc->id;
        $PtcView->amount                =   $adRate;
        $PtcView->view_date             =   Date('Y-m-d');
        $PtcView->save();

        $notify[] = ['success','Successfully viewed this ads'];
        return redirect()->route('user.ptc.index')->withNotify($notify);

    }

    protected function checkEligibleAd($hash, $user){
        $decrypted          =   decrypt($hash);
        $decryptData        =   explode('|',$decrypted);
        $id                 =   $decryptData[0];

        if($decryptData[1]!=$user->id){
            return false;
        }

        return $id;
    }


}
