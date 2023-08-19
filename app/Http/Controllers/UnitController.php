<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    public function index()
    {
        $data = DB::select("select * from penyelenggara");
        return view('unit.index', compact('data'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama'      => 'required|unique:penyelenggara,nama',
            'color'     => 'required|unique:penyelenggara,color',
        ]);

        // insert tabel user
        $data = [
            'nama' => $request->nama,
            'color' => $request->color,
        ];
        DB::table('penyelenggara')->insert($data);

        session()->flash('success', 'Berhasil menyimpan data');

        return redirect()->to('unit');
    }

    public function update(Request $request)
    {
        $id = $request->id;
        
        $data = [
            'nama' => $request->nama,
            'color' => $request->color,
        ];

        DB::table('penyelenggara')
            ->where('id', $id)    
            ->update($data);

        session()->flash('success', 'Berhasil mengubah data');

        return redirect()->to('unit');
    }
    
    public function delete($id)
    {
        DB::table('penyelenggara')
            ->where('id', $id)
            ->delete();

        session()->flash('success', 'Berhasil menghapus data');

        return redirect()->to('unit');
    }
}
