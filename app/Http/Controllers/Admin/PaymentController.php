<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Order;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function shop()
    {
        return view('admin.payment.index');
    }

    public function listShop()
    {
        $data = Order::join('services', 'orders.service_id', 'services.id')
                ->join('shops', 'services.shop_id', 'shops.id')
                ->join('payments', 'orders.id', 'payments.order_id')
                ->join('banks', 'payments.bank_id', 'banks.id')
                ->where('orders.status_tracking', '7')
                ->select('orders.*', 'shops.name as shop_name', 'services.price_weight', 'services.price_unit', 'payments.proof_payment', 'banks.logo', 'banks.name_rekening', 'banks.no_rekening');
        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('proof_payment', function($row) {
                $image = '<a href="'.url($row->proof_payment).'" target="_blank">
                            <img src="'.url($row->proof_payment).'" width="70">
                        </a>';
                return $image;
            })
            ->addColumn('status', function($row) {
                $btn = '<span class="badge bg-success">Finished</span>';
                return $btn;
            })
            ->addColumn('price_total', function($row) {
                $price = 'Rp. '.number_format($row->price_total);
                return $price;
            })
            ->addColumn('price', function($row) {
                if ($row->type==0) {
                    $price = 'Rp. '.number_format($row->price_weight);
                } else {
                    $price = 'Rp. '.number_format($row->price_unit);
                }
                return $price;
            })
            ->addColumn('sub_total', function($row) {
                if ($row->type==0) {
                    $sub_total = 'Rp. '.number_format($row->price_weight*$row->amount);
                } else {
                    $sub_total = 'Rp. '.number_format($row->price_unit*$row->amount);
                }
                return $sub_total;
            })
            ->addColumn('shipping_cost', function($row) {
                $shipping_cost = 'Rp. '.number_format($row->shipping_cost);
                return $shipping_cost;
            })
            ->addColumn('action', function($row) {
                $btn = '<a href="'.route('admin.payment.shop.confirmation', $row->id).'" class="btn btn-primary btn-sm nr-2" title="Payment Confirmation">
                            <i class="fas fa-check-circle"></i>
                        </a>';
                return $btn;
            })
            ->rawColumns(['action', 'proof_payment', 'status'])
            ->make(true);

        return $datatables;
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
        $order = Order::join('services', 'orders.service_id', 'services.id')
            ->join('shops', 'services.shop_id', 'shops.id')
            ->where('orders.id', $id)
            ->select('orders.*', 'shops.name as shop_name')
            ->first();

        return view('admin.payment.shop-payment', compact('order'));
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
