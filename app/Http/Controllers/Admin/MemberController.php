<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\DataTables;
use App\Models\User;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $members = User::get();
        return view('admin.member.index', compact('members'));
    }

    public function listData()
    {
        $data = User::query();
        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('foto', function($row) {
                if ($row->foto == NULL || $row->foto == '') {
                    $logo = '<img src="'.url('/images/no-image.jpg').'" width="70">';
                } else {
                    $logo = '<img src="'.url($row->foto).'" width="70">';
                }
                return $logo;
            })
            ->addColumn('action', function($row) {
                // $btn = '<a href="'.route('admin.member.edit', $row->id).'" class="btn btn-primary btn-sm" style="margin-right:10px;">
                //             <i class="fa fa-edit"></i>
                //         </a>';
                $btn = '<a href="'.route('admin.member.delete', $row->id).'" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i>
                        </a>';
                return $btn;
            })
            ->rawColumns(['action', 'foto'])
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
        $user = User::find($id);
        $user->delete();

        File::delete($user->foto);

        return redirect()->route('admin.member')->with('success', 'Delete Member Successful.');
    }
}
