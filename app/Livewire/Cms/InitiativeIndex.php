<?php

namespace App\Livewire\Cms;

use App\Models\Initiative;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;

class InitiativeIndex extends Component
{
    public ?int $deleteId = null;

    public string $deleteName = '';

    public function confirmDelete(int $id): void
    {
        $initiative = Initiative::find($id);

        if (! $initiative) {
            return;
        }

        $this->deleteId = $id;
        $this->deleteName = $initiative->name;
    }

    public function destroy(): void
    {
        if ($this->deleteId) {
            $initiative = Initiative::find($this->deleteId);

            if ($uploaded = storage_media_path($initiative?->logo_path)) {
                @unlink($uploaded);
            }

            $initiative?->delete();
            Toaster::success('Initiative deleted.');
        }

        $this->closeDelete();
    }

    public function closeDelete(): void
    {
        $this->reset('deleteId', 'deleteName');
    }

    public function render()
    {
        return view('livewire.cms.initiative-index', [
            'initiatives' => Initiative::orderBy('sort')->orderBy('id')->get(),
        ]);
    }
}
