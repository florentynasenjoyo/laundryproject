<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
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
        if (Str::length(Auth::guard('web')->user()) > 0) {
            $orderCount = Order::where('user_id', Auth::guard('web')->user()->id)->where('payment', '0')->count();
        } elseif (Str::length(Auth::guard('webshop')->user()) > 0) {
            $orderCount = Order::where('driver_id', Auth::guard('webshop')->user()->id)->where('status_tracking', '0')->count();
        } elseif (Str::length(Auth::guard('webdriver')->user()) > 0) {
            $orderCount = Order::where('driver_id', Auth::guard('webdriver')->user()->id)->whereIn('status_tracking', ['0', '5'])->count();
        }
        $tracking = DB::table('trackings')
                        ->selectRaw('count(order_id) as number_of_orders, order_id')
                        ->where('driver_id', Auth::guard('webdriver')->user()->id)
                        ->where('status', '3')
                        ->groupBy('order_id')
                        ->get();
        return view('driver.order.index', compact('orderCount', 'tracking'));
    }

    public function listData()
    {
        $data = Order::join('users', 'orders.user_id', 'users.id')
                ->join('services', 'orders.service_id', 'services.id')
                ->join('shops', 'services.shop_id', 'shops.id')
                ->where('orders.driver_id', Auth::guard('webdriver')->user()->id)
                ->whereIn('orders.status_tracking', ['0','1','2','5'])
                ->select('orders.shipping_cost', 'orders.driver_id', 'orders.id as id_order', 'orders.distance', 'orders.status_tracking', 'shops.name as shop_name', 'shops.logo as shop_logo', 'users.name as member_name', 'users.foto as member_foto', 'services.name')
                ->orderBy('orders.id', 'desc');
        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('shop', function($row) {
                if ($row->shop_logo <> '') {
                    $shop = '<img src="'.url($row->shop_logo).'" width="70">
                            <p>'.$row->shop_name.'</p>';
                } else {
                    $shop = '<p>'.$row->shop_name.'</p>';
                }
                return $shop;
            })
            ->addColumn('user', function($row) {
                if ($row->member_foto <> '') {
                    $user = '<img src="'.url($row->member_foto).'" width="70">
                            <p>'.$row->member_name.'</p>';
                } else {
                    $user = '<p>'.$row->member_name.'</p>';
                }
                return $user;
            })
            ->addColumn('shipping_cost', function($row) {
                if ($row->shipping_cost <> '') {
                    $shipping_cost = $row->shipping_cost/2;
                    $shipping = 'Rp. '.number_format($shipping_cost);
                } else {
                    $shipping = '';
                }
                return $shipping;
            })
            ->addColumn('cast', function($row) {
                if ($row->shipping_cost <> '') {
                    $castshipping = ($row->shipping_cost/2)*5/100;
                    $cast = 'Rp. '.number_format($castshipping);
                } else {
                    $cast = '';
                }
                return $cast;
            })
            ->addColumn('total', function($row) {
                if ($row->shipping_cost <> '') {
                    $shipping_cost = $row->shipping_cost/2;
                    $castshipping = ($row->shipping_cost/2)*5/100;
                    $total = 'Rp. '.number_format($shipping_cost - $castshipping);
                } else {
                    $total = '';
                }
                return $total;
            })
            ->addColumn('distance', function($row) {
                if ($row->distance <> '') {
                    $distance = $row->distance.' km';
                } else {
                    $distance = '';
                }
                return $distance;
            })
            ->addColumn('status_tracking', function($row) {
                if($row->status_tracking==0) {
                    $status = '<span class="badge badge-warning">Wait Confirmation</span>';
                } elseif ($row->status_tracking==1) {
                    $status = '<span class="badge badge-warning">Process Pickup</span>';
                } elseif ($row->status_tracking==2) {
                    $status = '<span class="badge badge-primary">Pickup</span>';
                } elseif ($row->status_tracking==5) {
                    $status = '<span class="badge badge-warning">Delivery to Customer</span>';
                } else {
                    $status = '';
                }
                return $status;
            })
            ->addColumn('action', function($row) {
                $btn = '<a href="'.route('driver.order.google_maps', $row->id_order).'" class="btn btn-info btn-sm mb-2 ml-2" title="Google Maps">
                            <i class="icon icon-map"></i>
                        </a>';
                if ($row->status_tracking==0) {
                    $btn .= '<a href="'.route('driver.order.confirmation', ['order_id' => $row->id_order, 'id' => Auth::guard('webdriver')->user()->id]).'" class="btn btn-primary btn-sm mb-2" title="Confirmation">
                                <i class="icon icon-check-circle"></i>
                            </a>';
                } elseif ($row->status_tracking==1) {
                    $btn .= '<a href="'.route('driver.order.pickup', ['order_id' => $row->id_order, 'id' => Auth::guard('webdriver')->user()->id]).'" class="btn btn-primary btn-sm mb-2" title="Pickup">
                                <i class="icon icon-check-circle"></i>
                            </a>';
                } elseif ($row->status_tracking==2) {
                    $btn .= '<a href="'.route('driver.order.shop', ['order_id' => $row->id_order, 'id' => Auth::guard('webdriver')->user()->id]).'" class="btn btn-primary btn-sm mb-2" title="Already In Store">
                                <i class="icon icon-check-circle"></i>
                            </a>';
                } elseif ($row->status_tracking==5) {
                    $btn .= '<a href="'.route('driver.order.received', ['order_id' => $row->id_order, 'id' => Auth::guard('webdriver')->user()->id]).'" class="btn btn-primary btn-sm mb-2" title="Order received by customers">
                                <i class="icon icon-check-circle"></i>
                            </a>';
                } else {
                    $btn = '';
                }                
                return $btn;
            })
            ->rawColumns(['action', 'status_tracking', 'shop', 'user', 'logo'])
            ->make(true);
        

        return $datatables;
    }

    public function listFinish()
    {
        $tracking = DB::table('trackings')
                        ->selectRaw('count(order_id) as number_of_orders, order_id')
                        ->where('driver_id', Auth::guard('webdriver')->user()->id)
                        ->groupBy('order_id')
                        ->get();
        // $array = array();
        foreach ($tracking as $key => $value) {
            $array[] = $value->order_id;
        }
        // echo $array;
        // die;
        $data = Order::join('users', 'orders.user_id', 'users.id')
                        ->join('services', 'orders.service_id', 'services.id')
                        ->join('shops', 'services.shop_id', 'shops.id')
                        ->where(function($q) use($tracking) {
                            foreach ($tracking as $key => $value) {
                                $q->where('orders.id', $value->order_id);
                            }
                        })
                        ->whereIn('orders.status_tracking', ['3','6'])
                        ->select('orders.shipping_cost', 'orders.id as id_order', 'orders.distance', 'orders.status_tracking', 'shops.name as shop_name', 'shops.logo as shop_logo', 'users.name as member_name', 'users.foto as member_foto', 'services.name')
                        ->orderBy('orders.id', 'desc');
        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('shop', function($row) {
                if ($row->shop_logo <> '') {
                    $shop = '<img src="'.url($row->shop_logo).'" width="70">
                            <p>'.$row->shop_name.'</p>';
                } else {
                    $shop = '<p>'.$row->shop_name.'</p>';
                }
                return $shop;
            })
            ->addColumn('user', function($row) {
                if ($row->member_foto <> '') {
                    $user = '<img src="'.url($row->member_foto).'" width="70">
                            <p>'.$row->member_name.'</p>';
                } else {
                    $user = '<p>'.$row->member_name.'</p>';
                }
                return $user;
            })
            ->addColumn('shipping_cost', function($row) {
                if ($row->shipping_cost <> '') {
                    $shipping_cost = $row->shipping_cost/2;
                    $shipping = 'Rp. '.number_format($shipping_cost);
                } else {
                    $shipping = '';
                }
                return $shipping;
            })
            ->addColumn('cast', function($row) {
                if ($row->shipping_cost <> '') {
                    $castshipping = ($row->shipping_cost/2)*5/100;
                    $cast = 'Rp. '.number_format($castshipping);
                } else {
                    $cast = '';
                }
                return $cast;
            })
            ->addColumn('total', function($row) {
                if ($row->shipping_cost <> '') {
                    $shipping_cost = $row->shipping_cost/2;
                    $castshipping = ($row->shipping_cost/2)*5/100;
                    $total = 'Rp. '.number_format($shipping_cost - $castshipping);
                } else {
                    $total = '';
                }
                return $total;
            })
            ->addColumn('distance', function($row) {
                $distance = $row->distance.' km';
                return $distance;
            })
            ->addColumn('status_tracking', function($row) {
                $status = '<span class="badge badge-success">Finished</span>';
                return $status;
            })
            ->rawColumns(['status_tracking', 'shop', 'user', 'logo'])
            ->make(true);

        return $datatables;
    }

    public function google_maps($id)
    {
        if (Str::length(Auth::guard('web')->user()) > 0) {
            $orderCount = Order::where('user_id', Auth::guard('web')->user()->id)->where('payment', '0')->count();
        } elseif (Str::length(Auth::guard('webshop')->user()) > 0) {
            $orderCount = Order::where('driver_id', Auth::guard('webshop')->user()->id)->where('status_tracking', '0')->count();
        } elseif (Str::length(Auth::guard('webdriver')->user()) > 0) {
            $orderCount = Order::where('driver_id', Auth::guard('webdriver')->user()->id)->where('status_tracking', '0')->count();
        }
        $order = Order::join('users', 'orders.user_id', 'users.id')
                ->join('services', 'orders.service_id', 'services.id')
                ->join('shops', 'services.shop_id', 'shops.id')
                ->select('users.google_map as from', 'users.address as member_address', 'shops.google_map as destination', 'shops.address as shop_address')
                ->where('orders.id', $id)
                ->first();
        $from = $order->from;
        $address_member = $order->member_address;
        $address_shop = $order->shop_address;
        $destination = $order->destination;
        return view('driver.order.google-maps', compact('from', 'destination', 'orderCount', 'address_member', 'address_shop'));
    }

    public function confirmation($order_id, $id)
    {
        $tracking = Tracking::create([
            'order_id' => $order_id,
            'driver_id' => $id,
            'status' => '1',
        ]);

        $order = Order::find($tracking->order_id);
        $order->status = '2';
        $order->status_tracking = '1';
        $order->save();

        return redirect()->route('driver.order')->with('success', 'Confirmation successful.');
    }

    public function pickup($order_id, $id)
    {
        $tracking = Tracking::create([
            'order_id' => $order_id,
            'driver_id' => $id,
            'status' => '2',
        ]);

        $order = Order::find($tracking->order_id);
        $order->status_tracking = '2';
        $order->save();

        return redirect()->route('driver.order')->with('success', 'Pickup successful.');
    }

    public function shop($order_id, $id)
    {
        $tracking = Tracking::create([
            'order_id' => $order_id,
            'driver_id' => $id,
            'status' => '3',
        ]);

        $order = Order::find($tracking->order_id);
        $order->driver_id = NULL;
        $order->status_tracking = '3';
        $order->save();

        return redirect()->route('driver.order')->with('success', 'Order Successful.');
    }

    public function received($order_id, $id)
    {
        $tracking = Tracking::create([
            'order_id' => $order_id,
            'driver_id' => $id,
            'status' => '6',
        ]);

        $order = Order::find($tracking->order_id);
        $order->status_tracking = '6';
        $order->save();

        return redirect()->route('driver.order')->with('success', 'Order received customer.');
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
