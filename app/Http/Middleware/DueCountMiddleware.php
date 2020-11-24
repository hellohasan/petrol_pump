<?php

namespace App\Http\Middleware;

use App\Order;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\View;

class DueCountMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $start = Carbon::parse()->addDays('7')->format('Y-m-d');
        $end = '2018-00-00';
        $dueCount = Order::whereStatus(0)->whereBetween('due_payment_date',[$end,$start])->count();
        View::share('dueCount',$dueCount);

        return $next($request);
    }
}
