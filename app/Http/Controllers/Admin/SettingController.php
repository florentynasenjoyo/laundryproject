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
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.setting.index');
    }

    public function listData()
    {
        $data = Setting::query();
        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('shipping_cost', function($row) {
                $cost = 'Rp. '.number_format($row->shipping_cost);
                return $cost;
            })
            ->addColumn('logo', function($row) {
                $logo = '<img src="'.url($row->logo).'" width="70">';
                return $logo;
            })
            ->addColumn('action', function($row) {
                $btn = '<a href="'.route('admin.setting.edit', $row->id).'" class="btn btn-primary btn-sm" style="margin-right:10px;">
                            <i class="fa fa-edit"></i>
                        </a>';
                return $btn;
            })
            ->rawColumns(['action', 'logo'])
            ->make(true);

        return $datatables;
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
        $setting = Setting::find($id);
        return view('admin.setting.edit', compact('setting'));
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
            'website_name' => 'required',
            'tagline' => 'required',
            'email' => 'required|email',
            'shipping_cost' => 'required',
            'no_telp' => 'required',
            'whatsapp' => 'required',
            'image_about' => 'mimes:jpg,svg,png,jpeg',
            'logo' => 'mimes:jpg,svg,png,jpeg',
            'about' => 'required',
            'address' => 'required',
            'google_map' => 'required',
            'address_latitude' => 'required',
            'address_longitude' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors)->withInput($request->all());
        }

        if ($request->image_about <> '') {
            $foto = $request->file('image_about');
            $namafoto = 'About-'.strtolower(str_replace(' ', '-',$request->get('website_name'))).'-'.Str::random(5).'.'.$foto->extension();
            $tujuan = 'images';
            $foto->move(public_path($tujuan), $namafoto);
            $fotoname = $tujuan.'/'.$namafoto;
        }

        if ($request->logo <> '') {
            $logo = $request->file('logo');
            $namalogo = 'Logo-'.strtolower(str_replace(' ', '-',$request->get('website_name'))).'-'.Str::random(5).'.'.$logo->extension();
            $tujuan = 'images';
            $logo->move(public_path($tujuan), $namalogo);
            $logoname = $tujuan.'/'.$namalogo;
        }

        $setting = Setting::find($id);
        $setting->website_name = $request->get('website_name');
        $setting->tagline = $request->get('tagline');
        $setting->email = $request->get('email');
        $setting->shipping_cost = $request->get('shipping_cost');
        $setting->no_telp = $request->get('no_telp');
        $setting->whatsapp = $request->get('whatsapp');
        $setting->about = $request->get('about');
        if ($request->image_about <> '') {
            $setting->image_about = $fotoname;
        }
        if ($request->logo <> '') {
            $setting->logo = $logoname;
        }
        $setting->address = $request->get('address');
        $setting->google_map = $request->get('google_map');
        $setting->latitude = $request->get('address_latitude');
        $setting->longitude = $request->get('address_longitude');
        $setting->save();

        return redirect()->route('admin.setting')->with('success', 'Setting update successful.');
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
