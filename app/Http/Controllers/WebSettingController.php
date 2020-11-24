<?php

namespace App\Http\Controllers;

use App\BasicSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;

class WebSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkInstaller');
        $this->middleware('auth');
        $this->middleware('basicView');
        $this->middleware('dueCount');
    }
    public function manageLogo()
    {
        $data['page_title'] = "Manage Logo & Favicon";
        return view('webControl.logo', $data);
    }
    public function updateLogo(Request $request)
    {

        $this->validate($request,[
           'logo' => 'mimes:png',
            'favicon' => 'mimes:png',
        ]);
        if($request->hasFile('logo')){
            $image = $request->file('logo');
            $filename = 'logo'.'.'.$image->getClientOriginalExtension();
            $location = public_path('storage/images/' . $filename);
            Image::make($image)->save($location);
        }
        if($request->hasFile('favicon')){
            $image = $request->file('favicon');
            $filename = 'favicon'.'.'.$image->getClientOriginalExtension();
            $location = public_path('storage/images/' . $filename);
            Image::make($image)->resize(50,50)->save($location);
        }

        session()->flash('message','Logo and Favicon Updated Successfully.');
        session()->flash('title','Success');
        session()->flash('type','success');
        return redirect()->back();
    }
    public function manageFooter()
    {
        $data['page_title'] = "Manage Web Footer";
        return view('webControl.footer', $data);
    }
    public function updateFooter(Request $request,$id)
    {
        $basic = BasicSetting::findOrFail($id);
        $this->validate($request,[
            'footer_text' => 'required',
            'copy_text' => 'required',
            'footer_image' => 'mimes:png,jpg,jpeg'
        ]);
        $in = $request->except('_method','_token');
        if($request->hasFile('footer_image')){
            $image = $request->file('footer_image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $location = storage_path('storage/images/' . $filename);
            Image::make($image)->resize(1600,475)->save($location);
            $path = public_path('storage/images/');
            $link = $path.$basic->footer_image;
            File::delete($link);
            $in['footer_image'] = $filename;
        }
        $basic->fill($in)->save();
        session()->flash('message','Web Footer Updated Successfully.');
        session()->flash('title','Success');
        session()->flash('type','success');
        return redirect()->back();
    }

}
