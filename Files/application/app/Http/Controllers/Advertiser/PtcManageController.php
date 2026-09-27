<?php

namespace App\Http\Controllers\Advertiser;

use App\Models\Ptc;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use App\Http\Controllers\Controller;

class PtcManageController extends Controller
{
    public function index()
    {
        $pageTitle = 'List Advertisements';
        $user = authAdvertiser();
        $ptcs = Ptc::where('user_id', $user->id)->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'advertiser.ptc.index', compact('ptcs', 'pageTitle'));
    }

    public function activePtc()
    {
        return $this->getPtc(1, 'Active Ads');
    }

    public function pendingPtc()
    {
        return $this->getPtc(0, 'Pending Ads');
    }

    public function create()
    {
        $pageTitle = 'Create Ads';
        return view($this->activeTemplate.'advertiser.ptc.create', compact('pageTitle'));
    }

    public function store(Request $request)
    {

        $user = authAdvertiser();

        if($user->ptc_point <=0){

            $notify[] = ['error', 'PPV point has reached its limit. Please subscribe to a new plan.'];
            return back()->withNotify($notify);
        }

        $request->validate([
            'title'         =>  'required',
            'duration'      =>  'required|numeric|min:1',
            'max_show'      =>  'required|numeric|min:1',
            'website_link'  =>  'nullable|url|required_without_all:image,script',
            'image'         => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'script'        =>  'nullable|required_without_all:image,website_link',
        ]);


        $ptc = new Ptc();
        $ptc->user_id   =  $user->id;
        $ptc->title   =   $request->title;
        $ptc->duration  =   $request->duration;
        $ptc->max_show  =   $request->max_show;
        $ptc->remain   =   $request->max_show;
        $ptc->ads_type =   $request->ads_type;
        $ptc->status = gs()->ad_approve == 1 ?? 0;

        if ($ptc->ads_type == 1) {
            $ptc->ads_body  =   $request->website_link;
        } elseif ($ptc->ads_type == 2) {

            if ($request->hasFile('image')) {
                try {
                    $ptc->ads_body = fileUploader($request->image, getFilePath('ptc'), null, $old = null);
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Couldn\'t upload your image'];
                    return back()->withNotify($notify);
                }
            }
        } else {
            $ptc->ads_body =  $request->script;
        }

        $ptc->save();

        $user->ptc_point -=1;
        $user->save();

        $notify[] = ['success', 'Ads has been Created Successfully.'];
        return redirect()->route('advertiser.ptc.index')->withNotify($notify);
    }

    public function edit($id)
    {
        $pageTitle  = 'Update Advertisement';
        $ptc = Ptc::findOrFail($id);

        return view($this->activeTemplate.'advertiser.ptc.edit', compact('ptc', 'pageTitle'));
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'title'         =>  'required',
            'duration'      =>  'required|numeric|min:1',
            'max_show'      =>  'required|numeric|min:1',
        ]);

        $ptc = Ptc::findOrFail($id);
        $ptc->title  =   $request->title;
        $ptc->duration  =   $request->duration;
        $ptc->max_show  =   $request->max_show;
        $ptc->remain =   $request->max_show - $ptc->showed;
        $ptc->ads_type =   $request->ads_type;

        if ($ptc->ads_type == 1) {
            $ptc->ads_body  =   $request->website_link;
        } elseif ($ptc->ads_type == 2) {

            if ($request->hasFile('image')) {
                try {
                    $old = $ptc->ads_body;
                    $ptc->ads_body = fileUploader($request->image, getFilePath('ptc'), null, $old);
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Couldn\'t upload your image'];
                    return back()->withNotify($notify);
                }
            }
        } else {
            $ptc->ads_body  = $request->script;
        }

        $ptc->save();
        $notify[] = ['success', 'Ads has been Updated Successfully.'];
        return redirect()->route('advertiser.ptc.index')->withNotify($notify);
    }

    public function getPtc($status, $pageTitle){
        $user = authAdvertiser();
        $ptcs = Ptc::where('status', $status)
                       ->where('user_id',$user->id)
                       ->latest()
                       ->paginate(getPaginate(12));
        return view($this->activeTemplate.'advertiser.ptc.index', compact('ptcs', 'pageTitle'));
    }

}
