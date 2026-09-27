<?php

namespace App\Http\Controllers\Admin;

use App\Models\Referral;
use App\Models\Refferal;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Http\Controllers\Controller;

class RefferalController extends Controller
{

    public function index(){
        $pageTitle = "Refferal";
        $refferal = Refferal::first();
        return view('admin.refferal.index',compact('pageTitle','refferal'));
    }

    public function store(Request $request){

        $request->validate([
            'percent' => 'required|numeric',
       ]);

        $refferal = Refferal::first() ?? new Refferal();
        $refferal->level =  1;
        $refferal->percent =  $request->percent;
        $refferal->save();

        $notify[] = ['success', 'Successfully Generated Refferal'];
        return back()->withNotify($notify);
    }
}
