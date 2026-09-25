<?php

namespace App\Livewire\Cms;

use App\Models\User;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class UserIndex extends Component
{
    public ?int $deleteId = null;

    public string $deleteName = '';

    public function confirmDelete(int $id): void
    {
        $user = User::find($id);

        if (! $user) {
            return;
        }

        $this->deleteId = $id;
        $this->deleteName = $user->name;
    }

    public function destroy(): void
    {
        if (! $this->deleteId) {
            $this->closeDelete();

            return;
        }

        $user = User::find($this->deleteId);

        if (! $user) {
            $this->closeDelete();

            return;
        }

        if ($user->id === auth()->id()) {
            Toaster::error('You cannot delete your own account.');

            return;
        }

        if ($user->isAdmin() && User::where('role_id', User::ROLE_ADMIN)->where('id', '!=', $user->id)->count() === 0) {
            Toaster::error('The last administrator cannot be deleted.');

            return;
        }

        $user->delete();
        Toaster::success('User deleted.');
        $this->closeDelete();
    }

    public function closeDelete(): void
    {
        $this->reset('deleteId', 'deleteName');
    }

    public function render()
    {
        return view('livewire.cms.user-index', [
            'users' => User::orderBy('id')->get(),
        ]);
    }
}
