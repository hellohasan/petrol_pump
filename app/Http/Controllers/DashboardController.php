<?php

namespace App\Http\Controllers;

use App\Account;
use App\BasicSetting;
use App\Category;
use App\Company;
use App\CompanyPayment;
use App\Order;
use App\Product;
use App\TraitsFolder\CommonTrait;
use App\TransactionLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    use CommonTrait;
    public function __construct()
    {
        $this->middleware('checkInstaller');
        $this->middleware('auth');
        $this->middleware('basicView');
        $this->middleware('dueCount');
    }

    public function getDashboard()
    {

        $data['page_title'] = "Dashboard";

        $data['total_deposit'] = Account::wherePayment_type(0)->sum('pay_amount');
        $data['total_withdraw'] = Account::wherePayment_type(1)->sum('pay_amount');
        $data['total_expense'] = Account::wherePayment_type(2)->sum('pay_amount');

        $data['categoryCount'] = Category::all()->count();
        $data['productCount'] = Product::all()->count();
        $data['stockCount'] = Product::all()->sum('quantity');
        $data['stock_amount'] = DB::table('products')
            ->sum(DB::raw('(buy_price * quantity)'));


        $data['total_sell'] = Order::all()->count();
        $data['sell_amount'] = Order::all()->sum('order_total');
        $data['discount'] = Order::all()->sum('discount');
        $data['due_on'] = Order::whereStatus(0)->sum('due_amount');

        $data['companyCount'] = Company::all()->count();
        $data['company_send'] = Company::sum('total_send');
        $data['company_pay'] = Company::sum('total_pay');

        return view('dashboard.dashboard',$data);
    }

    public function getTransactionLog(Request $request)
    {
        $data['page_title'] = 'Transaction Log';
        if ($request->ajax()) {
            $data = TransactionLog::orderBy('id','desc')->where('user_id',Auth::id())->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($p){
                    return Carbon::parse($p->created_at)->format('d-m-y h:i A');
                })
                ->editColumn('type', function ($p){
                    if($p->type == 1){
                        return '<div class="badge badge-primary font-weight-bold text-uppercase">
                                                    <i class="ft ft-briefcase font-medium-2"></i>
                                                    <span>Company Payment</span>
                                                </div>';
                    }elseif($p->type == 2){
                        return '<div class="badge badge-warning font-weight-bold text-uppercase">
                                                    <i class="ft ft-briefcase font-medium-2"></i>
                                                    <span>Re Company Payment</span>
                                                </div>';
                    }elseif($p->type == 3) {
                        return '<div class="badge badge-success font-weight-bold text-uppercase" >
                                                    <i class="ft ft-download-cloud font-medium-2" ></i >
                                                    <span > Deposit Amount </span >
                                                </div >';
                    }elseif($p->type == 4){
                        return '<div class="badge badge-warning font-weight-bold text-uppercase">
                                                    <i class="ft ft-download-cloud font-medium-2"></i>
                                                    <span>Re Deposit Amount</span>
                                                </div>';
                    }elseif($p->type == 5){
                        return '<div class="badge badge-warning font-weight-bold text-uppercase">
                                                    <i class="ft ft-upload-cloud font-medium-2"></i>
                                                    <span>Withdraw Amount</span>
                                                </div>';
                    }elseif($p->type == 6){
                        return '<div class="badge badge-success font-weight-bold text-uppercase">
                                                    <i class="ft ft-upload-cloud font-medium-2"></i>
                                                    <span>Re Withdraw Amount</span>
                                                </div>';
                    } elseif($p->type == 7){
                        return '<div class="badge badge-success font-weight-bold text-uppercase">
                                                    <i class="ft ft-cloud-lightning font-medium-2"></i>
                                                    <span>Expense Amount</span>
                                                </div>';
                    } elseif($p->type == 8){
                        return '<div class="badge badge-warning font-weight-bold text-uppercase">
                                                    <i class="ft ft-cloud-lightning font-medium-2"></i>
                                                    <span>Re Expense Amount</span>
                                                </div>';
                    } elseif($p->type == 9){
                        return '<div class="badge badge-success font-weight-bold text-uppercase">
                                                    <i class="ft ft-shopping-cart font-medium-2"></i>
                                                    <span>Sell Amount</span>
                                                </div>';
                    }elseif($p->type == 10){
                        return '<div class="badge badge-warning font-weight-bold text-uppercase">
                                                    <i class="ft ft-shopping-cart font-medium-2"></i>
                                                    <span>Re Sell Amount</span>
                                                </div>';
                    } elseif($p->type == 11){
                        return '<div class="badge badge-primary font-weight-bold text-uppercase">
                                                    <i class="ft ft-copy font-medium-2"></i>
                                                    <span>Due Repayment</span>
                                                </div>';
                    } elseif($p->type == 12){
                        return '<div class="badge badge-warning font-weight-bold text-uppercase">
                                                    <i class="ft ft-copy font-medium-2"></i>
                                                    <span>Re Due Repayment</span>
                                                </div>';
                    } elseif($p->type == 13){
                        return '<div class="badge badge-primary font-weight-bold text-uppercase">
                                                    <i class="ft ft-sliders font-medium-2"></i>
                                                    <span>Instalment Repayment</span>
                                                </div>';
                    }elseif($p->type == 14){
                        return '<div class="badge badge-warning font-weight-bold text-uppercase">
                                                    <i class="ft ft-sliders font-medium-2"></i>
                                                    <span>Re Instalment Repayment</span>
                                                </div>';
                    }elseif($p->type == 15){
                        return '<div class="badge badge-success font-weight-bold text-uppercase">
                                                    <i class="ft ft-sliders font-medium-2"></i>
                                                    <span>Cash Submitted</span>
                                                </div>';
                    }elseif($p->type == 16){
                        return '<div class="badge badge-success font-weight-bold text-uppercase">
                                                    <i class="ft ft-sliders font-medium-2"></i>
                                                    <span>Cash Received</span>
                                                </div>';
                    }elseif($p->type == 17){
                        return '<div class="badge badge-success font-weight-bold text-uppercase">
                                                    <i class="ft ft-sliders font-medium-2"></i>
                                                    <span>Submitted Cash</span>
                                                </div>';
                    }elseif($p->type == 18){
                        return '<div class="badge badge-success font-weight-bold text-uppercase">
                                                    <i class="ft ft-sliders font-medium-2"></i>
                                                    <span>Received Cash</span>
                                                </div>';
                    }elseif($p->type == 19){
                        return '<div class="badge badge-warning font-weight-bold text-uppercase">
                                                    <i class="ft ft-sliders font-medium-2"></i>
                                                    <span>Re Submitted Cash</span>
                                                </div>';
                    }elseif($p->type == 20){
                        return '<div class="badge badge-warning font-weight-bold text-uppercase">
                                                    <i class="ft ft-sliders font-medium-2"></i>
                                                    <span>Re Received Cash</span>
                                                </div>';
                    }elseif($p->type == 21){
                        return '<div class="badge badge-warning font-weight-bold text-uppercase">
                                                    <i class="ft ft-sliders font-medium-2"></i>
                                                    <span>Re Received Cash</span>
                                                </div>';
                    }
                })
                ->rawColumns(['type','created_at'])
                ->make(true);
        }

        return view('dashboard.transaction-log',$data);

    }

    public function paginate($items, $perPage = null, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, ['path' => Paginator::resolveCurrentPath()]);
    }

    public function sellList($date)
    {
        $data['page_title'] = $date.' - Sell List';
        $role = getLoginRole();
        if ($role === "Seller" || $role === "Manager"){
            $data['sell'] = Order::where('user_id',Auth::id())->where('created_at','like',$date.'%')->latest()->get();
        }else{
            $data['sell'] = Order::where('created_at','like',$date.'%')->latest()->get();
        }
        return view('sell.sell-history',$data);
    }

    public function dailyStatistic()
    {
        $data['page_title'] = 'Daily Statistic';

        $role = getLoginRole();
        if ($role === "Seller" || $role === "Manager"){
            $orders = Order::where('user_id',Auth::id())->get();
        }else{
            $orders = Order::get();
        }

        $data['items'] = $orders->groupBy(function($d) {
            return Carbon::parse($d->created_at)->format('Y-m-d');
        })->reduce(function ($result,$group){
            return $result->put(Carbon::parse($group->first()->created_at)->format('Y-m-d'), collect([
                'total_order' => $group->sum('order_total'),
                'total_pay' => $group->sum('pay_amount'),
                'total_due' => $group->sum('due_amount'),
                'total_invoice' => $group->count(),
            ]));
        }, collect());

        $data['items'] = $this->paginate($data['items'],10);

        return view('dashboard.daily-statistic',$data);

    }

    public function monthlyStatistic()
    {
        $data['page_title'] = 'Monthly Statistic';

        $role = getLoginRole();
        if ($role === "Seller" || $role === "Manager"){
            $orders = Order::where('user_id',Auth::id())->get();
        }else{
            $orders = Order::get();
        }

        $data['items'] = $orders->groupBy(function($d) {
            return Carbon::parse($d->created_at)->format('Y-m');
        })->reduce(function ($result,$group){
            return $result->put(Carbon::parse($group->first()->created_at)->format('Y-m'), collect([
                'total_order' => $group->sum('order_total'),
                'total_pay' => $group->sum('pay_amount'),
                'total_due' => $group->sum('due_amount'),
                'total_invoice' => $group->count(),
            ]));
        }, collect());

        $data['items'] = $this->paginate($data['items'],10);

        return view('dashboard.month-statistic',$data);
    }
    public function yearlyStatistic()
    {
        $data['page_title'] = 'Yearly Statistic';

        $role = getLoginRole();
        if ($role === "Seller" || $role === "Manager"){
            $orders = Order::where('user_id',Auth::id())->get();
        }else{
            $orders = Order::get();
        }

        $data['items'] = $orders->groupBy(function($d) {
            return Carbon::parse($d->created_at)->format('Y');
        })->reduce(function ($result,$group){
            return $result->put(Carbon::parse($group->first()->created_at)->format('Y'), collect([
                'total_order' => $group->sum('order_total'),
                'total_pay' => $group->sum('pay_amount'),
                'total_due' => $group->sum('due_amount'),
                'total_invoice' => $group->count(),
            ]));
        }, collect());
        $data['items'] = $this->paginate($data['items'],10);

        return view('dashboard.year-statistic',$data);
    }


}
