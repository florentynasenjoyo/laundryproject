<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Bank;
use App\Models\Driver;
use App\Models\Payment;
use App\Models\Tracking;
use App\Models\Service;

class OrderController extends Controller
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
        }
        return view('shop.order.index', compact('orderCount'));
    }

    public function listData()
    {
        $data = Order::join('services', 'orders.service_id', 'services.id')
                ->join('shops', 'services.shop_id', 'shops.id')
                ->join('users', 'orders.user_id', 'users.id')
                ->join('payments', 'orders.id', 'payments.order_id')
                ->join('rekenings', 'payments.rekening_id', 'rekenings.id')
                ->join('banks', 'rekenings.bank_id', 'banks.id')
                ->where('services.shop_id', Auth::guard('webshop')->user()->id)
                ->where('status', '!=', '3')
                ->where('status', '!=', '4')
                ->select('orders.*', 'users.name as member_name', 'payments.status_payment', 'payments.proof_payment', 'shops.name as shop_name', 'services.name', 'services.image', 'services.price_weight', 'services.price_unit', 'banks.logo', 'rekenings.name_rekening', 'rekenings.no_rekening')
                ->orderBy('orders.id', 'desc');
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
            ->addColumn('status', function($row) {
                if ($row->status==0) {
                    if ($row->status_payment==0 && $row->proof_payment != NULL) {
                        $btn = '<span class="badge badge-warning">Waiting for payment confirmation</span>';
                    } else {
                        $btn = '<span class="badge badge-warning">Process Payment</span>';
                    }
                } elseif ($row->status==1) {
                    if ($row->status_tracking==0) {
                        $btn = '<span class="badge badge-primary">Paid</span>';
                        $btn .= '<span class="badge badge-primary">Wait for Driver</span>';
                    } else {
                        $btn = '<span class="badge badge-primary">Paid</span>';
                    }
                } elseif ($row->status==2) {
                    if ($row->status_tracking==3) {
                        $btn = '<span class="badge badge-warning">Already In Store</span>';
                    } elseif ($row->status_tracking==4) {
                        $btn = '<span class="badge badge-primary">Processed Shop</span>';
                    } elseif ($row->status_tracking==5) {
                        $btn = '<span class="badge badge-success">Delivery</span>';
                    } else {
                        $btn = '<span class="badge badge-success">On Progress</span>';
                    }
                }
                return $btn;
            })
            ->addColumn('type', function($row) {
                if ($row->type==0) {
                    $btn = '<span class="badge badge-primary">Weight</span>';
                } elseif ($row->type==1) {
                    $btn = '<span class="badge badge-primary">Unit</span>';
                }
                return $btn;
            })
            ->addColumn('price', function($row) {
                if ($row->type==0) {
                    $price = 'Rp. '.number_format($row->price_weight).'/kg';
                } else {
                    $price = 'Rp. '.number_format($row->price_unit).'/unit';
                }
                return $price;
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
                if ($row->status_tracking==0) {
                    if ($row->payment==0) {
                        $btn = '';
                    } else {
                        if ($row->status==1) {
                            $btn = '';
                        } else {
                            $btn = '<a href="'.route('shop.order.confirm', $row->id).'" class="btn btn-success btn-sm mb-2" title="Confirmation">
                                        <i class="icon icon-check-circle"></i>
                                    </a>';
                        }
                    }
                } elseif ($row->status_tracking==3) {
                    $btn = '<a href="'.route('shop.order.confirmation', $row->id).'" class="btn btn-success btn-sm mb-2" title="Confirmation">
                                <i class="icon icon-check-circle"></i>
                            </a>';
                } elseif ($row->status_tracking==4) {
                    $btn = '<a href="'.route('shop.order.delivery', $row->id).'" class="btn btn-warning btn-sm mb-2" title="Delivery">
                                <i class="icon icon-send"></i>
                            </a>';
                } else {
                    $btn = '';
                }
                return $btn;
            })
            ->rawColumns(['action', 'status', 'image', 'type', 'logo'])
            ->make(true);

        return $datatables;
    }

    public function listFinish()
    {
        $data = Order::join('services', 'orders.service_id', 'services.id')
                ->join('shops', 'services.shop_id', 'shops.id')
                ->join('users', 'orders.user_id', 'users.id')
                ->join('payments', 'orders.id', 'payments.order_id')
                ->join('rekenings', 'payments.rekening_id', 'rekenings.id')
                ->join('banks', 'rekenings.bank_id', 'banks.id')
                ->where('services.shop_id', Auth::guard('webshop')->user()->id)
                ->where('status', '=', '3')
                ->select('orders.*', 'users.name as member_name', 'payments.status_payment', 'payments.proof_payment', 'shops.name as shop_name', 'services.name', 'services.image', 'services.price_weight', 'services.price_unit', 'banks.logo', 'rekenings.name_rekening', 'rekenings.no_rekening')
                ->orderBy('orders.id', 'desc');
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
            ->addColumn('status', function($row) {
                if ($row->status==0) {
                    if ($row->status_payment==0 && $row->proof_payment != NULL) {
                        $btn = '<span class="badge badge-warning">Waiting for payment confirmation</span>';
                    } else {
                        $btn = '<span class="badge badge-warning">Process Payment</span>';
                    }
                } elseif ($row->status==1) {
                    if ($row->status_tracking==0) {
                        $btn = '<span class="badge badge-primary">Paid</span>';
                        $btn .= '<span class="badge badge-primary">Wait for Driver</span>';
                    } else {
                        $btn = '<span class="badge badge-primary">Paid</span>';
                    }
                } elseif ($row->status==2) {
                    if ($row->status_tracking==6) {
                        $btn = '<span class="badge badge-success">Order received by customers</span>';
                    } else {
                        $btn = '<span class="badge badge-success">On Progress</span>';
                    }
                } elseif ($row->status==3) {
                    $btn = '<span class="badge badge-success">Finished</span>';
                }
                return $btn;
            })
            ->addColumn('type', function($row) {
                if ($row->type==0) {
                    $btn = '<span class="badge badge-primary">Weight</span>';
                } elseif ($row->type==1) {
                    $btn = '<span class="badge badge-primary">Unit</span>';
                }
                return $btn;
            })
            ->addColumn('price', function($row) {
                if ($row->type==0) {
                    $price = 'Rp. '.number_format($row->price_weight).'/kg';
                } else {
                    $price = 'Rp. '.number_format($row->price_unit).'/unit';
                }
                return $price;
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
                if ($row->status == 2) {
                    $btn = '<a href="'.route('order.tracking', $row->id).'" class="btn btn-primary btn-sm mb-2" title="Tracking">
                                <i class="icon icon-track_changes"></i>
                            </a>';
                } elseif ($row->status == 3) {
                    $btn = '<a href="'.route('order.tracking', $row->id).'" class="btn btn-primary btn-sm mb-2" title="Tracking">
                                <i class="icon icon-track_changes"></i>
                            </a>';
                } else {
                    if ($row->payment != 1) {
                        $btn = '<a href="'.route('order.payment', $row->id).'" class="btn btn-primary btn-sm mb-2" title="Payment">
                                    <i class="icon icon-payment"></i>
                                </a>';
                        $btn .= '<a href="'.route('order.cancel', $row->id).'" class="btn btn-danger btn-sm" title="Canceled">
                                    <i class="icon icon-cancel"></i>
                                </a>';
                    } else {
                        $btn = '';
                    }
                }
                return $btn;
            })
            ->rawColumns(['action', 'status', 'image', 'type', 'logo'])
            ->make(true);

        return $datatables;
    }

    public function listCancel()
    {
        $data = Order::join('services', 'orders.service_id', 'services.id')
                ->join('shops', 'services.shop_id', 'shops.id')
                ->join('users', 'orders.user_id', 'users.id')
                ->join('payments', 'orders.id', 'payments.order_id')
                ->join('rekenings', 'payments.rekening_id', 'rekenings.id')
                ->join('banks', 'rekenings.bank_id', 'banks.id')
                ->where('services.shop_id', Auth::guard('webshop')->user()->id)
                ->where('status', '=', '4')
                ->select('orders.*', 'users.name as member_name', 'payments.status_payment', 'payments.proof_payment', 'shops.name as shop_name', 'services.name', 'services.image', 'services.price_weight', 'services.price_unit', 'banks.logo', 'rekenings.name_rekening', 'rekenings.no_rekening')
                ->orderBy('orders.id', 'desc');
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
            ->addColumn('status', function($row) {
                $btn = '<span class="badge badge-danger">Canceled</span>';
                return $btn;
            })
            ->addColumn('type', function($row) {
                if ($row->type==0) {
                    $btn = '<span class="badge badge-primary">Weight</span>';
                } elseif ($row->type==1) {
                    $btn = '<span class="badge badge-primary">Unit</span>';
                }
                return $btn;
            })
            ->addColumn('price', function($row) {
                if ($row->type==0) {
                    $price = 'Rp. '.number_format($row->price_weight).'/kg';
                } else {
                    $price = 'Rp. '.number_format($row->price_unit).'/unit';
                }
                return $price;
            })
            ->addColumn('price_total', function($row) {
                $price = 'Rp. '.number_format($row->price_total);
                return $price;
            })
            ->addColumn('shipping_cost', function($row) {
                $shipping_cost = 'Rp. '.number_format($row->shipping_cost);
                return $shipping_cost;
            })
            ->rawColumns(['status', 'image', 'type', 'logo'])
            ->make(true);

        return $datatables;
    }

    public function getDriver(Request $request)
    {
        $data = [];
        if ($request->has('q')) {
            $search = $request->q;
            $data = Driver::where('name', 'LIKE', "%$search%")->get();
        } else {
            $data = Driver::get();
        }
        return response()->json($data);
    }

    public function tracking($id)
    {
        if (Str::length(Auth::guard('web')->user()) > 0) {
            $orderCount = Order::where('user_id', Auth::guard('web')->user()->id)->where('payment', '0')->where('status', '!=', '4')->count();
        } elseif (Str::length(Auth::guard('webshop')->user()) > 0) {
            $orderCount = Order::join('services', 'orders.service_id', 'services.id')
                ->where('services.shop_id', Auth::guard('webshop')->user()->id)
                ->whereIn('status_tracking', ['0', '3'])
                ->count();
        } elseif (Str::length(Auth::guard('webdriver')->user()) > 0) {
            $orderCount = Order::where('driver_id', Auth::guard('webdriver')->user()->id)->where('status_tracking', '0')->count();
        }
        $tracking = Tracking::join('orders', 'trackings.order_id', 'orders.id')
                    ->join('drivers', 'trackings.driver_id', 'drivers.id')
                    ->where('trackings.order_id', $id)
                    ->where('trackings.status', '!=', '0')
                    ->select('trackings.*', 'drivers.name as driver_name', 'drivers.no_hp', 'drivers.foto as drivers_foto')
                    ->get();
        return view('order.tracking', compact('tracking', 'orderCount'));
    }

    public function confirm($id)
    {
        $order = Order::find($id);
        $order->status = '1';
        $order->save();

        $payment = Payment::where('order_id', $id)->first();
        $payment->status_payment = '1';
        $payment->save();

        return redirect()->route('shop.order')->with('success', 'Payment confirmation successful.');
    }

    public function confirmation($order_id)
    {
        $driver = Tracking::where('order_id', $order_id)->first();
        $tracking = Tracking::create([
            'order_id' => $order_id,
            'driver_id' => $driver->driver_id,
            'status' => '4',
        ]);

        $order = Order::find($tracking->order_id);
        $order->status_tracking = '4';
        $order->save();

        return redirect()->route('shop.order')->with('success', 'Confirmation successful.');
    }

    public function delivery($id)
    {
        if (Str::length(Auth::guard('web')->user()) > 0) {
            $orderCount = Order::where('user_id', Auth::guard('web')->user()->id)->where('payment', '0')->count();
        } elseif (Str::length(Auth::guard('webshop')->user()) > 0) {
            $orderCount = Order::join('services', 'orders.service_id', 'services.id')
                ->where('services.shop_id', Auth::guard('webshop')->user()->id)
                ->whereIn('status_tracking', ['0', '3'])
                ->count();
        } elseif (Str::length(Auth::guard('webdriver')->user()) > 0) {
            $orderCount = Order::where('driver_id', Auth::guard('webdriver')->user()->id)->where('status_tracking', '0')->count();
        }
        $order = Order::join('services', 'orders.service_id', 'services.id')
                ->join('payments', 'orders.id', 'payments.order_id')
                ->join('rekenings', 'payments.rekening_id', 'rekenings.id')
                ->join('banks', 'rekenings.bank_id', 'banks.id')
                ->where('orders.id', $id)
                ->select('orders.*', 'payments.proof_payment', 'services.name', 'services.price_weight', 'services.price_unit', 'rekenings.name_rekening', 'rekenings.no_rekening', 'banks.logo')
                ->first();

        return view('shop.order.delivery', compact('order', 'orderCount'));
    }

    public function postDelivery(Request $request, $id)
    {
        $tracking = Tracking::create([
            'order_id' => $id,
            'driver_id' => $request->get('driver'),
            'status' => '5',
        ]);

        $order = Order::find($tracking->order_id);
        $order->driver_id = $request->get('driver');
        $order->status_tracking = '5';
        $order->save();

        return redirect()->route('shop.order')->with('success', 'Process Delivery to customer.');
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
