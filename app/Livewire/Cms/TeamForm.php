<?php

namespace App\Livewire\Cms;

use App\Models\TeamMember;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;

class TeamForm extends Component
{
    use WithFileUploads;

    public ?TeamMember $member = null;

    public string $category = TeamMember::CATEGORY_TECHNICAL;

    public string $teamGroup = '';

    public string $region = '';

    public string $name = '';

    public string $positionID = '';

    public string $positionEN = '';

    public string $email = '';

    public string $bioID = '';

    public string $bioEN = '';

    public array $collections = [
        'landy' => [],
        'fire' => [],
        'alerta' => [],
    ];

    /** Number of collection checkboxes shown per initiative (baseline or grown via addCollection). */
    public array $collectionCounts = [];

    public ?string $sort = null;

    public bool $isActive = true;

    public $photo = null;

    public ?string $currentPhoto = null;

    public function mount(?int $record = null): void
    {
        $this->collectionCounts = TeamMember::collectionMaxInUse();

        if ($record) {
            $this->member = TeamMember::findOrFail($record);
            $this->category = $this->member->category;
            $this->teamGroup = (string) $this->member->team_group;
            $this->region = (string) $this->member->region;
            $this->name = (string) $this->member->name;
            $this->positionID = (string) $this->member->position_id;
            $this->positionEN = (string) $this->member->position_en;
            $this->email = (string) $this->member->email;
            $this->bioID = (string) $this->member->bio_id;
            $this->bioEN = (string) $this->member->bio_en;
            $this->collections = array_merge($this->collections, $this->member->collections ?? []);
            $this->sort = $this->member->sort !== 0 ? (string) $this->member->sort : null;
            $this->isActive = $this->member->is_active;
            $this->currentPhoto = $this->member->photo_path;
        }
    }

    public function addCollection(string $initiative): void
    {
        if (array_key_exists($initiative, $this->collectionCounts)) {
            $this->collectionCounts[$initiative]++;
        }
    }

    protected function rules(): array
    {
        return [
            'category' => ['required', 'in:technical,scientific'],
            'teamGroup' => [
                'nullable',
                'in:koordinator,inti,regio',
                'required_if:category,technical',
                'prohibited_if:category,scientific',
            ],
            'region' => ['nullable', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'positionID' => ['nullable', 'string', 'max:255'],
            'positionEN' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'bioID' => ['nullable', 'string'],
            'bioEN' => ['nullable', 'string'],
            'collections' => ['array'],
            'collections.*' => ['array'],
            'collections.*.*' => ['integer', 'min:1', 'max:99'],
            'sort' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'isActive' => ['boolean'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'teamGroup' => 'team group',
            'positionID' => 'position (Bahasa Indonesia)',
            'positionEN' => 'position (English)',
            'bioID' => 'bio (Bahasa Indonesia)',
            'bioEN' => 'bio (English)',
            'photo' => 'photo',
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        $isTechnical = $validated['category'] === TeamMember::CATEGORY_TECHNICAL;

        $data = [
            'category' => $validated['category'],
            'team_group' => $isTechnical ? $validated['teamGroup'] : null,
            'region' => $validated['region'] ?: null,
            'name' => $validated['name'],
            'position_id' => $validated['positionID'] ?: null,
            'position_en' => $validated['positionEN'] ?: null,
            'email' => $validated['email'] ?: null,
            'bio_id' => $validated['bioID'] ?: null,
            'bio_en' => $validated['bioEN'] ?: null,
            'collections' => $isTechnical
                ? array_map(fn ($nums) => array_values(array_map('intval', $nums)), $this->collections)
                : null,
            'sort' => (int) ($validated['sort'] ?? 0),
            'is_active' => $validated['isActive'],
        ];

        if ($this->photo) {
            $data['photo_path'] = 'storage/' . $this->photo->store('team', 'public');

            if ($this->member && ($old = storage_media_path($this->member->photo_path))) {
                @unlink($old);
            }
        }

        if ($this->member) {
            $this->member->update($data);
            Toaster::success('Team member updated.');
        } else {
            TeamMember::create($data);
            Toaster::success('Team member created.');
        }

        $this->redirect(route('cms.team.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.cms.team-form', [
            'collectionMax' => TeamMember::COLLECTION_MAX,
        ]);
    }
}
