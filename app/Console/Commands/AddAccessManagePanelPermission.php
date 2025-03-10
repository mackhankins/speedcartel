<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddAccessManagePanelPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-access-manage-panel-permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add the access_manage_panel permission and assign it to super_admin role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Adding access_manage_panel permission...');
        
        // Check if permission already exists
        $permission = Permission::where('name', 'access_manage_panel')->first();
        
        if (!$permission) {
            // Create the permission
            $permission = Permission::create([
                'name' => 'access_manage_panel',
                'guard_name' => 'web',
            ]);
            
            $this->info('Permission access_manage_panel created successfully.');
        } else {
            $this->info('Permission access_manage_panel already exists.');
        }
        
        // Assign permission to super_admin role
        $superAdminRole = Role::where('name', 'super_admin')->first();
        
        if ($superAdminRole) {
            if (!$superAdminRole->hasPermissionTo('access_manage_panel')) {
                $superAdminRole->givePermissionTo('access_manage_panel');
                $this->info('Permission assigned to super_admin role.');
            } else {
                $this->info('Permission already assigned to super_admin role.');
            }
        } else {
            $this->warn('super_admin role not found. Permission was not assigned.');
        }
        
        return 0;
    }
}
