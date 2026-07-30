<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $user = User::select('id', 'name', 'email', 'created_at', "phone")
            ->orderBy('id', 'desc')
            ->with("roles")
            ->get();

        return view("pages.user.index", [
            "user" => $user
        ]);
    }

    public function create()
    {
        $roles = Role::select('id', 'name')
            ->orderBy("name", "asc")
            ->get();

        return view("pages.user.create", [
            'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {


        $validated = $request->validate([
            'name' => 'required',
            'role' => 'required|array|min:1',
            'role.*' => 'exists:roles,id',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|unique:users,phone',
        ]);

        DB::beginTransaction();

        try {
            $newUser =  User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make('12345678'),
                'phone' => $validated['phone'] ?? null,
            ]);

            $role = DB::table('roles')->whereIn('id', $request->role)->pluck('name');
            $newUser->assignRole($role);

            DB::commit();
            return redirect()->route('akses.user.index')->with('success', 'User berhasil ditambahkan');
        } catch (Exception $th) {

            DB::rollBack();
            return redirect()->back()->with('error', 'User gagal ditambahkan')
                ->withErrors([
                    'msg_error' => 'User gagal ditambahkan'
                ]);
        }
    }

    public function edit(User $user)
    {
        $roles = Role::select('id', 'name')
            ->orderBy("name", "asc")
            ->get();

        return view("pages.user.edit", [
            'user' => $user,
            'roles' => $roles,
            'userRole' => $user->roles()->pluck('id')->toArray(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required',
            'role' => 'required|array|min:1',
            'role.*' => 'exists:roles,id',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|unique:users,phone,' . $user->id,
            'reset_password' => 'nullable',
        ]);

        DB::beginTransaction();

        try {
            $dataUpdate = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
            ];

            if ($request->reset_password) {
                $dataUpdate['password'] = Hash::make('12345678');
            }

            $user->update($dataUpdate);

            $role = DB::table('roles')->whereIn('id', $request->role)->pluck('name');
            $user->syncRoles($role);

            DB::commit();

            return redirect()->route('akses.user.index')->with('success', 'User berhasil diperbarui');
        } catch (Exception $th) {
            DB::rollBack();

            return redirect()->back()->with('error', 'User gagal diperbarui')->withInput();
        }
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('akses.user.index')->with('success', 'User berhasil dihapus');
    }
}
