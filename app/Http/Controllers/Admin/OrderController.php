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
use App\Models\Payment;
use App\Models\Tracking;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.order.index');
    }

    public function payment()
    {
        return view('admin.order.order-payment');
    }

    public function listData()
    {
        $data = Order::join('services', 'orders.service_id', 'services.id')
                ->join('shops', 'services.shop_id', 'shops.id')
                ->join('users', 'orders.user_id', 'users.id')
                ->select('orders.*', 'users.foto', 'users.name as member_name', 'shops.name as shop_name', 'shops.logo', 'services.name', 'services.image');
        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('logo', function($row) {
                if ($row->logo <> '') {
                    $image = '<img src="'.url($row->logo).'" width="70">
                            <p>'.$row->shop_name.'</p>';
                } else {
                    $image = '';
                }
                return $image;
            })
            ->addColumn('foto', function($row) {
                if ($row->foto <> '') {
                    $image = '<img src="'.url($row->foto).'" width="70">
                            <p>'.$row->member_name.'</p>';
                } else {
                    $image = '';
                }
                return $image;
            })
            ->addColumn('status', function($row) {
                if ($row->status==0) {
                    $btn = '<span class="badge bg-warning">Process Payment</span>';
                } elseif ($row->status==1) {
                    $btn = '<span class="badge bg-primary">Paid</span>';
                } elseif ($row->status==2) {
                    $btn = '<span class="badge bg-info">On Progress</span>';
                } elseif ($row->status==3) {
                    $btn = '<span class="badge bg-success">Finished</span>';
                } elseif ($row->status==4) {
                    $btn = '<span class="badge bg-danger">Canceled</span>';
                }
                return $btn;
            })
            ->addColumn('price_total', function($row) {
                if ($row->price_total <> '') {
                    $price = 'Rp. '.number_format($row->price_total);
                } else {
                    $price = 'Rp. 0';
                }
                return $price;
            })
            ->addColumn('shipping_cost', function($row) {
                if ($row->shipping_cost <> '') {
                    $shipping_cost = 'Rp. '.number_format($row->shipping_cost);
                } else {
                    $shipping_cost = 'Rp. 0';
                }
                return $shipping_cost;
            })
            ->rawColumns(['foto', 'logo', 'status'])
            ->make(true);

        return $datatables;
    }

    public function listPayment()
    {
        $data = Order::join('services', 'orders.service_id', 'services.id')
                ->join('shops', 'services.shop_id', 'shops.id')
                ->join('payments', 'orders.id', 'payments.order_id')
                ->join('banks', 'payments.bank_id', 'banks.id')
                ->join('users', 'orders.user_id', 'users.id')
                ->where('orders.status', '=', '0')
                ->where('payments.proof_payment', '!=', NULL)
                ->select('orders.*', 'users.foto', 'users.name as member_name', 'payments.status_payment', 'payments.proof_payment', 'shops.name as shop_name', 'services.name', 'services.image', 'banks.logo', 'banks.name_rekening', 'banks.no_rekening');
        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('image', function($row) {
                $image = '<img src="'.url($row->image).'" width="70">';
                return $image;
            })
            ->addColumn('logo', function($row) {
                $image = '<img src="'.url($row->logo).'" width="70">
                        <p>'.$row->no_rekening.' - '.$row->name_rekening.'</p>';
                return $image;
            })
            ->addColumn('foto', function($row) {
                $image = '<img src="'.url($row->foto).'" width="70">
                        <p>'.$row->member_name.'</p>';
                return $image;
            })
            ->addColumn('proof_payment', function($row) {
                $image = '<a href="'.url($row->proof_payment).'" target="_blank">
                            <img src="'.url($row->proof_payment).'" width="70">
                        </a>';
                return $image;
            })
            ->addColumn('status', function($row) {
                if ($row->status==0) {
                    if ($row->status_payment==0 && $row->proof_payment != NULL) {
                        $btn = '<span class="badge bg-warning">Waiting for payment confirmation</span>';
                    } else {
                        $btn = '<span class="badge bg-warning">Process Payment</span>';
                    }
                }
                return $btn;
            })
            ->addColumn('price_total', function($row) {
                $price = 'Rp. '.number_format($row->price_total);
                return $price;
            })
            ->addColumn('shipping_cost', function($row) {
                $shipping_cost = 'Rp. '.number_format($row->shipping_cost);
                return $shipping_cost;
            })
            ->addColumn('action', function($row) {
                if ($row->proof_payment != NULL) {
                    $btn = '<a href="'.route('admin.order.confirmation', $row->id).'" class="btn btn-primary btn-sm nr-2" title="Confirmation">
                                <i class="fas fa-check-circle"></i>
                            </a>';
                } else {
                    $btn = '';
                }
                return $btn;
            })
            ->rawColumns(['action', 'logo', 'image', 'foto', 'proof_payment', 'status'])
            ->make(true);

        return $datatables;
    }

    public function confirmation($id)
    {
        $order = Order::find($id);
        $order->status = '1';
        $order->save();

        $payment = Payment::where('order_id', $id)->first();
        $payment->status_payment = '1';
        $payment->save();

        return redirect()->route('admin.order')->with('success', 'Payment confirmation successful.');
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
