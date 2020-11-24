<?php

namespace App\Http\Controllers;

use App\BasicSetting;
use App\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkInstaller');
        $this->middleware('auth');
        $this->middleware('basicView');
        $this->middleware('dueCount');
    }

    public function manageCategory()
    {
        $data['page_title'] = " Category";
        $data['category'] = Category::all();
        $data['company'] = Company::all();
        return view('dashboard.category', $data);
    }
    public function storeCategory(Request $request)
    {
        $this->validate($request,[
            'company_id' => 'required',
            'unit' => 'required',
            'name' => 'unique_with:categories,company_id',
            'status' => 'required',
        ]);

    	$data = new Category();
        $data->name = $request->name;
        $data->unit = $request->unit;
        $data->company_id = $request->company_id;
        $data->slug = Str::slug($request->name);
        $data->status = $request->status;
        $data->save();
        $data['company'] = Company::find($request->company_id)->name;
        return response()->json($data);

    }
    public function editCategory($product_id)
    {
        $product = Category::find($product_id);
        return response()->json($product);
    }
    public function updateCategory(Request $request,$product_id)
    {
        $product = Category::find($product_id);
        $request->validate([
            'company_id' => 'required',
            'unit' => 'required',
            'name' => 'unique_with:categories,company_id,'.$product_id,
           'status' => 'required',
        ]);

        $product->name = $request->name;
        $product->unit = $request->unit;
        $product->company_id = $request->company_id;
        $product->slug = Str::slug($request->name);
        $product->status = $request->status;
        $product->save();
        $product['company'] = Company::find($request->company_id)->name;
        return response()->json($product);
    }


}
