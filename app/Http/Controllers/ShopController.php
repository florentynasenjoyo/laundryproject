<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Service;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Order;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Str::length(Auth::guard('web')->user()) > 0) {
            $orderCount = Order::where('user_id', Auth::guard('web')->user()->id)->where('payment', '0')->count();
        } elseif (Str::length(Auth::guard('webshop')->user()) > 0) {
            $orderCount = Order::join('services', 'orders.service_id', 'services.id')
                ->where('services.shop_id', Auth::guard('webshop')->user()->id)
                ->where('orders.status', '!=', '3')
                ->where('orders.status', '!=', '4')
                ->whereIn('status_tracking', ['0', '3'])
                ->count();
        } elseif (Str::length(Auth::guard('webdriver')->user()) > 0) {
            $orderCount = Order::where('driver_id', Auth::guard('webdriver')->user()->id)
                ->where('status', '!=', '4')
                ->where('status_tracking', '0')
                ->count();
        } else {
            $orderCount = '';
        }
        $shops = Shop::join('openinghours', 'shops.id', 'openinghours.shop_id')
            ->where('openinghours.day', date('l'))
            ->select('shops.*', 'openinghours.status', 'openinghours.day', 'openinghours.open', 'openinghours.close')
            ->get();
        return view('shop.index', compact('shops', 'orderCount'));
    }

    public function login()
    {
        $orderCount = '';
        return view('shop.login', compact('orderCount'));
    }

    public function register()
    {
        $orderCount = '';
        return view('shop.register', compact('orderCount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'address' => 'required',
            'google_map' => 'required',
            'email' => 'required|email|unique:shops,email',
            'no_hp' => 'required|unique:shops,no_hp',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors)->withInput($request->all());
        }

        Shop::create([
            'name' => $request->get('nama'),
            'slug' => Str::slug($request->get('nama')),
            'address' => $request->get('address'),
            'google_map' => $request->get('google_map'),
            'latitude' => $request->get('address_latitude'),
            'longitude' => $request->get('address_longitude'),
            'email' => $request->get('email'),
            'no_hp' => $request->get('no_hp'),
            'password' => Hash::make($request->get('password'))
        ]);

        return redirect()->route('shop.login')->with('success', 'Selamat berhasil mendaftarkan toko.');
    }

    public function postlogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors)->withInput($request->all());
        }

        $email = $request->get('email');
        $password = Hash::make($request->get('password'));
        if (Auth::guard('webshop')->attempt($request->only('email', 'password'))) {
            Session::put('users', 'shop');
            Session::put('login_shop', TRUE);
            return redirect()->route('home');
        }

        return back()->with('danger', 'Data tidak ditemukan.')->withInput($request->all());
    }

    public function logout()
    {
        Session::flush();
        Auth::guard('webshop')->logout();
        return redirect()->route('shop.login')->with('success', 'Berhasil keluar.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Str::length(Auth::guard('web')->user()) > 0) {
            $orderCount = Order::where('user_id', Auth::guard('web')->user()->id)->where('payment', '0')->count();
        } elseif (Str::length(Auth::guard('webshop')->user()) > 0) {
            $orderCount = Order::join('services', 'orders.service_id', 'services.id')
                ->where('services.shop_id', Auth::guard('webshop')->user()->id)
                ->where('orders.status', '!=', '3')
                ->where('orders.status', '!=', '4')
                ->whereIn('status_tracking', ['0', '3'])
                ->count();
        } elseif (Str::length(Auth::guard('webdriver')->user()) > 0) {
            $orderCount = Order::where('driver_id', Auth::guard('webdriver')->user()->id)
                ->where('status', '!=', '4')
                ->where('status_tracking', '0')
                ->count();
        } else {
            $orderCount = '';
        }
        $shop = Shop::where('slug', $id)->first();
        $services = Service::where('shop_id', $shop->id)->get();
        return view('shop.detail', compact('shop', 'services', 'orderCount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
