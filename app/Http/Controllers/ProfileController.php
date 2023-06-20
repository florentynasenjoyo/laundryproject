<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shop;
use App\Models\Openinghour;
use App\Models\Rekening;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProfileController extends Controller
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
        return view('profile.index', compact('orderCount'));
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
        $profile = User::find(Auth::guard('web')->user()->id);
        return view('profile.edit', compact('profile', 'orderCount'));
    }

    public function membercard($id)
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
        $profile = User::find(Auth::guard('web')->user()->id);
        return view('profile.member-card', compact('profile', 'orderCount'));
    }

    public function editpassword()
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
        return view('profile.change-password', compact('orderCount'));
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
            'no_hp' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors);
        }

        if ($request->foto <> '') {
            $foto = $request->file('foto');
            $namafoto = 'Profile-'.strtolower(str_replace(' ', '-',$request->get('name'))).'-'.Str::random(5).'.'.$foto->extension();
            $tujuan = 'images';
            $foto->move(public_path($tujuan), $namafoto);
            $fotoname = $tujuan.'/'.$namafoto;
        }

        if (Session::get('users')=='user') {
            $profile = User::find($id);
            $profile->name = $request->get('name');
            $profile->no_hp = $request->get('no_hp');
            $profile->email = $request->get('email');
            $profile->tgl_lahir = $request->get('tgl_lahir');
            $profile->jns_kelamin = $request->get('jns_kelamin');
            if ($request->foto <> '') {
                $profile->foto = $fotoname;
            }
            $profile->save();
        } else {
            $profile = Shop::find($id);
            $profile->name = $request->get('name');
            $profile->no_hp = $request->get('no_hp');
            $profile->email = $request->get('email');
            if ($request->foto <> '') {
                $profile->logo = $fotoname;
            }
            $profile->save();
        }

        return redirect()->route('profile.edit', $id)->with('success', 'Change profile successful.');
    }

    public function updatepassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:6'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors);
        }

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->with('danger', 'Current Password Doesn`t Match!!');
        }

        if (Session::get('users')=='user') {
            $profile = User::find(Auth::user()->id);
            $profile->password = Hash::make($request->get('new_password'));
            $profile->save();
        } else {
            $profile = Shop::find(Auth::user()->id);
            $profile->password = Hash::make($request->get('new_password'));
            $profile->save();
        }

        return redirect()->route('profile.index')->with('success', 'Change password successful.');
    }

    public function updateaddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors);
        }

        if (Session::get('users')=='user') {
            $profile = User::find(Auth::user()->id);
            $profile->address = $request->get('address');
            $profile->latitude = $request->get('address_latitude');
            $profile->longitude = $request->get('address_longitude');
            $profile->save();
        } else {
            $profile = Shop::find(Auth::user()->id);
            $profile->address = $request->get('address');
            $profile->latitude = $request->get('address_latitude');
            $profile->longitude = $request->get('address_longitude');
            $profile->save();
        }

        return redirect()->route('profile.edit', Auth::user()->id)->with('success', 'Address update successful.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Session::get('users')=='user') {
            $profile = User::find($id);
            $profile->delete();
        } else {
            $profile = Shop::find($id);
            $profile->delete();
        }

        File::delete($profile->foto);

        if (Session::get('users')=='user') {
            return redirect()->route('logout')->with('success', 'Delete Account succsessful.');
        } else {
            return redirect()->route('shop.logout')->with('success', 'Delete Account succsessful.');
        }
    }
}
