<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::when($request->search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                ->orWhere('nomor_induk', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('role', 'like', "%{$search}%");
        })->paginate(15);

        return view('laboran/kelolaAsisten_lab', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'nomor_induk' => 'required|string|max:255|unique:users',
            'nama' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'password' => 'required|string|max:25|min:8',
            'no_hp' => 'required|string|max:14',
            'role' => 'required|string|max:255',
        ], [
            'nomor_induk.unique' => 'Nomor Induk Ini Telah Dipakai, Harap Mengunakan Nomor Induk Lain.',
            'nomor_induk.required' => 'Nomor Induk harus diisi.',
            'nama.required' => 'Nama harus diisi.',
            'email.required'      => 'Email harus diisi.',
            'password.required' => 'Password harus diisi.',
            'no_hp.required' => 'Nomor Handphone harus diisi.',
            'role.required' => 'Role User harus diisi.',
        ]);

        User::create([
           'nomor_induk' => $request->nomor_induk,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => $request->password,
            'nohp' => $request->no_hp,
            'role' => $request->role,
        ]);

        return redirect('/admin/user')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validator = $request->validate([
            'nomor_induk' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'no_hp' => 'required|string|max:14',
            'role' => 'required|string|max:255',
        ], [
            'nomor_induk.unique' => 'Nomor Induk Ini Telah Dipakai, Harap Mengunakan Nomor Induk Lain.',
            'nomor_induk.required' => 'Nomor Induk harus diisi.',
            'nama.required' => 'Nama harus diisi.',
            'email.required'      => 'Email harus diisi.',
            'no_hp.required' => 'Nomor Handphone harus diisi.',
            'role.required' => 'Role User harus diisi.',
        ]);

        $user->update([
           'nomor_induk' => $request->nomor_induk,
            'nama' => $request->nama,
            'email' => $request->email,
            'nohp' => $request->no_hp,
            'role' => $request->role,
        ]);

        if ($request->password) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect('/admin/user')->with('success', 'Pengguna berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        $user->delete();
        return redirect('/admin/user')->with('success', 'Pengguna berhasil dihapus!');
    }
}
