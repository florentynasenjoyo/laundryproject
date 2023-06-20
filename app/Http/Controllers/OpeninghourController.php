<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Openinghour;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class OpeninghourController extends Controller
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
            'day' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors);
        }

        if ($request->get('status')=='1') {
            Openinghour::create([
                'shop_id' => Auth::user()->id,
                'day' => $request->get('day'),
                'status' => $request->get('status'),
                'open' => $request->get('open_time'),
                'close' => $request->get('close_time'),
            ]);
        } else {
            Openinghour::create([
                'shop_id' => Auth::user()->id,
                'day' => $request->get('day'),
                'status' => $request->get('status'),
            ]);
        }

        return redirect()->route('openinghour.show', Auth::user()->id)->with('success', 'Add Opening Hour successful.');
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
        $opening = Openinghour::where('shop_id', $id)->get();
        $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        return view('openinghour.index', compact('opening', 'days', 'orderCount'));
    }

    public function listData()
    {
        $data = Openinghour::where('shop_id', Auth::user()->id);
        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('status', function($row) {
                if ($row->status==1) {
                    $btn = '<span class="badge badge-success">OPEN</span>';
                } else {
                    $btn = '<span class="badge badge-danger">CLOSED</span>';
                }
                return $btn;
            })
            ->addColumn('action', function($row) {
                $btn = '<a href="'.route('openinghour.delete', $row->id).'" class="btn btn-danger btn-sm">
                            <i class="icon icon-trash"></i>
                        </a>';
                return $btn;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);

        return $datatables;
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
        $opening = Openinghour::find($id);
        $opening->delete();

        return redirect()->route('openinghour.show', Auth::user()->id)->with('success', 'Delete opening hour successful.');
    }
}
