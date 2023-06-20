<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Bank;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banks = Bank::get();
        return view('admin.bank.index', compact('banks'));
    }

    public function listData()
    {
        $data = Bank::query();
        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('logo', function($row) {
                $logo = '<img src="'.url($row->logo).'" width="70">';
                return $logo;
            })
            ->addColumn('action', function($row) {
                $btn = '<a href="'.route('admin.bank.edit', $row->id).'" class="btn btn-primary btn-sm" style="margin-right:10px;">
                            <i class="fa fa-edit"></i>
                        </a>';
                $btn .= '<a href="'.route('admin.bank.delete', $row->id).'" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i>
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
        return view('admin.bank.add');
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
            'name_rekening' => 'required',
            'no_rekening' => 'required',
            'logo' => 'mimes:png,jpg,jpeg,svg'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors)->withInput($request->all());
        }

        if ($request->logo <> '') {
            $foto = $request->file('logo');
            $namafoto = 'Bank-'.strtolower(str_replace(' ', '-',$request->get('name'))).'-'.Str::random(5).'.'.$foto->extension();
            $tujuan = 'images';
            $foto->move(public_path($tujuan), $namafoto);
            $fotoname = $tujuan.'/'.$namafoto;
        }

        Bank::create([
            'name' => $request->get('name'),
            'name_rekening' => $request->get('name_rekening'),
            'no_rekening' => $request->get('no_rekening'),
            'logo' => $fotoname
        ]);

        return redirect()->route('admin.bank')->with('success', 'Add bank successful.');
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
        $bank = Bank::find($id);
        return view('admin.bank.edit', compact('bank'));
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
            'name_rekening' => 'required',
            'no_rekening' => 'required',
            'logo' => 'mimes:png,jpg,jpeg,svg'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors', $errors);
        }

        if ($request->logo <> '') {
            $foto = $request->file('logo');
            $namafoto = 'Bank-'.strtolower(str_replace(' ', '-',$request->get('name'))).'-'.Str::random(5).'.'.$foto->extension();
            $tujuan = 'images';
            $foto->move(public_path($tujuan), $namafoto);
            $fotoname = $tujuan.'/'.$namafoto;
        }

        $bank = Bank::find($id);
        $bank->name = $request->get('name');
        $bank->name_rekening = $request->get('name_rekening');
        $bank->no_rekening = $request->get('no_rekening');
        if ($request->logo <> '') {
            File::delete($bank->logo);
            $bank->logo = $fotoname;
        }
        $bank->save();

        return redirect()->route('admin.bank')->with('success', 'Update bank successful.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bank = Bank::find($id);
        $bank->delete();

        File::delete($bank->logo);

        return redirect()->route('admin.bank')->with('success', 'Delete bank successful.');
    }
}
