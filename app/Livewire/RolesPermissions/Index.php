<?php

namespace App\Livewire\RolesPermissions;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\Attributes\Title;

#[Title('Roles & Permissions')]
class Index extends Component
{
    public $roles;
    public $permissions;
    public $selectedRole = null;
    public $newPermissionName = '';
    public $openAccordions = [];

    protected $rules = [
        'newPermissionName' => 'required|string|unique:permissions,name',
    ];

    protected $messages = [
        'newPermissionName.required' => 'Permission name is required.',
        'newPermissionName.unique' => 'This permission already exists.',
    ];

    public function mount()
    {
        $this->loadRolesAndPermissions();
    }

    public function loadRolesAndPermissions()
    {
        $this->roles = Role::with('permissions')->get();
        $this->permissions = Permission::orderBy('name')->get();
    }

    public function toggleAccordion($roleId)
    {
        if (in_array($roleId, $this->openAccordions)) {
            $this->openAccordions = array_diff($this->openAccordions, [$roleId]);
        } else {
            $this->openAccordions[] = $roleId;
        }
    }

    public function createPermission()
    {
        $this->validate();

        Permission::create(['name' => $this->newPermissionName]);

        $this->newPermissionName = '';
        $this->loadRolesAndPermissions();

        session()->flash('message', 'Permission created successfully.');
    }

    public function togglePermission($roleId, $permissionId)
    {
        $role = Role::findOrFail($roleId);
        $permission = Permission::findOrFail($permissionId);

        if ($role->hasPermissionTo($permission)) {
            $role->revokePermissionTo($permission);
        } else {
            $role->givePermissionTo($permission);
        }

        $this->loadRolesAndPermissions();
    }

    public function deletePermission($permissionId)
    {
        $permission = Permission::findOrFail($permissionId);
        
        // Check if permission is assigned to any roles
        if ($permission->roles()->count() > 0) {
            session()->flash('error', 'Cannot delete permission that is assigned to roles.');
            return;
        }

        $permission->delete();
        $this->loadRolesAndPermissions();
        
        session()->flash('message', 'Permission deleted successfully.');
    }

    public function render()
    {
        return view('livewire.roles-permissions.index');
    }
}