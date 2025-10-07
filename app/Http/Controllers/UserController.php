<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        confirmDelete('Hapus User' , 'Apakah anda yakin Ingin Menghapus User ini?');
        return view('users.index', compact('users'));
    }

    public function store(Request $request){
        $id = $request->id;
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
        ],[
            'name.required' => 'Nama Harus Diisi',
            'email.required' => 'Email Harus Diisi',
            'email.email' => 'Email Tidak Valid',
            'email.unique' => 'Email Sudah Ke Daftar'

        ]);
        $newRequest = $request->all();

        if(!$id) {
            $newRequest['password'] = Hash::make('12345678');
        }

        User::updateOrCreate(['id' => $id], $newRequest);
        toast()->success('User Berhasil Disimpan');
        return redirect()->route('users.index');
    }

    public function gantiPassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ],[
            'old_password.required' => 'Password Lama Harus Diisi',
            'password.required' => 'Password Baru Harus Diisi',
            'password.min' => 'Password Baru Minimal 8 Karakter',
            'password.confirmed' => 'Password Baru Tidak Sama Dengan Konfirmasi Password',
        ]);

        $user =User::find(Auth::id());


        if(!Hash::check($request->old_password, $user->password)) {
            toast()->error('password lama tidak sesuai');
            return redirect()->route('dashboard');
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        toast()->success('Password Berhasil Diubah');
        return redirect()->route('dashboard');
    }

    public function destroy(String $id)
    {
        $user = User::find($id);

        if(Auth::id() == $id) {
            toast()->error('Tidak Dapat Menghapus Akun Yang Sedang Login');
            return redirect()->route('users.index');
        }

        $user->delete();
        toast()->success('User Berhasil Dihapus');
        return redirect()->route('users.index');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'id'  => 'required'
        ]);

        $user = User::find($request->id);
        $user->update([
            'password' => Hash::make('12345678')
        ]);
        toast()->success('Password Berhasil Diriset');
        return redirect()->route('users.index');
    }
}
