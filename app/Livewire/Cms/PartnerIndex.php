<?php

namespace App\Livewire\Cms;

use App\Models\Partner;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class PartnerIndex extends Component
{
    public ?int $deleteId = null;

    public string $deleteName = '';

    public function confirmDelete(int $id): void
    {
        $partner = Partner::find($id);

        if (! $partner) {
            return;
        }

        $this->deleteId = $id;
        $this->deleteName = $partner->name;
    }

    public function destroy(): void
    {
        if ($this->deleteId) {
            $partner = Partner::find($this->deleteId);

            if ($uploaded = storage_media_path($partner?->logo_path)) {
                @unlink($uploaded);
            }

            $partner?->delete();
            Toaster::success('Partner deleted.');
        }

        $this->closeDelete();
    }

    public function closeDelete(): void
    {
        $this->reset('deleteId', 'deleteName');
    }

    public function render()
    {
        return view('livewire.cms.partner-index', [
            'partners' => Partner::orderBy('category')->orderBy('sort')->orderBy('id')->get(),
        ]);
    }
}
