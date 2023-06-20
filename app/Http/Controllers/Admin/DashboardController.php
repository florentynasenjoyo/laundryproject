<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shop;
use App\Models\Order;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $total_member = User::count();
        $total_shop = Shop::count();
        $total_order = Order::join('services', 'orders.service_id', 'services.id')
                ->join('shops', 'services.shop_id', 'shops.id')
                ->join('users', 'orders.user_id', 'users.id')
                ->select('orders.*', 'users.foto', 'users.name as member_name', 'shops.name as shop_name', 'shops.logo', 'services.name', 'services.image')
                ->count();
        $order_progress = Order::join('services', 'orders.service_id', 'services.id')
                ->join('shops', 'services.shop_id', 'shops.id')
                ->join('payments', 'orders.id', 'payments.order_id')
                ->join('rekenings', 'payments.rekening_id', 'rekenings.id')
                ->join('banks', 'rekenings.bank_id', 'banks.id')
                ->whereIn('status', ['0','1','2'])
                ->count();
        $order_finish = Order::join('services', 'orders.service_id', 'services.id')
                ->join('shops', 'services.shop_id', 'shops.id')
                ->join('payments', 'orders.id', 'payments.order_id')
                ->join('rekenings', 'payments.rekening_id', 'rekenings.id')
                ->join('banks', 'rekenings.bank_id', 'banks.id')
                ->where('status', '=', '3')
                ->count();
        $order_cancel = Order::join('services', 'orders.service_id', 'services.id')
                ->join('shops', 'services.shop_id', 'shops.id')
                ->join('payments', 'orders.id', 'payments.order_id')
                ->join('rekenings', 'payments.rekening_id', 'rekenings.id')
                ->join('banks', 'rekenings.bank_id', 'banks.id')
                ->where('status', '=', '4')
                ->count();
        return view('admin.dashboard.index', compact('total_member', 'total_order', 'total_shop', 'order_progress', 'order_finish', 'order_cancel'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
