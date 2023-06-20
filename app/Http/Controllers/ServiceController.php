<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Service;
use App\Models\Bank;
use App\Models\Rekening;
use App\Models\Order;
use App\Models\Reviewrating;

class ServiceController extends Controller
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
        $services = Service::join('shops', 'services.shop_id', 'shops.id')
                ->select('services.*', 'shops.name as name_shop', 'shops.slug as slug_shop')
                ->get();
        return view('service.list', compact('services', 'orderCount'));
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
    public function reviewstore(Request $request)
    {
        $review = Reviewrating::create([
            'user_id' => Auth::guard('web')->user()->id,
            'service_id' => $request->service_id,
            'comments' => $request->comment,
            'star_rating' => $request->rating,
        ]);

        return redirect()->back()->with('success', 'Your review has been submitted Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug, $id)
    {
        $rekening = Rekening::join('banks', 'rekenings.bank_id', 'banks.id')
                ->join('shops', 'rekenings.shop_id', 'shops.id')
                ->where('shops.slug', $slug)
                ->select('rekenings.name_rekening', 'rekenings.no_rekening', 'banks.logo', 'rekenings.id', 'rekenings.bank_id')
                ->get();
        $service = Service::join('shops', 'services.shop_id', 'shops.id')
            ->where('shops.slug', $slug)
            ->where('services.slug', $id)
            ->select('services.*', 'shops.name as name_shop', 'shops.latitude', 'shops.longitude')
            ->first();
        if (Str::length(Auth::guard('web')->user()) > 0) {
            $orderCount = Order::where('user_id', Auth::guard('web')->user()->id)
                ->where('payment', '0')->count();
            $reviewcount = Reviewrating::where('service_id', $service->id)->where('user_id', Auth::guard('web')->user()->id)->count();
        } elseif (Str::length(Auth::guard('webshop')->user()) > 0) {
            $orderCount = Order::join('services', 'orders.service_id', 'services.id')
                ->where('services.shop_id', Auth::guard('webshop')->user()->id)
                ->where('orders.status', '!=', '3')
                ->where('orders.status', '!=', '4')
                ->whereIn('status_tracking', ['0', '3'])
                ->count();
            $reviewcount = Reviewrating::where('service_id', $service->id)->count();
        } elseif (Str::length(Auth::guard('webdriver')->user()) > 0) {
            $orderCount = Order::where('driver_id', Auth::guard('webdriver')->user()->id)
                ->where('status', '!=', '4')
                ->where('status_tracking', '0')
                ->count();
            $reviewcount = Reviewrating::where('service_id', $service->id)->count();
        } else {
            $orderCount = '';
            $reviewcount = '';
        }
        $review = Reviewrating::join('users', 'reviewratings.user_id', 'users.id')
                ->where('service_id', $service->id)
                ->select('reviewratings.*', 'users.name', 'users.foto')
                ->get();

        return view('service.detail', compact('service', 'rekening', 'orderCount', 'review', 'reviewcount'));
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
