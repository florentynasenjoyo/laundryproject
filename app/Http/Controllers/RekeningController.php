<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Rekening;
use App\Models\Bank;
use App\Models\Order;
use Yajra\DataTables\DataTables;

class RekeningController extends Controller
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
        return view('rekening.index', compact('orderCount'));
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
            'name_rekening' => 'required',
            'no_rekening' => 'required',
            'bank_id' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors);
        }

        Rekening::create([
            'shop_id' => Auth::user()->id,
            'name_rekening' => $request->get('name_rekening'),
            'no_rekening' => $request->get('no_rekening'),
            'bank_id' => $request->get('bank_id'),
        ]);

        return redirect()->route('rekening.show', Auth::user()->id)->with('success', 'Add rekening successful.');
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
        $status = 'Show';
        $banks = Bank::get();
        return view('rekening.index', compact('banks', 'status', 'orderCount'));
    }

    public function listData()
    {
        $data = Rekening::join('banks', 'rekenings.bank_id', 'banks.id')
                ->where('shop_id', Auth::user()->id)
                ->select('rekenings.*', 'banks.logo');
        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('image', function($row) {
                $img = '<img src="'.url($row->logo).'" width="70">';
                return $img;
            })
            ->addColumn('action', function($row) {
                $btn = '<a href="'.route('rekening.edit', $row->id).'" class="btn btn-primary btn-sm">
                            <i class="icon icon-edit"></i>
                        </a>';
                $btn .= '<a href="'.route('rekening.delete', $row->id).'" class="btn btn-danger btn-sm">
                            <i class="icon icon-trash"></i>
                        </a>';
                return $btn;
            })
            ->rawColumns(['action', 'image'])
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
        $status = 'Edit';
        $rekenings = Rekening::join('banks', 'rekenings.bank_id', 'banks.id')
            ->where('shop_id', Auth::user()->id)
            ->select('rekenings.*', 'banks.logo')
            ->get();
        $banks = Bank::get();
        $rekening = Rekening::find($id);
        return view('rekening.index', compact('rekenings', 'banks', 'status', 'rekening', 'orderCount'));
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
        $validator = Validator::make($request->all(), [
            'name_rekening' => 'required',
            'no_rekening' => 'required',
            'bank_id' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors);
        }

        $rekening = Rekening::find($id);
        $rekening->name_rekening = $request->get('name_rekening');
        $rekening->no_rekening = $request->get('no_rekening');
        $rekening->bank_id = $request->get('bank_id');
        $rekening->save();

        return redirect()->route('rekening.show', Auth::user()->id)->with('success', 'Update rekening successful.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rekenings = Rekening::find($id);
        $rekenings->delete();

        return redirect()->route('rekening.show', Auth::user()->id)->with('success', 'Delete rekening successful.');
    }
}
