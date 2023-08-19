<?php

namespace App\Http\Controllers;

use Illuminate\Cache\RedisTagSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    public function index()
    {
        $data = DB::select("select a.*, b.nama as unit_name
        from users a
        join penyelenggara b on a.unit_id = b.id");
        $penyelenggara = DB::select("select * from penyelenggara");
        return view('user.index', compact('data', 'penyelenggara'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // validasi
        $request->validate([
            'role_name' => $request->role_name == 'superadmin' ? 'unique:users,role_name' : 'required',
            'username'  => 'required|string|unique:users,username',
            'email'     => 'required|string|unique:users,email',
            'password'  => 'required|string',
        ]);

        // insert tabel user
        $data = [
            'role_name' => $request->role_name,
            'username' => $request->username,
            'nama_petugas' => $request->nama_petugas,
            'email' => $request->email,
            'unit_id' => $request->unit,
            'password' => Hash::make($request->password),
        ];
        DB::table('users')->insert($data);

        // insert tabel penyelenggara
        // $penyelenggara = [
        //     'nama' => $request->unit,
        //     'color' => $request->color,
        // ];
        // DB::table('penyelenggara')->insert($penyelenggara);

        session()->flash('success', 'Berhasil menyimpan data');

        return redirect()->to('user');
    }

    public function update(Request $request)
    {
        $id = $request->id;
        
        $data = [
            'role_name' => $request->role_name,
            'username' => $request->username,
            'nama_petugas' => $request->nama_petugas,
            'email' => $request->email,
            'unit_id' => $request->unit,
            'password' => Hash::make($request->password),
        ];

        DB::table('users')
            ->where('id', $id)    
            ->update($data);

        session()->flash('success', 'Berhasil mengubah data');

        return redirect()->to('user');
    }
    
    public function delete($id)
    {
        DB::table('users')
            ->where('id', $id)
            ->delete();

        session()->flash('success', 'Berhasil menghapus data');

        return redirect()->to('user');
    }
}
