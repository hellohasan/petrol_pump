<?php

namespace App\Http\Controllers;

use App\BasicSetting;
use App\Receive;
use App\TransactionLog;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ReceiveController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkInstaller');
        $this->middleware('auth');
        $this->middleware('basicView');
        $this->middleware('dueCount');
    }

    public function newReceive()
    {
        $data['page_title'] = "Receive Cash";
        if (getLoginRole() === 'Manager'){
            $data['users'] = User::role('Seller')->get();
        }else{
            $data['users'] = User::role(['Seller','Manager'])->get();
        }
        return view("receive.new", $data);
    }

    public function submitReceive(Request $request)
    {
        $request->validate([
            'payment_date' => 'required',
            'user_id' => 'required',
            'pay_amount' => 'required|numeric'
        ]);

        try {
            DB::beginTransaction();

            if (getLoginRole() === 'Manager') {
                $manager = Auth::user();
                $user = User::find($request->input('user_id'));
                $rc['custom'] = 'RC-' . date('ymdHis');
                $rc['receive_date'] = $request->input('payment_date');
                $rc['user_id'] = $request->input('user_id');
                $rc['old_amount'] = $user->balance;
                $rc['pay_amount'] = $request->pay_amount;
                $rc['details'] = $request->details;
                $rc['receive_by'] = $manager->id;
                Receive::create($rc);

                $this->createTransactionLog($rc['custom'], $user->id, 15, 1, $request->pay_amount,
                    ($user->balance - $request->pay_amount));
                $user->balance -= $request->pay_amount;
                $user->save();
                // user tr

                $this->createTransactionLog($rc['custom'], $manager->id, 16, 0, $request->pay_amount,
                    ($manager->balance + $request->pay_amount));
                $manager->balance += $request->pay_amount;
                $manager->save();
                // manager transfer
            } else {

                $user = User::find($request->input('user_id'));
                $rc['custom'] = 'RC-' . date('ymdHis');
                $rc['receive_date'] = $request->input('payment_date');
                $rc['user_id'] = $request->input('user_id');
                $rc['old_amount'] = $user->balance;
                $rc['pay_amount'] = $request->pay_amount;
                $rc['details'] = $request->details;
                $rc['receive_by'] = Auth::id();
                Receive::create($rc);

                $this->createTransactionLog($rc['custom'], $user->id, 17, 1, $request->pay_amount,
                    ($user->balance - $request->pay_amount));
                $user->balance -= $request->pay_amount;
                $user->save();
                // seller or manager tr

                $basic = BasicSetting::first();
                $this->createTransactionLog($rc['custom'], Auth::id(), 18, 0, $request->pay_amount,
                    ($basic->balance + $request->pay_amount));
                $basic->balance += $request->pay_amount;
                $basic->save();
                // Admin transfer
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            //$e->getMessage();
            abort(503);
        }

        session()->flash('message','Receive Successfully.');
        session()->flash('type','success');
        return redirect()->back();
    }

    public function createTransactionLog($custom,$userId,$type,$status,$amount,$posAmount)
    {
        $tr['custom'] = $custom;
        $tr['user_id'] = $userId;
        $tr['type'] = $type;
        $tr['status'] = $status;
        $tr['balance'] = $amount;
        $tr['post_balance'] = $posAmount;
        TransactionLog::create($tr);
    }

    public function receiveHistory()
    {
        $data['page_title'] = "Receive History";
        $data['history'] = Receive::whereReceive_by(Auth::id())->orderBy('id', 'desc')->get();
        return view("receive.history", $data);
    }

    public function receiveDelete(Request $request)
    {
        $receive = Receive::findOrFail($request->delete_id);
        $user = User::find($receive->user_id);

        try {
            DB::beginTransaction();

            $this->createTransactionLog($receive->custom, $user->id, 19, 0, $receive->pay_amount,
                ($user->balance + $receive->pay_amount));
            $user->balance += $receive->pay_amount;
            $user->save();
            // user tr

            if ($receive->receive_by == 1) {
                $basic = BasicSetting::first();
                $this->createTransactionLog($receive->custom, Auth::id(), 20, 1, $receive->pay_amount,
                    ($basic->balance - $receive->pay_amount));
                $basic->balance -= $receive->pay_amount;
                $basic->save();
            } else {
                $this->createTransactionLog($receive->custom, $user->id, 21, 1, $receive->pay_amount,
                    ($user->balance - $receive->pay_amount));
                $user->balance -= $receive->pay_amount;
                $user->save();
            }

            $receive->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            //$e->getMessage();
            abort(503);
        }

        session()->flash('message','Receive Successfully.');
        session()->flash('type','success');
        return redirect()->back();
    }

    public function checkBalance($amount,$id)
    {
        $user = User::find($id);
        $balance = $user->balance;

        if ($balance < $amount){
            $rr['errorStatus'] = 'yes';
            $rr['errorDetails'] = 'Available balance only '.$balance;
        }else{
            $rr['errorStatus'] = 'no';
            $rr['errorDetails'] = 'You can Process Your Request.';
        }

        return $result = json_encode($rr);

    }
}
