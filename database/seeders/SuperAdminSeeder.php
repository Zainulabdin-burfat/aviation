<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'Super Admin']);
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Branch Manager']);
        Role::firstOrCreate(['name' => 'Delivery Agent']);

        $superAdmin = User::where('email', 'superadmin@knexpress.com')->first();

        if (!$superAdmin) {
            $superAdmin = User::create([
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'username' => 'superadmin@knexpress.com',
                'email' => 'superadmin@knexpress.com',
                'phone' => '12345678',
                'password' => bcrypt('knexpress'),
                'status' => 1,
                'email_verified_at' => now(),
            ]);
        }

        $permissions = Permission::all();

        $data = [];
        DB::table('permission_user')->delete();
        foreach ($permissions as $permission) {
            $data[] = [
                "user_id" => $superAdmin->id,
                "permission_id" => $permission->id
            ];
        }

        DB::table('permission_user')->insert($data);



    }
}
