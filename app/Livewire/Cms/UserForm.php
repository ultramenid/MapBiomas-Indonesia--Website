<?php

namespace App\Livewire\Cms;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class UserForm extends Component
{
    public ?User $user = null;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $passwordConfirmation = '';

    public string $role = 'editor';

    public function mount(?int $record = null): void
    {
        if ($record) {
            $this->user = User::findOrFail($record);
            $this->name = (string) $this->user->name;
            $this->email = (string) $this->user->email;
            $this->role = (string) $this->user->role_id;
        }
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user?->id)],
            'password' => [$this->user ? 'nullable' : 'required', 'string', 'min:8'],
            'passwordConfirmation' => [$this->user ? 'nullable' : 'required', 'same:password'],
            'role' => ['required', 'in:1,2'],
        ];
    }

    protected function messages(): array
    {
        return [
            'passwordConfirmation.same' => 'The password confirmation does not match.',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'passwordConfirmation' => 'password confirmation',
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role_id' => (int) $validated['role'],
        ];

        if ($validated['password'] !== null && $validated['password'] !== '') {
            $data['password'] = $validated['password']; // hashed via User cast
        }

        // Guard: demoting or changing the last remaining admin is blocked.
        if ($this->user && $this->user->isAdmin() && $data['role_id'] !== User::ROLE_ADMIN) {
            $otherAdmins = User::where('role_id', User::ROLE_ADMIN)->where('id', '!=', $this->user->id)->count();

            if ($otherAdmins === 0) {
                $this->addError('role', 'The last administrator cannot be demoted.');

                return;
            }
        }

        if ($this->user) {
            $this->user->update($data);
            Toaster::success('User updated.');
        } else {
            User::create($data);
            Toaster::success('User created.');
        }

        $this->redirect(route('cms.users.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.cms.user-form');
    }
}
