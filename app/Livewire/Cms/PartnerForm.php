<?php

namespace App\Livewire\Cms;

use App\Models\Partner;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;

class PartnerForm extends Component
{
    use WithFileUploads;

    public ?Partner $partner = null;

    public string $name = '';

    public string $url = '';

    public string $category = Partner::CATEGORY_COCREATOR;

    public ?string $sort = null;

    public bool $isActive = true;

    public $logo = null;

    public ?string $currentLogo = null;

    public function mount(?int $record = null): void
    {
        if ($record) {
            $this->partner = Partner::findOrFail($record);
            $this->name = (string) $this->partner->name;
            $this->url = (string) $this->partner->url;
            $this->category = $this->partner->category;
            $this->sort = $this->partner->sort !== 0 ? (string) $this->partner->sort : null;
            $this->isActive = $this->partner->is_active;
            $this->currentLogo = $this->partner->logo_path;
        }
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'url', 'max:255'],
            'category' => ['required', 'in:cocreator,supported'],
            'sort' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'isActive' => ['boolean'],
            'logo' => [$this->partner ? 'nullable' : 'required', 'image', 'max:2048'],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'url' => 'website URL',
            'logo' => 'logo',
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        $data = [
            'name' => $validated['name'],
            'url' => $validated['url'] ?: null,
            'category' => $validated['category'],
            'sort' => (int) ($validated['sort'] ?? 0),
            'is_active' => $validated['isActive'],
        ];

        if ($this->logo) {
            $data['logo_path'] = 'storage/' . $this->logo->store('partners', 'public');

            if ($this->partner && ($old = storage_media_path($this->partner->logo_path))) {
                @unlink($old);
            }
        }

        if ($this->partner) {
            $this->partner->update($data);
            Toaster::success('Partner updated.');
        } else {
            Partner::create($data);
            Toaster::success('Partner created.');
        }

        $this->redirect(route('cms.partners.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.cms.partner-form');
    }
}
