<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Driver;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('driver.auth.login');
    }

    public function postlogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors)->withInput($request->all());
        }

        $email = $request->get('email');
        $password = Hash::make($request->get('password'));
        if (Auth::guard('webdriver')->attempt($request->only('email', 'password'))) {
            Session::put('users', 'driver');
            Session::put('login_driver', TRUE);
            return redirect()->route('home');
        }

        return back()->with('danger', 'Data tidak ditemukan.')->withInput($request->all());
    }

    public function register()
    {
        return view('driver.auth.register');
    }

    public function postregister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'no_hp' => 'required|unique:drivers,no_hp',
            'email' => 'required|email|unique:drivers,email',
            'foto' => 'required|mimes:png,jpg,jpeg,svg,gif',
            'password' => 'required|confirmed|min:6'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors)->withInput($request->all());
        }

        if ($request->foto <> '') {
            $image = $request->file('foto');
            $namaImage = 'Driver-Profile-'.Str::slug($request->get('name')).'-'.Str::random(5).'.'.$image->extension();
            $tujuan = 'images';
            $image->move(public_path($tujuan), $namaImage);
            $imagename = $tujuan.'/'.$namaImage;
        }

        Driver::create([
            'name' => $request->get('nama'),
            'no_hp' => $request->get('no_hp'),
            'email' => $request->get('email'),
            'foto' => $imagename,
            'password' => Hash::make($request->get('password')),
        ]);

        return redirect()->route('driver.login')->with('success', 'Driver register successful.');
    }

    public function logout()
    {
        Session::flush();
        Auth::guard('webdriver')->logout();
        return redirect()->route('driver.login')->with('success', 'Berhasil keluar.');
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
