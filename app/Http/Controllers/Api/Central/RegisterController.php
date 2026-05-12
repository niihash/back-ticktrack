<?php

namespace App\Http\Controllers\Api\Central;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'confirmed', 'min:6'],
            'subdomain' => ['required', 'string', 'alpha_dash:ascii', 'unique:tenants,id'],
        ]);


        try {

            DB::beginTransaction();

            // Create Tenant

            $tenant = Tenant::create([
                'id' => $data['subdomain'],
            ]);

            // Create Domain

            $baseDomain = config('session.domain');

            $domain = "{$data['subdomain']}{$baseDomain}";

            $tenant->domains()->create([
                'domain' => $domain
            ]);

            DB::commit();

            // Bootstrap Tenant

            $tenant->run(function () use ($data) {

                DB::transaction(function () use ($data) {
                    // Permissions

                    $permissions = [
                        'manage users',
                        'manage employees',
                        'manage work schedules',
                        'manage time entries',
                        'view reports',
                        'manage notifications',
                    ];

                    foreach ($permissions as $permission) {
                        Permission::create([
                            'name' => $permission,
                        ]);
                    }

                    // Roles

                    $adminRole = Role::create([
                        'name' => 'admin',
                    ]);

                    $adminRole->givePermissionTo(Permission::all());

                    $managerRole = Role::create([
                        'name' => 'manager',
                    ]);

                    $managerRole->givePermissionTo([
                        'manage employees',
                        'manage work schedules',
                        'manage time entries',
                        'view reports',
                        'manage notifications',
                    ]);

                    $employeeRole = Role::create([
                        'name' => 'employee',
                    ]);

                    $employeeRole->givePermissionTo([
                        'manage time entries',
                    ]);

                    // Create Admin User

                    $user = User::create([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'password' => Hash::make($data['password']),
                    ]);

                    $user->assignRole('admin');
                });
            });

            return response()->json([
                'message' => 'Tenant criado com sucesso',
                'tenant_url' => "http://{$domain}",
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
