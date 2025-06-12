<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Spatie\Permission\Models\Role;

#[Title('Gestão de Usuários')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingUserId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'roleFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function createUser()
    {
        $this->showCreateModal = true;
        $this->dispatch('create-user');
    }

    public function editUser($userId)
    {
        $this->editingUserId = $userId;
        $this->showEditModal = true;
        $this->dispatch('edit-user', userId: $userId);
    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);
        
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Você não pode excluir sua própria conta.');
            return;
        }

        $user->delete();
        session()->flash('message', 'Usuário excluído com sucesso.');
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->editingUserId = null;
    }

    public function render()
    {
        $query = User::with('roles');

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Apply role filter
        if ($this->roleFilter) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', $this->roleFilter);
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        $roles = Role::all();

        return view('livewire.users.index', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }
}