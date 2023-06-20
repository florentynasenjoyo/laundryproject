<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shop;
use App\Models\Driver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\Bank;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orderCount = Order::where('driver_id', Auth::user()->id)->where('status_tracking', '0')->count();
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
        $orderCount = Order::where('driver_id', Auth::user()->id)->where('status_tracking', '0')->count();
        $profile = Driver::find($id);
        $bank = Bank::get();
        return view('profile.edit', compact('profile', 'orderCount', 'bank'));
    }

    public function editpassword()
    {
        $orderCount = Order::where('driver_id', Auth::user()->id)->where('status_tracking', '0')->count();
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
            'whatsapp' => 'required',
            'name_rekening' => 'required',
            'no_rekening' => 'required',
            'bank' => 'required',
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

        $profile = Driver::find($id);
        $profile->name = $request->get('name');
        $profile->no_hp = $request->get('no_hp');
        $profile->whatsapp = $request->get('whatsapp');
        $profile->name_rekening = $request->get('name_rekening');
        $profile->no_rekening = $request->get('no_rekening');
        $profile->bank_id = $request->get('bank');
        $profile->email = $request->get('email');
        if ($request->foto <> '') {
            $profile->foto = $fotoname;
        }
        $profile->save();

        return redirect()->route('driverprofile.edit', $id)->with('success', 'Change profile successful.');
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

        $profile = Driver::find(Auth::user()->id);
        $profile->password = Hash::make($request->get('new_password'));
        $profile->save();

        return redirect()->route('driverprofile.index')->with('success', 'Change password successful.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $profile = Driver::find($id);
        $profile->delete();

        File::delete($profile->foto);

        return redirect()->route('driver.logout')->with('success', 'Delete Account succsessful.');
    }
}
