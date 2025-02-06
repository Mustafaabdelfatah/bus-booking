<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Position;
use Illuminate\Database\Seeder;
use App\Enums\PositionTitleEnum;
use App\Enums\EmployeeStatusEnum;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Services\Global\RoleService;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        // Remove the relationships from pivot tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        (new RoleService())->handle();



        Employee::query()->firstOrCreate([
            'email' => 'root@root.com',
        ], [
            'name' => 'root',
            'password' => '123456',
        ])->assignRole('root');

        Employee::query()->firstOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name' => 'admin',
            'password' => '123456',
        ])->assignRole('admin');
    }
}