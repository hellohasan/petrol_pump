<?php

namespace App\Http\Controllers;

use App\BasicSetting;
use App\Code;
use App\Customer;
use App\Order;
use App\OrderInstalment;
use App\OrderItem;
use App\Product;
use App\TransactionLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class SellController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkInstaller');
        $this->middleware('auth');
        $this->middleware('basicView');
        $this->middleware('dueCount');
    }

    public function newSell()
    {
        /*$dbValue = 9999; echo $dbValue = str_pad($dbValue, 8, "0", STR_PAD_LEFT);
        exit();*/
        $data['page_title'] = 'New Sell';
        $data['customer'] = Customer::latest()->get();
        $data['product'] = Product::whereNotIn('category_id',['1'])->get();
        return view('sell.sell-new',$data);
    }

    public function createCustom()
    {
        $latestOrder = Order::orderBy('created_at','DESC')->first();
        return str_pad($latestOrder ? $latestOrder->id+1 : 1, 8, "0", STR_PAD_LEFT);
    }


    public function submitSell(Request $request)
    {

        try {
            DB::beginTransaction();

            if ($request->customer_type == 0) {
                $request->validate([
                    'name'           => 'required',
                    'email'          => 'nullable|email|unique:customers',
                    'phone'          => 'required|numeric|unique:customers',
                    'address'        => 'required',
                    'rate'           => 'array|required',
                    'rate.*'         => 'required|numeric',
                    'quantity'       => 'array|required',
                    'quantity.*'     => 'required|numeric',
                    'subtotal'       => 'array|required',
                    'subtotal.*'     => 'required|numeric',
                    'discount'       => 'nullable|numeric',
                    'due_pay_amount' => 'required_if:payment_type,1|nullable|numeric',
                ]);
                $in['name'] = $request->name;
                $in['email'] = $request->email;
                $in['phone'] = $request->phone;
                $in['address'] = $request->address;
                $customer = Customer::create($in);
            } else {
                $request->validate([
                    'rate'           => 'array|required',
                    'rate.*'         => 'required|numeric',
                    'quantity'       => 'array|required',
                    'quantity.*'     => 'required|numeric',
                    'subtotal'       => 'array|required',
                    'subtotal.*'     => 'required|numeric',
                    'discount'       => 'nullable|numeric',
                    'due_pay_amount' => 'required_if:payment_type,1|nullable|numeric',
                ]);
                $customer = Customer::findOrFail($request->customer_id);
            }

            if ($customer->id === 1 && $request->input('payment_type') === "1"){
                session()->flash('message','Cant due sell on Bypass Customer.');
                session()->flash('type','warning');
                return redirect()->back();
            }

            if ($request->order_subtotal != null) {
                $arr = [];
                for ($i = 0; $i < count($request->product_id); $i++) {
                    for ($j = 0; $j < 5; $j++) {
                        if ($j == 0) {
                            $arr[$i]['product_id'] = $request->product_id[$i];
                        } elseif ($j == 2) {
                            $arr[$i]['rate'] = $request->rate[$i];
                        } elseif ($j == 3) {
                            $arr[$i]['quantity'] = $request->quantity[$i];
                        } elseif ($j == 4) {
                            $arr[$i]['subtotal'] = $request->subtotal[$i];
                        }
                    }
                }

                $custom = $this->createCustom();

                $or['custom'] = $custom;
                $or['customer_id'] = $customer->id;
                $or['order_total'] = $request->order_total;
                $or['order_subtotal'] = $request->order_subtotal;
                $or['discount'] = $request->discount == null ? '0' : $request->discount;

                if ($request->payment_type == 1) {
                    $or['pay_amount'] = $request->due_pay_amount;
                    $or['due_amount'] = $request->due_due_amount;
                    $or['due_payment_date'] = $request->due_payment_date;
                    $or['status'] = 0;
                } else {
                    $or['pay_amount'] = $request->order_total;
                    $or['status'] = 1;
                }

                $or['payment_type'] = $request->payment_type;
                $or['payment_with'] = $request->payment_with;
                $or['user_id'] = Auth::Id();

                $order = Order::create($or);

                foreach ($arr as $c) {
                    $product = Product::findOrFail($c['product_id']);
                    $orderItem['order_id'] = $order->id;
                    $orderItem['custom'] = $custom;
                    $orderItem['product_id'] = $c['product_id'];
                    $orderItem['company_id'] = $product->company_id;
                    $orderItem['category_id'] = $product->category_id;
                    $orderItem['code'] = $product->code;
                    $orderItem['buy_price'] = $product->buy_price;
                    $orderItem['sell_price'] = $c['rate'];
                    $orderItem['quantity'] = $c['quantity'];
                    $orderItem['subtotal'] = $c['subtotal'];
                    OrderItem::create($orderItem);
                    $product->quantity -= $c['quantity'];
                    $product->save();
                }

                $customer->total_amount += $request->order_total;
                $customer->pay_amount += $or['pay_amount'];
                $customer->save();

                $role = getLoginRole();
                if ($role === 'Seller' || $role === 'Manager') {
                    $this->saveTransactionLog($custom, $or['pay_amount'], false);
                } else {
                    $this->saveTransactionLog($custom, $or['pay_amount'], true);
                }

            } else {
                session()->flash('message', 'Enter Valid Product First.');
                session()->flash('type', 'warning');
                return redirect()->back();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            //$e->getMessage();
            //abort(503);
        }

        session()->flash('message', 'Item Sell Successfully Completed.');
        session()->flash('type', 'success');
        return redirect()->route('sell-invoice', $custom);
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

    public function sellInvoice($invoice)
    {
        $data['page_title'] = 'Sell Invoice';
        $data['sell'] = Order::whereCustom($invoice)->firstOrFail();
        $data['sellItem'] = OrderItem::whereCustom($invoice)->get();
        return view('sell.sell-invoice',$data);
    }
    public function printInvoice($invoice)
    {
        $data['page_title'] = 'Sell Invoice';
        $data['sell'] = Order::whereCustom($invoice)->firstOrFail();
        $data['sellItem'] = OrderItem::whereCustom($invoice)->get();
        return view('sell.invoice-print',$data);
    }
    public function sellHistory()
    {
        $data['page_title'] = 'Sell History';
        if (getLoginRole() === "Manager"){
            $data['sell'] = Order::whereUser_id(Auth::id())->orderBy('id','desc')->get();
        }else{
            $data['sell'] = Order::orderBy('id','desc')->get();
        }
        return view('sell.sell-history',$data);
    }

    public function sellDelete(Request $request)
    {
        $request->validate([
            'delete_id' => 'required'
        ]);

        try {
            DB::beginTransaction();

            $order = Order::findOrFail($request->delete_id);

            $orderItem = OrderItem::whereOrder_id($order->id)->get();

            foreach ($orderItem as $ot) {
                $product = Product::findOrFail($ot->product_id);
                $product->quantity += $ot->quantity;
                $product->save();
                $ot->delete();
            }

            $customer = Customer::findOrFail($order->customer_id);

            $customer->total_amount -= $order->order_total;
            $customer->pay_amount -= $order->pay_amount;
            $customer->save();

            $tran = TransactionLog::whereCustom($order->custom)->first();

            $tr['user_id'] = $tran->user_id;
            $tr['custom'] = $order->custom;
            $tr['type'] = 10; // Sell Product return
            $tr['balance'] = $order->pay_amount;
            $tr['status'] = 1;// David
            if ($tran === 1) {
                $basic = BasicSetting::first();
                $tr['post_balance'] = $basic->balance - $order->pay_amount;
                TransactionLog::create($tr);
                $basic->balance -= $order->pay_amount;
                $basic->save();
            } else {
                $user = User::find($tran->user_id);
                $tr['post_balance'] = $user->balance - $order->pay_amount;
                TransactionLog::create($tr);
                $user->balance -= $order->pay_amount;
                $user->save();
            }

            $order->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            //$e->getMessage();
            abort(503);
        }

        session()->flash('message','Sell Item Deleted Successfully.');
        session()->flash('type','success');
        return redirect()->back();

    }
}
