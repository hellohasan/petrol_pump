<?php

namespace App\Http\Controllers;

use App\BasicSetting;
use App\Category;
use App\Code;
use App\Company;
use App\Product;
use App\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkInstaller');
        $this->middleware('auth');
        $this->middleware('basicView');
        $this->middleware('dueCount');
    }

    public function newStore()
    {
        $data['page_title'] = 'New Store';
        $data['company'] = Company::all();
        return view('store.store-new',$data);
    }

    public function submitStore(Request $request)
    {
        $request->validate([
           'store_date' => 'required',
           'reference' => 'required',
           'company_id' => 'required',
           'category_id' => 'required',
           'product_id' => 'required',
           'quantity' => 'required|numeric',
            'buy_price' => 'required|numeric',
            'sell_price' => 'required|numeric',
        ]);

        $in = $request->except('_method','_token','codes');

        Store::create($in);

        $product = Product::findOrFail($request->product_id);
        $product->quantity += $request->quantity;
        $product->sell_price = $request->sell_price;
        $product->buy_price = $request->buy_price;

        $product->save();

        session()->flash('message','Store Successfully Completed.');
        session()->flash('type','success');
        return redirect()->back();

    }

    public function storeHistory()
    {
        $data['page_title'] = 'Store History';
        $data['history'] = Store::latest()->get();
        return view('store.store-history',$data);
    }

    public function viewHistory($id)
    {
        $data['page_title'] = 'View Store';
        $data['store'] = Store::findOrFail($id);
        return view('store.store-view',$data);
    }

    public function editHistory($id)
    {
        $data['page_title'] = 'Edit History';
        $data['store'] = Store::findOrFail($id);
        $data['company'] = Company::all();
        $data['product'] = Product::whereCompany_id($data['store']->company_id)->get();
        $data['category'] = Category::whereCompany_id($data['store']->category_id)->get();
        return view('store.store-edit',$data);
    }

    public function deleteStore(Request $request)
    {

        $request->validate([
           'delete_id' => 'required'
        ]);
        $store = Store::findOrFail($request->delete_id);
        $product = Product::findOrFail($store->product_id);
        $product->quantity -= $store->quantity;
        $product->save();
        $store->delete();

        session()->flash('message','Store Deleted Successfully.');
        session()->flash('type','success');
        return redirect()->back();

    }


    public function currentStore()
    {
        $data['page_title'] = 'Current Store';
        $data['company'] = Company::all();
        $data['product'] = [];
        $data['top'] = 0;
        return view('store.store-current',$data);
    }

    public function searchCurrentStore(Request $request)
    {
        $data['page_title'] = 'Search Store Result';

        $data['company'] = Company::all();
        $data['category'] = Category::whereCompany_id($request->company_id)->get();
        $data['products'] = Product::whereCompany_id($request->company_id)->get();

        $data['company_id'] = $request->company_id;
        $data['category_id'] = $request->category_id;
        $data['product_id'] = $request->product_id;

        $data['top'] = 1;

        if ($request->category_id == 0){
            $data['product'] = Product::whereCompany_id($request->company_id)->get();
        }else{
            if ($request->product_id == 0){
                $data['product'] = Product::whereCompany_id($request->company_id)
                    ->whereCategory_id($request->category_id)
                    ->get();
            }else{
                $data['product'] = Product::whereCompany_id($request->company_id)
                    ->whereCategory_id($request->category_id)
                    ->whereId($request->product_id)
                    ->get();
            }
        }
        return view('store.store-current',$data);

    }

    public function searchViewResult($id)
    {
        $data['page_title'] = 'View Search Result';
        $data['product'] = Product::findOrFail($id);
        $data['productCode'] = Code::whereProduct_id($id)->orderBy('id','desc')->get();
        return view('store.store-search-view',$data);
    }


}
