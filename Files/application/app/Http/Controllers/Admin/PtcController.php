<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ptc;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use App\Http\Controllers\Controller;

class PtcController extends Controller
{
    public function index()
    {
        $pageTitle = 'List Advertisement';
        $ptcs = Ptc::with('user')->latest()->paginate(getPaginate());
        return view('admin.ptc.index', compact('ptcs', 'pageTitle'));
    }

    public function activePtc()
    {
        return $this->getPtc(1, 'Active Ptc');
    }

    public function pendingPtc()
    {
        return $this->getPtc(0, 'Pending Ptc');
    }

    public function create()
    {
        $pageTitle = 'Add Advertisement';
        return view('admin.ptc.create', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         =>  'required',
            'duration'      =>  'required|numeric|min:1',
            'max_show'      =>  'required|numeric|min:1',
            'website_link'  =>  'nullable|url|required_without_all:image,script',
            'image'         => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'script'        =>  'nullable|required_without_all:image,website_link',
        ]);

        $ptc = new Ptc();
        $ptc->title   =   $request->title;
        $ptc->duration  =   $request->duration;
        $ptc->max_show  =   $request->max_show;
        $ptc->remain   =   $request->max_show;
        $ptc->ads_type =   $request->ads_type;
        $ptc->status  =  1;

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
        $notify[] = ['success', 'Ads has been Created Successfully.'];
        return redirect()->route('admin.ptc.index')->withNotify($notify);
    }

    public function edit($id)
    {
        $pageTitle  = 'Update Advertisement';
        $ptc = Ptc::findOrFail($id);

        return view('admin.ptc.update', compact('ptc', 'pageTitle'));
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
        $ptc->status =   isset($request->status) ? 1 : 0;

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
        return redirect()->route('admin.ptc.index')->withNotify($notify);
    }

    public function changeStatus(Request $request){
        $ptc = Ptc::findOrFail($request->id);
        $ptc->status = $request->status;
        $ptc->save();

        $notify[] = ['success', 'PPV has been change status successfully'];
        return back()->withNotify($notify);
    }


    public function getPtc($status, $pageTitle){
        $ptcs = Ptc::where('status', $status)
                       ->with(['user'])
                       ->latest()
                       ->paginate(getPaginate(12));
        return view('admin.ptc.index', compact('ptcs', 'pageTitle'));
    }



}
