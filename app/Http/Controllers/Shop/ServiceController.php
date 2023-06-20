<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use App\Models\Service;
use App\Models\Order;

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
        }
        return view('service.index', compact('orderCount'));
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
            'name' => 'required',
            'price_weight' => 'required',
            'price_unit' => 'required',
            'image' => 'mimes:png,jpg,jpeg,svg',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors);
        }

        if ($request->image <> '') {
            $image = $request->file('image');
            $namaImage = 'Service-'.Str::slug($request->get('name')).'-'.Str::random(5).'.'.$image->extension();
            $tujuan = 'images';
            $image->move(public_path($tujuan), $namaImage);
            $imagename = $tujuan.'/'.$namaImage;
        }

        Service::create([
            'shop_id' => Auth::user()->id,
            'name' => $request->get('name'),
            'price_weight' => $request->get('price_weight'),
            'price_unit' => $request->get('price_unit'),
            'slug' => Str::slug($request->get('name')),
            'image' => $imagename,
            'description' => $request->get('description')
        ]);

        return redirect()->route('shopservice.show', Auth::user()->id)->with('success', 'Add service successful.');
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
        }
        $status = 'Show';
        $services = Service::where('shop_id', $id)->get();
        return view('service.index', compact('services', 'status', 'orderCount'));
    }

    public function listData()
    {
        $data = Service::where('shop_id', Auth::user()->id);
        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('image', function($row) {
                $img = '<img src="'.url($row->image).'" width="70">';
                return $img;
            })
            ->addColumn('description', function($row) {
                $desc = Str::limit(strip_tags($row->description), 100);
                return $desc;
            })
            ->addColumn('price_weight', function($row) {
                $price = 'Rp. '.number_format($row->price_weight).',-';
                return $price;
            })
            ->addColumn('price_unit', function($row) {
                $price = 'Rp. '.number_format($row->price_unit).',-';
                return $price;
            })
            ->addColumn('action', function($row) {
                $btn = '<a href="'.route('shopservice.edit', $row->id).'" class="btn btn-primary btn-sm">
                            <i class="icon icon-edit"></i>
                        </a>';
                $btn .= '<a href="'.route('shopservice.delete', $row->id).'" class="btn btn-danger btn-sm">
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
        $services = Service::where('shop_id', Auth::user()->id)->get();
        $service = Service::find($id);
        return view('service.index', compact('services', 'service', 'status', 'orderCount'));
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
            'name' => 'required',
            'price_weight' => 'required',
            'price_unit' => 'required',
            'image' => 'mimes:png,jpg,jpeg,svg',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors);
        }

        if ($request->image <> '') {
            $image = $request->file('image');
            $namaImage = 'Service-'.Str::slug($request->get('name')).'-'.Str::random(5).'.'.$image->extension();
            $tujuan = 'images';
            $image->move(public_path($tujuan), $namaImage);
            $imagename = $tujuan.'/'.$namaImage;
        }

        $service = Service::find($id);
        $service->name = $request->get('name');
        $service->price_weight = $request->get('price_weight');
        $service->price_unit = $request->get('price_unit');
        $service->slug = Str::slug($request->get('name'));
        $service->description = $request->get('description');
        if ($request->image <> '') {
            File::delete($service->image);
            $service->image = $imagename;
        }
        $service->save();

        return redirect()->route('shopservice.show', Auth::user()->id)->with('success', 'Update service successful.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = Service::find($id);
        $service->delete();

        File::delete($service->image);

        return redirect()->route('shopservice.show', Auth::user()->id)->with('success', 'Delete service successful.');
    }
}
