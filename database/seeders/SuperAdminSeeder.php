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
        Role::firstOrCreate(['name' => 'Buyer']);
        Role::firstOrCreate(['name' => 'Seller']);

        $superAdmin = User::where('email', 'superadmin@demo.com')->first();

        if (!$superAdmin) {
            $superAdmin = User::create([
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'superadmin@demo.com',
                'password' => bcrypt('demo1234'),
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
