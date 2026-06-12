<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Create permissions
        $permissions = [
            // Supplier Management
            'view_suppliers',
            'create_supplier',
            'edit_supplier',
            'delete_supplier',

            // Category Management
            'view_categories',
            'create_category',
            'edit_category',
            'delete_category',

            // Product Management
            'view_products',
            'create_product',
            'edit_product',
            'delete_product',

            // Order Management
            'view_orders',
            'create_order',
            'edit_order',
            'confirm_order',
            'complete_order',
            'delete_order',

            // User Management
            'view_users',
            'create_user',
            'edit_user',
            'delete_user',

            // Reports & Analytics
            'view_reports',
            'export_data',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $supplierRole = Role::firstOrCreate(['name' => 'supplier']);

        // Assign all permissions to super_admin
        $superAdminRole->syncPermissions($permissions);

        // Assign admin permissions (everything except user management)
        $adminPermissions = array_filter($permissions, function ($permission) {
            return !str_contains($permission, 'user');
        });
        $adminRole->syncPermissions($adminPermissions);

        // Assign supplier permissions (only view products and create orders)
        $supplierPermissions = [
            'view_products',
            'view_orders',
            'create_order',
        ];
        $supplierRole->syncPermissions($supplierPermissions);
    }
}

