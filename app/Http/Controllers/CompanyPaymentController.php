<?php

namespace App\Http\Controllers;

use App\BasicSetting;
use App\Company;
use App\CompanyPayment;
use App\TransactionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CompanyPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkInstaller');
        $this->middleware('auth');
        $this->middleware('basicView');
        $this->middleware('dueCount');
    }

    public function newPayment()
    {
        $data['page_title'] = 'New Payment';
        $data['company'] = Company::all();
        return view('company.payment-new',$data);
    }

    public function storePayment(Request $request)
    {

        $request->validate([
            'payment_date' => 'required',
            'company_id' => 'required',
            'amount' => 'required|numeric',
            'details' => 'required'
        ]);

        $basic = BasicSetting::first();
        $custom = 'CP-'.date('ymdHis');

        $in = $request->except('_method','_token');
        $in['custom'] = $custom;
        $in['payment_type'] = 1;
        CompanyPayment::create($in);

        $com = Company::findOrFail($request->company_id);

        $com->total_pay += $request->amount;
        $com->save();

        $tr['custom'] = $custom;
        $tr['type'] = 1; // Company Payment
        $tr['balance'] = $request->amount;
        $tr['status'] = 1;// Credit
        $tr['post_balance'] = $basic->balance - $request->amount;

        TransactionLog::create($tr);

        $basic->balance -= $request->amount;
        $basic->save();

        session()->flash('message','Payment Successfully Completed.');
        session()->flash('type','success');
        return redirect()->back();
    }

    public function historyPayment()
    {
        $data['page_title'] = 'Company Payment History';
        $data['history'] = CompanyPayment::latest()->get();
        return view('company.payment-history',$data);
    }

    public function deletePayment(Request $request)
    {
        $request->validate([
           'delete_id' => 'required'
        ]);

        $payment = CompanyPayment::findOrFail($request->delete_id);

        $custom = $payment->custom;

        $com = Company::findOrFail($payment->company_id);

        if($payment->payment_type == 0){
            $com->total_send -= $payment->amount;
            $com->save();
        }else{
            $com->total_pay -= $payment->amount;
            $com->save();

            $basic = BasicSetting::first();

            $tr['custom'] = $custom;
            $tr['type'] = 2; // Company Payment Reverse
            $tr['balance'] = $payment->amount;
            $tr['status'] = 0;// David
            $tr['post_balance'] = $basic->balance + $payment->amount;

            TransactionLog::create($tr);

            $basic->balance += $payment->amount;
            $basic->save();
        }

        $payment->delete();

        session()->flash('message','Payment Successfully Deleted.');
        session()->flash('type','success');
        return redirect()->back();

    }

    public function getCompanySend()
    {
        $data['page_title'] = 'Company Send';
        $data['company'] = Company::all();
        return view('company.company-send',$data);
    }

    public function submitCompanySend(Request $request)
    {
        $request->validate([
            'payment_date' => 'required',
            'company_id' => 'required',
            'amount' => 'required|numeric',
            'details' => 'required',
            'reference' => 'required'
        ]);

        $in = $request->except('_method','_token');
        $in['custom'] = 'CS-'.date('ymdHis');
        $in['payment_type'] = 0;
        $company = Company::findOrFail($request->company_id);
        $company->total_send += $request->amount;
        $company->save();

        CompanyPayment::create($in);

        session()->flash('message','Company Send Amount Saved.');
        session()->flash('type','success');
        return redirect()->back();

    }

}
