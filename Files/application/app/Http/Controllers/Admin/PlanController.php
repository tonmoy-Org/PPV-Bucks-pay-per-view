<?php

namespace App\Http\Controllers\Admin;

use App\Models\Plan;
use App\Models\Referral;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlanController extends Controller
{
    public function  index(){
        $pageTitle = 'Plans';
        $plans =  Plan::latest()->paginate(getPaginate());
        return view('admin.plan.index',compact('pageTitle','plans'));

    }

    public function store(Request $request){

        $request->validate([
            'name'=>'required',
            'price'=>'required|numeric|min:0',
            'point'=>'required|numeric|min:0',
            'ad_rate'=>'nullable|numeric|min:0',
            'ad_duration'=>'nullable|integer|min:0',
        ]);

        $plan = new Plan();
        $plan->name          =  $request->name;
        $plan->subtitle      =  $request->subtitle;
        $plan->price         =  $request->price;
        $plan->point         =  $request->point;
        $plan->ad_rate       =  $request->ad_rate ?? 0;
        $plan->ad_duration   =  $request->ad_duration ?? 0;
        $plan->validity_text =  $request->validity_text;
        $plan->features      =  $request->features;
        $plan->status        =  isset($request->status)? 1:0;
        $plan->save();

        $notify[] = ['success', 'Plan has been created Successfully.'];
        return back()->withNotify($notify);
    }



    public function update(Request $request){

        $request->validate([
            'name'=>'required',
            'price'=>'required|numeric|min:0',
            'point'=>'required|numeric|min:0',
            'ad_rate'=>'nullable|numeric|min:0',
            'ad_duration'=>'nullable|integer|min:0',
        ]);


        $plan=Plan::findOrFail($request->id);
        $plan->name          =  $request->name;
        $plan->subtitle      =  $request->subtitle;
        $plan->price         =  $request->price;
        $plan->point         =  $request->point;
        $plan->ad_rate       =  $request->ad_rate ?? 0;
        $plan->ad_duration   =  $request->ad_duration ?? 0;
        $plan->validity_text =  $request->validity_text;
        $plan->features      =  $request->features;
        $plan->status        =  isset($request->status)? 1:0;
        $plan->save();

        $notify[] = ['success', 'Plan has been updated Successfully.'];
        return back()->withNotify($notify);
     }
}
