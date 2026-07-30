<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{


    public function index(Request $request)
    {
        $search = $request->q;

        $roles = Role::when($search, function ($query) use ($search) {
            return $query->where("name", "LIKE", "%$search%");
        })->orderBy("id", "desc")->paginate(32);

        return view("pages.role-permission.index", compact("roles"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = DB::table("roles")->get();
        $permissions = Permission::get();

        return view("pages.role-permission.create", compact("roles", "permissions"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        DB::beginTransaction();

        try {

            $role = Role::create([
                'name' => $request->name
            ]);

            if ($request->permissions) {
                foreach ($request->permissions as $itempermissions) {
                    $permission = Permission::findById($itempermissions);

                    if ($permission) {
                        $role->givePermissionTo($permission);
                    }
                }
            }



            DB::commit();

            return redirect()->route("akses.role.index")->with("success", "Berhasil tambah data");
        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->back()->with("error", "Gagal simpan data ");
        }
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
        $roles = Role::findById($id);
        $permissions = Permission::get();

        return view('pages.role-permission.edit', [
            'itemRol' => $roles,
            'permissions' => $permissions
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string'
        ]);


        $role = Role::findById($id);
        if (!$role) {
            return redirect()->back()->with("message", "Data tidak di temukan");
        }

        $permissionName = [];
        if ($request->permissions) {
            foreach ($request->permissions as $itempermissions) {
                $permission = Permission::findById($itempermissions);

                if ($permission) {
                    array_push($permissionName, $permission->name);
                }
            }
        }

        $role->update(['name' => $request->name]);

        if (count($permissionName) > 0) {
            $permissions = Permission::whereIn('name', $permissionName)->get()->pluck("name");

            $role->syncPermissions($permissions);
        }

        return redirect()->route("akses.role.index")->with("success", "Berhasil update data");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);

        $role->delete();


        return redirect()->back()->with("success", "Berhasil hapus data");
    }
}
