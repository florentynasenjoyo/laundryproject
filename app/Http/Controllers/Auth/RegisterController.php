<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.register');
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
            'no_hp' => 'required',
            'whatsapp' => 'required',
            'gender' => 'required',
            'birthday' => 'required',
            'foto' => 'required|mimes:jpeg,jpg,png,svg,gif',
            'address' => 'required',
            'google_map' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors)->withInput($request->all());
        }

        if ($request->foto <> '') {
            $foto = $request->file('foto');
            $namafoto = 'Profile-'.strtolower(str_replace(' ', '-',$request->get('name'))).'-'.Str::random(5).'.'.$foto->extension();
            $tujuan = 'images';
            $foto->move(public_path($tujuan), $namafoto);
            $fotoname = $tujuan.'/'.$namafoto;
        }

        $user = User::create([
            'name' => $request->get('name'),
            'no_hp' => $request->get('no_hp'),
            'whatsapp' => $request->get('whatsapp'),
            'jns_kelamin' => $request->get('gender'),
            'tgl_lahir' => $request->get('birthday'),
            'foto' => $fotoname,
            'address' => $request->get('address'),
            'google_map' => $request->get('google_map'),
            'latitude' => $request->get('address_latitude'),
            'longitude' => $request->get('address_longitude'),
            'email' => $request->get('email'),
            'level_member' => 'silver',
            'password' => Hash::make($request->get('password')),
        ]);

        event(new Registered($user));

        auth()->login($user);
        Session::put('users', 'user');
        Session::put('login', TRUE);

        return redirect()->route('verification.notice')->with('success', 'Selamat pendaftaran berhasil.');
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
