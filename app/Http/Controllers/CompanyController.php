<?php

namespace App\Http\Controllers;

use App\BasicSetting;
use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkInstaller');
        $this->middleware('auth');
        $this->middleware('basicView');
        $this->middleware('dueCount');
    }

    public function manageCompany()
    {
        $data['page_title'] = "Manage Company";
        $data['company'] = Company::all();
        return view('dashboard.company', $data);
    }
    public function storeCompany(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|unique:companies,name',
            'email' => 'required|unique:companies,email',
            'phone' => 'required|unique:companies,phone',
            'address' => 'required',
        ]);
        $data = new Company();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->save();
        $data['total_send'] = 0;
        $data['total_pay'] = 0;
        $data['total_due'] = 0;
        return response()->json($data);

    }
    public function editCompany($product_id)
    {
        $product = Company::find($product_id);
        return response()->json($product);
    }
    public function updateCompany(Request $request,$product_id)
    {
        $product = Company::find($product_id);
        $this->validate($request,[
            'name' => 'required|unique:companies,name,'.$product->id,
            'email' => 'required|unique:companies,email,'.$product->id,
            'phone' => 'required|unique:companies,phone,'.$product->id,
            'address' => 'required',
        ]);

        $product->name = $request->name;
        $product->email = $request->email;
        $product->phone = $request->phone;
        $product->address = $request->address;
        $product->save();
        return response()->json($product);
    }
}
