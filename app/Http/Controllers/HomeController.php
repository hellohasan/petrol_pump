<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home');
    }

    public function testMaster()
    {
        /*$role = Role::create(['name' => 'Super Admin']);
        $user = User::first();
        $user->assignRole([$role->id]);*/

        /*$user = User::create([
            'name' => 'Mr Seller',
            'email' => 'seller@gmail.com',
            'password' => Hash::make(123456),
        ]);
        $user->assignRole([6]);
        echo 'Done';*/
    }

}
