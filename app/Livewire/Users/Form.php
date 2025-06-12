<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Form extends Component
{
    public $userId = null;
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $selectedRoles = [];
    public $isEditMode = false;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->userId),
            ],
            'selectedRoles' => 'required|array|min:1',
            'selectedRoles.*' => 'exists:roles,name',
        ];

        if (!$this->isEditMode || $this->password) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        return $rules;
    }

    protected $messages = [
        'selectedRoles.required' => 'Por favor, selecione pelo menos um papel.',
        'selectedRoles.min' => 'Por favor, selecione pelo menos um papel.',
    ];

    #[On('edit-user')]
    public function editUser($userId)
    {
        $this->resetForm();
        $this->userId = $userId;
        $this->isEditMode = true;

        $user = User::with('roles')->findOrFail($userId);
        $this->name = $user->name;
        $this->email = $user->email;
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
    }

    #[On('create-user')]
    public function createUser()
    {
        $this->resetForm();
        $this->isEditMode = false;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditMode) {
            $user = User::findOrFail($this->userId);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);

            if ($this->password) {
                $user->update([
                    'password' => Hash::make($this->password),
                ]);
            }

            $message = 'Usuário atualizado com sucesso.';
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            $message = 'Usuário criado com sucesso.';
        }

        // Sync roles
        $user->syncRoles($this->selectedRoles);

        session()->flash('message', $message);
        $this->dispatch('user-saved');
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->selectedRoles = [];
        $this->isEditMode = false;
        $this->resetValidation();
    }

    public function render()
    {
        $roles = Role::all();
        
        return view('livewire.users.form', [
            'roles' => $roles,
        ]);
    }
}