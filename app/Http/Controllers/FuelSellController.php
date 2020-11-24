<?php

namespace App\Http\Controllers;

use App\BasicSetting;
use App\Category;
use App\Customer;
use App\DataTables\OrdersDataTable;
use App\Machine;
use App\Order;
use App\OrderItem;
use App\Product;
use App\TransactionLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class FuelSellController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkInstaller');
        $this->middleware('auth');
        $this->middleware('basicView');
        $this->middleware('dueCount');
    }

    public function sellFuel()
    {
        $data['page_title'] = "Sell Fuel";
        $data['machine'] = Machine::whereActivated(1)->get();
        $data['customer'] = Customer::all();
        return view("fuel.sell", $data);
    }

    public function createCustom()
    {
        $latestOrder = Order::orderBy('created_at','DESC')->first();
        return str_pad($latestOrder ? $latestOrder->id+1 : 1, 8, "0", STR_PAD_LEFT);
    }

    public function submitSellFuel(Request $request)
    {
        try {
            DB::beginTransaction();

            $commonRule = [
                'customer_type' => 'required',
                'machine_id' => 'required|exists:machines,id',
                'quantity' => 'required|numeric',
                'rate' => 'required|numeric',
                'total' => 'required|numeric',
            ];

            $role = getLoginRole();
            if ($role === "Super Admin" || $role === "Manager"){
                $othersRule = [
                    'order_subtotal' => 'required|numeric',
                    'discount' => 'nullable|numeric',
                    'order_total' => 'required|numeric',
                    'payment_type' => 'required',
                    'payment_with' => 'required',
                    'due_pay_amount' => 'required_if:payment_type,1',
                    'due_due_amount' => 'required_if:payment_type,1',
                    'due_payment_date' => 'required_if:payment_type,1',
                ];
                $rule = array_merge($commonRule,$othersRule);
                $request->validate($rule);
                $customer = $this->customerId($request);


                if ($customer->id === 1 && $request->input('payment_type') === "1"){
                    session()->flash('message','Cant due sell on Bypass Customer.');
                    session()->flash('type','warning');
                    return redirect()->back();
                }

                $custom = $this->createCustom();
                $or['custom'] = $custom;
                $or['customer_id'] = $customer->id;
                $or['order_total'] = $request->order_total;
                $or['order_subtotal'] = $request->order_subtotal;
                $or['discount'] = $request->discount == null ? '0' : $request->discount;

                if ($request->payment_type){
                    $or['pay_amount'] = $request->due_pay_amount;
                    $or['due_amount'] = $request->due_due_amount;
                    $or['due_payment_date'] = $request->due_payment_date;
                    $or['status'] = 0;
                }else{
                    $or['pay_amount'] = $request->order_total;
                    $or['status'] = 1;
                }
                $or['payment_type'] = $request->payment_type;
                $or['payment_with'] = $request->payment_with;
                $or['user_id'] = Auth::id();
                $order = Order::create($or);
                $machine = Machine::findOrFail($request->machine_id);

                $product = $machine->product;
                $orderItem['order_id'] = $order->id;
                $orderItem['custom'] = $custom;
                $orderItem['product_id'] = $product->id;
                $orderItem['company_id'] = $product->company_id;
                $orderItem['category_id'] = $product->category_id;
                $orderItem['code'] = $product->code;
                $orderItem['buy_price'] = $product->buy_price;
                $orderItem['sell_price'] = $request->input('rate');
                $orderItem['quantity'] = $request->input('quantity');
                $orderItem['subtotal'] = $request->input('order_subtotal');
                OrderItem::create($orderItem);
                $product->quantity -= $request->input('quantity');
                $product->save();
                $machine->current_reading += $request->input('quantity');
                $machine->save();

                $customer->total_amount += $request->order_total;
                $customer->pay_amount += $or['pay_amount'];
                $customer->save();

                $role = getLoginRole();
                if ($role === 'Seller' || $role === 'Manager') {
                    $this->saveTransactionLog($custom,$or['pay_amount'], false);
                }else{
                    $this->saveTransactionLog($custom,$or['pay_amount'], true);
                }

            }else {
                $request->validate($commonRule);
                $customer = $this->customerId($request);
                $custom = $this->createCustom();
                $or['custom'] = $custom;
                $or['customer_id'] = $customer->id;
                $or['order_total'] = $request->total;
                $or['order_subtotal'] = $request->total;
                $or['discount'] = 0;
                $or['pay_amount'] = $request->total;
                $or['status'] = 1;

                $or['payment_type'] = 0;
                $or['payment_with'] = 0;
                $or['user_id'] = Auth::id();

                $order = Order::create($or);
                $machine = Machine::findOrFail($request->machine_id);
                $product = $machine->product;
                $orderItem['order_id'] = $order->id;
                $orderItem['custom'] = $custom;
                $orderItem['product_id'] = $product->id;
                $orderItem['company_id'] = $product->company_id;
                $orderItem['category_id'] = $product->category_id;
                $orderItem['code'] = $product->code;
                $orderItem['buy_price'] = $product->buy_price;
                $orderItem['sell_price'] = $request->input('rate');
                $orderItem['quantity'] = $request->input('quantity');
                $orderItem['subtotal'] = $request->input('total');
                OrderItem::create($orderItem);

                $product->quantity -= $request->input('quantity');
                $product->save();
                $machine->current_reading += $request->input('quantity');
                $machine->save();

                $customer->total_amount += $request->input('total');
                $customer->pay_amount += $or['pay_amount'];
                $customer->save();

                $role = getLoginRole();
                if ($role === 'Seller' || $role === 'Manager') {
                    $this->saveTransactionLog($custom,$or['pay_amount'], false);
                }else{
                    $this->saveTransactionLog($custom,$or['pay_amount'], true);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            //$e->getMessage();
            abort(503);
        }

        session()->flash('message','Sell Successfully Completed.');
        session()->flash('type','success');
        session()->flash('fuelSellUrl',true);
        if (getLoginRole() === 'Seller'){
            return redirect()->back();
        }
        return redirect()->route('sell-invoice',$custom);
    }

    function saveTransactionLog($custom,$amount, $superAdmin)
    {
        if ($superAdmin){
            $basic = BasicSetting::first();
            $tr['user_id'] = Auth::id();
            $tr['custom'] = $custom;
            $tr['type'] = 9; // Sell Product
            $tr['balance'] = $amount;
            $tr['status'] = 0;// David
            $tr['post_balance'] = $basic->balance + $amount;
            TransactionLog::create($tr);
            $basic->balance += $amount;
            $basic->save();

        }else{
            $user = Auth::user();
            $tr['user_id'] = $user->id;
            $tr['custom'] = $custom;
            $tr['type'] = 9; // Sell Product
            $tr['balance'] = $amount;
            $tr['status'] = 0;// David
            $tr['post_balance'] = $user->balance + $amount;
            TransactionLog::create($tr);
            $user->balance += $amount;
            $user->save();
        }
    }

    function customerId($re)
    {
        $type = $re->input('customer_type');
        if ($type === '2'){
            return Customer::find(1);
        }elseif ($type === '1'){
            return Customer::find($re->input('customer_id'));
        }else{
            $in['name'] = $re->name;
            $in['email'] = $re->email;
            $in['phone'] = $re->phone;
            $in['address'] = $re->address;
            return Customer::create($in);
        }
    }

    public function sellHistory(Request $request)
    {
        $data['page_title'] = "Sell History";
        if ($request->ajax()) {

            $orders = Order::orderBy('id','desc');

            $role = getLoginRole();
            if ($role == 'Seller' || $role == 'Manager'){
                $orders->whereUser_id(Auth::id());
            }

            $data = $orders->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('user_id', function ($p){
                    return $p->user->name.'<br>'.'<div class="badge badge-primary font-weight-bold text-uppercase">'.$p->user->getRoleNames()[0].'</div>';
                })
                ->editColumn('created_at', function ($p){
                    return Carbon::parse($p->created_at)->format('d-m-y h:i A');
                })
                ->editColumn('customer_id', function ($p){
                    return $p->customer->name.'<br>'.$p->customer->phone;
                })
                ->editColumn('payment_type', function ($p){
                    if ($p->payment_type){
                        return $p->due_amount;
                    }else{
                        return '<div class="badge badge-primary font-weight-bold text-uppercase">Paid</div>';
                    }
                })
                ->addColumn('action', function($p){
                    $disable = $p->deleted_at !== null ? "disabled" : "";

                    if (getLoginRole() === 'Seller'){
                        return '<a href="'.route("sell-invoice",$p->custom).'" class="btn btn-primary btn-sm  font-weight-bold text-uppercase" title="View"><i class="fa fa-eye"></i> View</a>';
                    }
                    return '<div class="btn-group" role="group"><button id="btnGroupDrop1" type="button" class="btn btn-secondary btn-sm dropdown-toggle font-weight-bold text-uppercase" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button><div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                      <a class="dropdown-item font-weight-bold text-uppercase" href="'.route("sell-invoice",$p->custom).'"><i class="fa fa-eye"></i> Invoice</a>
                      <button class="dropdown-item font-weight-bold text-uppercase cursor-pointer" data-toggle="modal" '.$disable.' data-target="#DelModal" data-id="'.$p->id .'"><i class="fa fa-trash"></i> Delete</button>
                    </div>
                    </div>';
                })
                ->rawColumns(['action','created_at','customer_id','payment_type','user_id'])
                ->make(true);
        }
        return view('fuel.history',$data);
    }

    public function checkQuantity($machine,$qty)
    {
        $machine = Machine::find($machine);
        $product = $machine->product;
        if ($product->quantity < $qty){
            $rr['errorStatus'] = 'yes';
            $rr['errorDetails'] = 'Can\'t Sell available only - '.$product->quantity.' '.$product->category->unit;
        }else{
            $rr['errorStatus'] = 'no';
        }
        return json_encode($rr);
    }

}
