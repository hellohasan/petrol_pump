<?php

namespace App\Http\Controllers;

use App\BasicSetting;
use App\Customer;
use App\Order;
use App\OrderInstalment;
use App\Repayment;
use App\TransactionLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class RepaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkInstaller');
        $this->middleware('auth');
        $this->middleware('basicView');
        $this->middleware('dueCount');
    }

    public function dueSellList()
    {
        $data['page_title'] = 'Due Sell List';
        $data['sell'] = Order::whereStatus(0)->latest()->get();
        return view('repayment.due-payment-list',$data);
    }

    public function newDueRepayment()
    {
        $data['page_title'] = 'Due RePayment';
        $data['customer'] = Customer::latest()->get();
        return view('repayment.due-payment-new',$data);
    }
    public function getCustomerDue()
    {
        $customer_id = $request->get('customer_id');
        $order = Order::whereCustomer_id($customer_id)->whereStatus(2)->get();
        return Response::json($order);
    }
    public function getOrderDetails($id)
    {
        $order = Order::findOrFail($id);
        return Response::json($order);
    }

    public function getOrderDue(Request $request)
    {
        $id = $request->get('list_id');
        $order = Order::findOrFail($id);
        return Response::json($order);
    }

    public function getCustomerDueOrder(Request $request)
    {
        $id = $request->get('list_id');
        $order = Order::whereCustomer_id($id)->whereStatus(0)->get();
        return Response::json($order);
    }

    public function submitDueRepayment(Request $request)
    {
        $request->validate([
           'order_id' => 'required',
           'pay_amount' => 'required|numeric',
            'payment_status' => 'required'
        ]);


        try {
            DB::beginTransaction();

            $order = Order::findOrFail($request->order_id);
            $order->pay_amount += $request->pay_amount;
            $custom = 'DU-' . date('ymdHis');

            $re['custom'] = $custom;
            $re['user_id'] = Auth::id();
            $re['ref_custom'] = $order->custom;
            $re['type'] = 1;// Due Repayment
            $re['payment_date'] = $request->payment_date;
            $re['customer_id'] = $order->customer_id;
            $re['order_id'] = $order->id;
            $re['pay_amount'] = $request->pay_amount;
            $re['payment_status'] = $request->payment_status;

            $post_due = $order->due_amount - $request->pay_amount;

            if ($request->payment_status == 0) {
                $order->due_amount -= $request->pay_amount;
                $order->due_payment_date = $request->due_payment_date;
                $re['post_due'] = $post_due;
            } else {
                $order->due_amount -= $request->pay_amount;
                $order->status = 1;
                $re['post_due'] = $post_due;
            }
            Repayment::create($re);

            $customer = Customer::findOrFail($order->customer_id);
            $customer->pay_amount += $request->pay_amount;
            $customer->save();

            if (getLoginRole() === 'Manager') {
                $user = Auth::user();
                $tr['custom'] = $custom;
                $tr['user_id'] = $user->id;
                $tr['type'] = 11; // Due Re Payment
                $tr['balance'] = $request->pay_amount;
                $tr['status'] = 0;// Davit
                $tr['post_balance'] = $user->balance + $request->pay_amount;
                TransactionLog::create($tr);
                $user->balance += $request->pay_amount;
                $user->save();
            } else {
                $basic = BasicSetting::first();
                $tr['custom'] = $custom;
                $tr['type'] = 11; // Due Re Payment
                $tr['balance'] = $request->pay_amount;
                $tr['status'] = 0;// Davit
                $tr['post_balance'] = $basic->balance + $request->pay_amount;
                TransactionLog::create($tr);
                $basic->balance += $request->pay_amount;
                $basic->save();
            }

            $order->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            //$e->getMessage();
            abort(503);
        }

        session()->flash('message','Repayment Successfully Completed');
        session()->flash('type','success');
        return redirect()->route('sell-invoice',$order->custom);
    }

    public function historyDueRepayment()
    {
        $data['page_title'] = 'Due Repayment History';
        $data['history'] = Repayment::whereType(1)->latest()->get();
        return view('repayment.due-repayment-history',$data);
    }

    public function deleteDueRepayment(Request $request)
    {
        $request->validate([
           'delete_id' => 'required'
        ]);

        try {
            DB::beginTransaction();

            $repayment = Repayment::findOrFail($request->delete_id);

            $order = Order::findOrFail($repayment->order_id);

            $order->pay_amount -= $repayment->pay_amount;
            $order->due_amount += $repayment->pay_amount;
            $order->status = 0;

            $order->save();

            $customer = Customer::findOrFail($order->customer_id);
            $customer->pay_amount -= $repayment->pay_amount;
            $customer->save();

            if (getLoginRole() === 'Manager') {
                $user = User::find($repayment->user_id);
                $tr['custom'] = $repayment->custom;
                $tr['user_id'] = $user->id;
                $tr['type'] = 12; // Due Re Payment
                $tr['balance'] = $repayment->pay_amount;
                $tr['status'] = 1;// Davit
                $tr['post_balance'] = $user->balance - $repayment->pay_amount;
                TransactionLog::create($tr);
                $user->balance -= $repayment->pay_amount;
                $user->save();
            } else {
                $basic = BasicSetting::first();
                $tr['custom'] = $repayment->custom;
                $tr['type'] = 12; // Due Re Payment
                $tr['balance'] = $repayment->pay_amount;
                $tr['status'] = 1;// Davit
                $tr['post_balance'] = $basic->balance - $repayment->pay_amount;
                TransactionLog::create($tr);
                $basic->balance -= $repayment->pay_amount;
                $basic->save();
            }

            $repayment->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            //$e->getMessage();
            abort(503);
        }

        session()->flash('message','Repayment Deleted Successfully.');
        session()->flash('type','success');
        return redirect()->back();

    }

    public function upcomingDueRepayment()
    {
        $data['page_title'] = 'Upcoming Due Repayment';

        $start = Carbon::parse()->addDays('7')->format('Y-m-d');
        $end = '2018-00-00';
        $data['sell'] = Order::whereStatus(0)->whereBetween('due_payment_date',[$end,$start])->orderBy('due_payment_date','asc')->get();
        return view('repayment.due-payment-list',$data);
    }

    public function repaymentSearch(Request $request)
    {
        $date = $request->get('repayment_date');
        $start = explode(' / ',$date);
        $data['page_title'] = 'Upcoming Due Repayment Search';
        $data['date'] = $start;
        $data['history'] = Order::whereStatus(2)->whereBetween('due_payment_date',[$start[0],$start[1]])->get();
        return view('repayment.due-upcoming-repayment',$data);
    }

    public function instalmentRepayment()
    {
        $data['page_title'] = 'Instalment Repayment';
        $data['customer'] = Customer::all();
        return view('repayment.instalment-repayment',$data);
    }

    public function CheckCustomerInstalment()
    {
        $customer_id = $request->get('customer_id');
        $instalment = OrderInstalment::whereCustomer_id($customer_id)->whereStatus(0)->get();
        return Response::json($instalment);
    }

    public function instalmentRepaymentList()
    {
        $custom = $request->get('custom');
        $data['page_title'] = $custom.' - Repayment List';
        $order = OrderInstalment::whereCustom($custom)->firstOrFail();
        $data['list'] = InstalmentTime::whereOrder_instalment_id($order->id)->get();
        return view('repayment.instalment-repayment-list',$data);
    }

    public function getInstalmentDetails($id)
    {
        $res = InstalmentTime::findOrFail($id);
        $res['custom'] = $res->instalment->custom;
        return Response::json($res);
    }

    public function submitInstalmentRepayment(Request $request)
    {

        $instalment = InstalmentTime::findOrFail($request->instalmentTime_id);

        $custom = 'IN-'.date('ymdHis');

        $re['custom'] = $custom;
        $re['ref_custom'] = $instalment->instalment->custom;
        $re['type'] = 2;// Instalment Repayment
        $re['payment_date'] = $request->payment_date;
        $re['customer_id'] = $instalment->instalment->customer_id;
        $re['order_id'] = $instalment->instalment->order_id;
        $re['pay_amount'] = $request->pay_amount;
        $re['post_due'] = $instalment->amount;
        $re['time_id'] = $request->instalmentTime_id;

        Repayment::create($re);

        $instalment->pay_amount = $request->pay_amount;
        $instalment->status = 1;

        $extra_amount = $instalment->amount - $instalment->pay_amount;


        $checkLast = InstalmentTime::whereOrder_instalment_id($request->orderInstalment_id)->whereStatus(0)->count();

        if($checkLast == 1){
            $mainInstalment = OrderInstalment::findOrFail($request->orderInstalment_id);
            $order = Order::findOrFail($mainInstalment->order_id);
            $order->status = 1;
            $order->save();
            $mainInstalment->status = 1;
            $mainInstalment->save();
        }else{
            $nextInstalment = InstalmentTime::whereNotIn('id',[$request->instalmentTime_id])->whereOrder_instalment_id($request->orderInstalment_id)->whereStatus(0)->first();
            $nextInstalment->amount += $extra_amount;
            $nextInstalment->save();
        }


        $instalment->save();

        $basic = BasicSetting::first();

        $tr['custom'] = $custom;
        $tr['type'] = 13; // Instalment Payment
        $tr['balance'] = $request->pay_amount;
        $tr['status'] = 0;// Davit
        $tr['post_balance'] = $basic->balance + $request->pay_amount;


        TransactionLog::create($tr);

        $basic->balance += $request->pay_amount;

        $basic->save();

        session()->flash('message','Repayment Successfully Completed');
        session()->flash('type','success');
        return redirect()->back();

    }

    public function instalmentRepaymentHistory()
    {
        $data['page_title'] = 'Instalment Repayment History';
        $data['history'] = Repayment::whereType(2)->latest()->get();
        return view('repayment.instalment-repayment-history',$data);
    }

    public function deleteInstalmentRepayment(Request $request)
    {
        $request->validate([
            'delete_id' => 'required'
        ]);

        $repayment = Repayment::findOrFail($request->delete_id);

        $time = InstalmentTime::findOrFail($repayment->time_id);

        $orderInstalment = OrderInstalment::findOrFail($time->order_instalment_id);
        $orderInstalment->status = 0;
        $orderInstalment->save();

        $order = Order::findOrFail($orderInstalment->order_id);
        $order->status = 3;
        $order->save();

        $extra_amount = $time->amount - $time->pay_amount;

        $time->pay_amount = 0;
        $time->status = 0;
        $time->save();


        $checkLast = InstalmentTime::whereOrder_instalment_id($time->order_instalment_id)->count();


        if($checkLast > 1){
            $nextInstalment = InstalmentTime::whereNotIn('id',[$time->id])->whereOrder_instalment_id($time->order_instalment_id)->first();
            $nextInstalment->amount -= $extra_amount;
            $nextInstalment->save();
        }


        $basic = BasicSetting::first();

        $tr['custom'] = $repayment->custom;
        $tr['type'] = 14; // Instalment Re Payment
        $tr['balance'] = $repayment->pay_amount;
        $tr['status'] = 1;// Credit
        $tr['post_balance'] = $basic->balance - $repayment->pay_amount;


        TransactionLog::create($tr);

        $basic->balance -= $repayment->pay_amount;

        $basic->save();

        $repayment->delete();

        session()->flash('message','Repayment Successfully Deleted');
        session()->flash('type','success');
        return redirect()->back();

    }

    public function upcomingInstalmentRepayment()
    {
        $data['page_title'] = 'Upcoming Instalment Repayment';
        $today = Carbon::now()->format('Y-m-d').' 00:00:00';
        $today1 = Carbon::now()->format('Y-m-d');
        $next = Carbon::parse()->addMonth()->format('Y-m-d').' 23:59:59';
        $next1 = Carbon::parse()->addMonth()->format('Y-m-d');
        $date[0] = $today1;
        $date[1] = $next1;
        $data['date'] = $date;
        $data['history'] = InstalmentTime::whereStatus(0)->whereBetween('pay_date',[$today,$next])->get();
        return view('repayment.instalment-upcoming-repayment',$data);
    }

    public function searchInstalmentRepayment()
    {
        $date = $request->get('repayment_date');
        $start = explode(' / ',$date);
        $data['page_title'] = 'Upcoming Due Repayment Search';
        $data['date'] = $start;
        $start1 = $start[0].' 00:00:00';
        $end1 = $start[1].' 23:59:59';
        $data['history'] = InstalmentTime::whereStatus(0)->whereBetween('pay_date',[$start1,$end1])->get();
        return view('repayment.instalment-upcoming-repayment',$data);
    }


}
