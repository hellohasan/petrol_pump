<?php

namespace App\Http\Controllers;

use App\Account;
use App\BasicSetting;
use App\TransactionLog;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkInstaller');
        $this->middleware('auth');
        $this->middleware('basicView');
        $this->middleware('dueCount');
    }

    public function mangeAccount()
    {
        $data['page_title'] = 'Manage Account';
        return view('account.manage-account',$data);
    }

    public function submitAccount(Request $request)
    {
        $request->validate([
           'payment_date' => 'required',
           'payment_type' => 'required',
           'pay_amount' => 'required',
           'details' => 'required'
        ]);


        $in = $request->except('_method','_token');

        $basic = BasicSetting::first();

        $date = date('ymdHis');

        try {
            DB::beginTransaction();
            $tr['user_id'] = Auth::id();
            if ($request->payment_type == 0){
                $tr['custom'] = 'DP-'.$date;
                $tr['status'] = 0;
                $tr['type'] = 3; // Deposit
                $tr['balance'] = $request->pay_amount;
                $tr['post_balance'] = $basic->balance + $request->pay_amount;
                TransactionLog::create($tr);
                $basic->balance += $request->pay_amount;
                $basic->save();
            }elseif ($request->payment_type == 1){
                $tr['custom'] = 'WD-'.$date;
                $tr['status'] = 1;
                $tr['type'] = 5; // Withdraw Payment
                $tr['balance'] = $request->pay_amount;
                $tr['post_balance'] = $basic->balance - $request->pay_amount;
                TransactionLog::create($tr);
                $basic->balance -= $request->pay_amount;
                $basic->save();
            }else{
                $role = getLoginRole();
                $custom = 'EX-'.$date;
                $tr['custom'] = $custom;
                if ($role === 'Seller' || $role === 'Manager') {
                    $this->saveTransactionLog($custom,$request->pay_amount, false);
                }else{
                    $this->saveTransactionLog($custom,$request->pay_amount, true);
                }
            }

            $in['custom'] = $tr['custom'];

            Account::create($in);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            //$e->getMessage();
            abort(503);
        }


        session()->flash('message','Payment Successfully Completed.');
        session()->flash('type','success');
        return redirect()->back();
    }

    function saveTransactionLog($custom,$amount, $superAdmin)
    {
        if ($superAdmin){
            $basic = BasicSetting::first();
            $tr['user_id'] = Auth::id();
            $tr['custom'] = $custom;
            $tr['balance'] = $amount;
            $tr['status'] = 1;
            $tr['type'] = 7; // Expense Payment
            $tr['post_balance'] = $basic->balance - $amount;
            TransactionLog::create($tr);
            $basic->balance -= $amount;
            $basic->save();

        }else{
            $user = Auth::user();
            $tr['user_id'] = $user->id;
            $tr['custom'] = $custom;
            $tr['balance'] = $amount;
            $tr['status'] = 1;
            $tr['type'] = 7; // Expense Payment
            $tr['post_balance'] = $user->balance - $amount;
            TransactionLog::create($tr);
            $user->balance -= $amount;
            $user->save();
        }
    }

    public function historyAccount()
    {
        $data['page_title'] = 'Account History';
        $data['history'] = Account::latest()->withTrashed()->get();
        return view('account.account-history',$data);
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
           'delete_id' => 'required'
        ]);

        try {
            DB::beginTransaction();

            $payment = Account::findOrFail($request->delete_id);

            $basic = BasicSetting::first();


            if ($payment->payment_type == 0) {
                $tr['custom'] = $payment->custom;
                $tr['status'] = 1;
                $tr['type'] = 4; // revere Deposit
                $tr['balance'] = $payment->pay_amount;
                $tr['post_balance'] = $basic->balance - $payment->pay_amount;
                TransactionLog::create($tr);
                $basic->balance -= $payment->pay_amount;
                $basic->save();
            } elseif ($payment->payment_type == 1) {
                $tr['custom'] = $payment->custom;
                $tr['status'] = 0;
                $tr['type'] = 6; // reverse Withdraw Payment
                $tr['balance'] = $payment->pay_amount;
                $tr['post_balance'] = $basic->balance + $payment->pay_amount;
                TransactionLog::create($tr);
                $basic->balance += $payment->pay_amount;
                $basic->save();
            } else {

                $tr['user_id'] = $payment->user_id;
                $tr['custom'] = $payment->custom;
                $tr['status'] = 0;
                $tr['type'] = 8; // reverse Expense Payment
                $tr['balance'] = $payment->pay_amount;
                if ($payment->user_id === 1) {
                    $tr['post_balance'] = $basic->balance + $payment->pay_amount;
                    TransactionLog::create($tr);
                    $basic->balance += $payment->pay_amount;
                    $basic->save();
                } else {
                    $user = User::find($payment->user_id);
                    $tr['post_balance'] = $user->balance + $payment->pay_amount;
                    TransactionLog::create($tr);
                    $user->balance += $payment->pay_amount;
                    $user->save();
                }
            }

            $payment->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            //$e->getMessage();
            abort(503);
        }

        session()->flash('message','Payment Successfully Deleted.');
        session()->flash('type','success');
        return redirect()->back();

    }

}
