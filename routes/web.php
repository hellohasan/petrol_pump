<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/test-master','HomeController@testMaster');


Route::group(['middleware' => 'entiInstaller'], function () {
    Route::get('installer',['as'=>'installer','uses'=>'InstallerController@getIndex']);
    Route::get('check-server',['as'=>'check-server','uses'=>'InstallerController@checkServer']);
    Route::get('check-permission',['as'=>'check-permission','uses'=>'InstallerController@checkPermission']);
    Route::get('check-database',['as'=>'check-database','uses'=>'InstallerController@checkDatabase']);
    Route::post('submit-database',['as'=>'submit-database','uses'=>'InstallerController@submitDatabase']);
    Route::get('check-purchase',['as'=>'check-purchase','uses'=>'InstallerController@checkPurchase']);
    Route::post('submit-purchase',['as'=>'submit-purchase','uses'=>'InstallerController@submitPurchase']);
});
Route::get('install-complete',['as'=>'install-complete','uses'=>'InstallerController@installComplete']);

Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
Auth::routes();

Route::get('dashboard',['as'=>'dashboard','uses'=>'DashboardController@getDashboard']);

Route::group(['prefix' => 'admin'], function () {

    Route::get('edit-profile', ['as' => 'edit-profile', 'uses' => 'BasicSettingController@editProfile']);
    Route::post('edit-profile', ['as' => 'update-profile', 'uses' => 'BasicSettingController@updateProfile']);

    Route::get('change-password', ['as' => 'admin-change-password', 'uses' => 'BasicSettingController@getChangePass']);
    Route::post('change-password', ['as' => 'admin-change-password', 'uses' => 'BasicSettingController@postChangePass']);

    Route::get('basic-setting', ['as' => 'basic-setting', 'uses' => 'BasicSettingController@getBasicSetting']);
    Route::put('basic-general/{id}', ['as' => 'basic-update', 'uses' => 'BasicSettingController@putBasicSetting']);

    Route::get('manage-logo', ['as' => 'manage-logo', 'uses' => 'WebSettingController@manageLogo']);
    Route::post('manage-logo', ['as' => 'manage-logo', 'uses' => 'WebSettingController@updateLogo']);

    Route::get('transaction-log',['as'=>'transaction-log','uses'=>'DashboardController@getTransactionLog']);

    Route::get('/manage-footer', ['as' => 'manage-footer', 'uses' => 'WebSettingController@manageFooter']);
    Route::put('/manage-footer/{id}', ['as' => 'manage-footer-update', 'uses' => 'WebSettingController@updateFooter']);

    Route::get('manage-category',['as'=>'manage-category','uses'=>'CategoryController@manageCategory']);
    Route::post('manage-category',['as'=>'manage-category','uses'=>'CategoryController@storeCategory']);
    Route::get('manage-category/{product_id?}',['as'=>'category-edit','uses'=>'CategoryController@editCategory']);
    Route::put('manage-category/{product_id?}',['as'=>'category-edit','uses'=>'CategoryController@updateCategory']);
    Route::delete('/manage-category/{product_id?}','CategoryController@deleteItem');

    Route::get('manage-company',['as'=>'manage-company','uses'=>'CompanyController@manageCompany']);
    Route::post('manage-company',['as'=>'manage-company','uses'=>'CompanyController@storeCompany']);
    Route::get('manage-company/{product_id?}',['as'=>'company-edit','uses'=>'CompanyController@editCompany']);
    Route::put('manage-company/{product_id?}',['as'=>'company-edit','uses'=>'CompanyController@updateCompany']);

    Route::get('product-new',['as'=>'product-new','uses'=>'ProductController@newProduct']);
    Route::post('product-new',['as'=>'product-new','uses'=>'ProductController@storeProduct']);
    Route::get('product-history',['as'=>'product-history','uses'=>'ProductController@storeHistory']);
    Route::get('product-edit/{id}',['as'=>'product-edit','uses'=>'ProductController@editProduct']);
    Route::put('product-edit/{id}',['as'=>'product-update','uses'=>'ProductController@updateProduct']);
    Route::get('product-view/{id}',['as'=>'product-view','uses'=>'ProductController@viewProduct']);

    Route::resource('machine','MachineController',['except' => ['destroy', 'show']]);
    Route::post('machine-activation','MachineController@activation')->name('machine.activation');
    Route::get('machine-reading','MachineController@reading')->name('machine.reading');

    Route::get('fuel-sell','FuelSellController@sellFuel')->name('fuel.sell');
    Route::post('fuel-sell','FuelSellController@submitSellFuel')->name('fuel.sell.submit');
    Route::get('fuel-sell-history','FuelSellController@sellHistory')->name('fuel.sell.history');
    Route::get('check-fuel-qty/{machine}/{qty}','FuelSellController@checkQuantity')->name('check-fuel-qty');

    Route::get('store-new',['as'=>'store-new','uses'=>'StoreController@newStore']);
    Route::post('store-new',['as'=>'store-new','uses'=>'StoreController@submitStore']);
    Route::get('store-history',['as'=>'store-history','uses'=>'StoreController@storeHistory']);
    Route::get('store-view/{id}',['as'=>'store-view','uses'=>'StoreController@viewHistory']);
    Route::get('store-edit/{id}',['as'=>'store-edit','uses'=>'StoreController@editHistory']);
    Route::put('store-edit/{id}',['as'=>'store-update','uses'=>'StoreController@updateHistory']);
    Route::get('current-store',['as'=>'current-store','uses'=>'StoreController@currentStore']);
    Route::get('search-current-store',['as'=>'search-current-store','uses'=>'StoreController@searchCurrentStore']);
    Route::get('store-search-view/{id}',['as'=>'store-search-view','uses'=>'StoreController@searchViewResult']);
    Route::delete('store-delete',['as'=>'store-delete','uses'=>'StoreController@deleteStore']);

    Route::get('company-send',['as'=>'company-send','uses'=>'CompanyPaymentController@getCompanySend']);
    Route::post('company-send',['as'=>'company-send','uses'=>'CompanyPaymentController@submitCompanySend']);
    Route::get('payment-new',['as'=>'payment-new','uses'=>'CompanyPaymentController@newPayment']);
    Route::post('payment-new',['as'=>'payment-new','uses'=>'CompanyPaymentController@storePayment']);
    Route::get('payment-history',['as'=>'payment-history','uses'=>'CompanyPaymentController@historyPayment']);
    Route::delete('payment-delete',['as'=>'payment-delete','uses'=>'CompanyPaymentController@deletePayment']);

    Route::get('mange-account',['as'=>'mange-account','uses'=>'AccountController@mangeAccount']);
    Route::post('mange-account',['as'=>'mange-account','uses'=>'AccountController@submitAccount']);
    Route::get('account-history',['as'=>'account-history','uses'=>'AccountController@historyAccount']);
    Route::delete('account-delete',['as'=>'account-delete','uses'=>'AccountController@deleteAccount']);

    Route::get('receive-new','ReceiveController@newReceive')->name('receive.new');
    Route::post('receive-new','ReceiveController@submitReceive')->name('receive.submit');
    Route::get('receive-history','ReceiveController@receiveHistory')->name('receive.history');
    Route::delete('receive-delete','ReceiveController@receiveDelete')->name('receive.delete');
    Route::get('check-receive-balance/{amount}/{id}','ReceiveController@checkBalance')->name('check-receive-balance');

    /*Route::get('manage-instalment',['as'=>'manage-instalment','uses'=>'InstalmentController@manageInstalment']);
    Route::post('manage-instalment',['as'=>'manage-instalment','uses'=>'InstalmentController@storeInstalment']);
    Route::get('manage-instalment/{product_id?}',['as'=>'instalment-edit','uses'=>'InstalmentController@editInstalment']);
    Route::put('manage-instalment/{product_id?}',['as'=>'instalment-edit','uses'=>'InstalmentController@updateInstalment']);*/

    Route::get('customer-new',['as'=>'customer-new','uses'=>'CustomerController@createCustomer']);
    Route::post('customer-new',['as'=>'customer-new','uses'=>'CustomerController@storeCustomer']);
    Route::get('customer-history',['as'=>'customer-history','uses'=>'CustomerController@historyCustomer']);
    Route::get('customer-edit/{id}',['as'=>'customer-edit','uses'=>'CustomerController@editCustomer']);
    Route::put('customer-edit/{id}',['as'=>'customer-update','uses'=>'CustomerController@updateCustomer']);
    Route::get('customer-view/{id}',['as'=>'customer-view','uses'=>'CustomerController@viewCustomer']);

    Route::get('sell-new',['as'=>'sell-new','uses'=>'SellController@newSell']);
    Route::post('sell-new',['as'=>'sell-new','uses'=>'SellController@submitSell']);
    Route::get('sell-invoice/{invoice}',['as'=>'sell-invoice','uses'=>'SellController@sellInvoice']);
    Route::get('print-invoice/{invoice}',['as'=>'print-invoice','uses'=>'SellController@printInvoice']);
    Route::get('sell-history',['as'=>'sell-history','uses'=>'SellController@sellHistory']);
    Route::delete('sell-delete',['as'=>'sell-delete','uses'=>'SellController@sellDelete']);
    Route::get('check-instalment-percent/{instalment_id}/{total}',['as'=>'check-instalment-percent','uses'=>'SellController@checkInstalmentPercent']);

    Route::get('due-order-history',['as'=>'due-order-history','uses'=>'RepaymentController@dueSellList']);
    Route::get('repayment-new',['as'=>'repayment-new','uses'=>'RepaymentController@newDueRepayment']);
    Route::get('get-order-details/{id}',['as'=>'get-order-details','uses'=>'RepaymentController@getOrderDetails']);
    Route::get('get-order-due',['as'=>'get-order-due','uses'=>'RepaymentController@getOrderDue']);
    Route::get('get-customer-due-orders',['as'=>'get-customer-due-orders','uses'=>'RepaymentController@getCustomerDueOrder']);
    Route::post('submit-due-repayment',['as'=>'submit-due-repayment','uses'=>'RepaymentController@submitDueRepayment']);
    Route::get('repayment-history',['as'=>'repayment-history','uses'=>'RepaymentController@historyDueRepayment']);
    Route::delete('due-repayment-delete',['as'=>'due-repayment-delete','uses'=>'RepaymentController@deleteDueRepayment']);
    Route::get('upcoming-due-repayment',['as'=>'upcoming-due-repayment','uses'=>'RepaymentController@upcomingDueRepayment']);

    Route::get('sell-list/{date}',['as'=>'sell-list','uses'=>'DashboardController@sellList']);
    Route::get('daily-statistic',['as'=>'daily-statistic','uses'=>'DashboardController@dailyStatistic']);
    Route::get('monthly-statistic',['as'=>'monthly-statistic','uses'=>'DashboardController@monthlyStatistic']);
    Route::get('yearly-statistic',['as'=>'yearly-statistic','uses'=>'DashboardController@yearlyStatistic']);

    Route::resource('roles','RoleController');
    Route::resource('permissions','PermissionController');
    Route::resource('users','UserController');


});

