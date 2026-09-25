<?php

namespace App\Livewire\Cms;

use App\Models\Initiative;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;

class InitiativeForm extends Component
{
    use WithFileUploads;

    public ?Initiative $initiative = null;

    public string $name = '';

    public string $slug = '';

    public string $descriptionID = '';

    public string $descriptionEN = '';

    public string $platformUrl = '';

    public string $accentColor = '#9EC4AB';

    public ?string $sort = null;

    public bool $isActive = true;

    public $logo = null;

    public ?string $currentLogo = null;

    public bool $slugEdited = false;

    public function mount(?int $record = null): void
    {
        if ($record) {
            $this->initiative = Initiative::findOrFail($record);
            $this->name = (string) $this->initiative->name;
            $this->slug = (string) $this->initiative->slug;
            $this->descriptionID = (string) $this->initiative->description_id;
            $this->descriptionEN = (string) $this->initiative->description_en;
            $this->platformUrl = (string) $this->initiative->platform_url;
            $this->accentColor = (string) $this->initiative->accent_color;
            $this->sort = $this->initiative->sort !== 0 ? (string) $this->initiative->sort : null;
            $this->isActive = $this->initiative->is_active;
            $this->currentLogo = $this->initiative->logo_path;
            // Never clobber the stored slug of an existing initiative.
            $this->slugEdited = true;
        }
    }

    /** Keep the slug in sync with the name until the user customises it. */
    public function updatedName(string $value): void
    {
        if (! $this->slugEdited) {
            $this->slug = Str::slug($value);
        }
    }

    public function updatedSlug(): void
    {
        $this->slugEdited = true;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'alpha_dash'],
            'descriptionID' => ['required', 'string', 'max:2000'],
            'descriptionEN' => ['required', 'string', 'max:2000'],
            'platformUrl' => ['required', 'url', 'max:255'],
            'accentColor' => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'sort' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'isActive' => ['boolean'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'descriptionID' => 'description (Bahasa Indonesia)',
            'descriptionEN' => 'description (English)',
            'platformUrl' => 'platform URL',
            'logo' => 'logo',
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        $slug = $validated['slug'] !== '' ? $validated['slug'] : Str::slug($validated['name']);

        $data = [
            'name' => $validated['name'],
            'slug' => $slug,
            'description_id' => $validated['descriptionID'],
            'description_en' => $validated['descriptionEN'],
            'platform_url' => $validated['platformUrl'],
            'accent_color' => $validated['accentColor'],
            'sort' => (int) ($validated['sort'] ?? 0),
            'is_active' => $validated['isActive'],
        ];

        if ($this->logo) {
            $data['logo_path'] = 'storage/' . $this->logo->store('initiatives', 'public');

            if ($this->initiative && ($old = storage_media_path($this->initiative->logo_path))) {
                @unlink($old);
            }
        }

        if ($this->initiative) {
            $this->initiative->update($data);
            Toaster::success('Initiative updated.');
        } else {
            Initiative::create($data);
            Toaster::success('Initiative created.');
        }

        $this->redirect(route('cms.initiatives.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.cms.initiative-form');
    }
}
