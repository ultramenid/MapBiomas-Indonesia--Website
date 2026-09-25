<?php

namespace App\Livewire\Cms;

use App\Models\TeamMember;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class TeamIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $category = '';

    public ?int $deleteId = null;

    public string $deleteName = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $member = TeamMember::find($id);

        if (! $member) {
            return;
        }

        $this->deleteId = $id;
        $this->deleteName = $member->name;
    }

    public function destroy(): void
    {
        if ($this->deleteId) {
            $member = TeamMember::find($this->deleteId);

            if ($uploaded = storage_media_path($member?->photo_path)) {
                @unlink($uploaded);
            }

            $member?->delete();
            Toaster::success('Team member deleted.');
        }

        $this->closeDelete();
    }

    public function closeDelete(): void
    {
        $this->reset('deleteId', 'deleteName');
    }

    public function render()
    {
        $members = TeamMember::query()
            ->when($this->category !== '', fn ($query) => $query->where('category', $this->category))
            ->when($this->search !== '', fn ($query) => $query->where(fn ($w) => $w
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('region', 'like', "%{$this->search}%")))
            ->orderBy('category')
            ->orderBy('sort')
            ->orderBy('id')
            ->paginate(10);

        return view('livewire.cms.team-index', ['members' => $members]);
    }
}