Route::get('/get-company-category',function (\Illuminate\Http\Request $request){
    $company_id = $request->get('company_id');
    $category = \App\Category::where('company_id','=',$company_id)->whereStatus(1)->get();
    return Response::json($category);
});
Route::get('/get-product-price',function (\Illuminate\Http\Request $request){
    $product_id = $request->get('product_id');
    $product = \App\Product::findOrFail($product_id);
    $product['unit'] = $product->category->unit;
    return Response::json($product);
});

Route::get('/get-category-product',function (\Illuminate\Http\Request $request){
    $category_id = $request->get('category_id');
    $product = \App\Product::where('category_id','=',$category_id)->get();
    return Response::json($product);
});

Route::get('/check-product-code',function (\Illuminate\Http\Request $request){
    $code = $request->get('code');
    $product = \App\Product::whereId($code)->count();
    if ($product == 0){
        $rr['errorStatus'] = 'yes';
        $rr['errorDetails'] = 'Select Wrong Product.';
        return $result = json_encode($rr);
    }else{
        $product = \App\Product::findOrFail($code);
        if ($product->quantity == 0){
            $rr['errorStatus'] = 'yes';
            $rr['errorDetails'] = 'Product Not Available In Store.';
            return $result = json_encode($rr);
        }else{
            $rr['name'] = $product->name;
            $rr['available'] = $product->quantity;
            $rr['price'] = $product->sell_price;
            $rr['product_id'] = $product->id;
            $rr['unit'] = $product->category->unit;
            return $result = json_encode($rr);
        }
    }

});

Route::get('check-product-store',function (\Illuminate\Http\Request $request){
    $qty = $request->get('quantity');
    $product_id = $request->get('product_id');
    $product = \App\Product::findOrFail($product_id);
    if ($product->quantity < $qty){
        $rr['errorStatus'] = 'yes';
        $rr['errorDetails'] = 'Store Available only - '.$product->quantity."".$product->category->unit;
        return $result = json_encode($rr);
    }else{
        $rr['errorStatus'] = 'no';
        $rr['errorDetails'] = 'Product Is Added To Sell Item.';
        return $result = json_encode($rr);
    }

});

Route::get('/check-admin-balance',function (\Illuminate\Http\Request $request){
    $amount = $request->get('amount');
    if (getLoginRole() === 'Super Admin'){
        $balance = \App\BasicSetting::first()->balance;
    }else{
        $balance = \Illuminate\Support\Facades\Auth::user()->balance;
    }
    if ($balance < $amount){
        $rr['errorStatus'] = 'yes';
        $rr['errorDetails'] = 'You have insufficient balance.';
    }else{
        $rr['errorStatus'] = 'no';
        $rr['errorDetails'] = 'You can Process Your Request.';
    }
    return $result = json_encode($rr);
});
