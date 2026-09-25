<?php

namespace App\Livewire\Cms;

use App\Models\NewsItem;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;

class NewsForm extends Component
{
    use WithFileUploads;

    public ?NewsItem $news = null;

    public string $type = NewsItem::TYPE_INTERNAL;

    public string $titleID = '';

    public string $titleEN = '';

    public string $slug = '';

    public string $excerptID = '';

    public string $excerptEN = '';

    public string $externalUrl = '';

    public string $contentID = '';

    public string $contentEN = '';

    public ?string $publishedAt = null;

    public bool $isPublished = true;

    public $image = null;

    public ?string $currentImage = null;

    public bool $slugEdited = false;

    public function mount(?int $record = null): void
    {
        if ($record) {
            $this->news = NewsItem::findOrFail($record);
            $this->type = $this->news->type;
            $this->titleID = (string) $this->news->title_id;
            $this->titleEN = (string) $this->news->title_en;
            $this->slug = (string) $this->news->slug;
            $this->excerptID = (string) $this->news->excerpt_id;
            $this->excerptEN = (string) $this->news->excerpt_en;
            $this->externalUrl = (string) $this->news->external_url;
            $this->contentID = (string) $this->news->content_id;
            $this->contentEN = (string) $this->news->content_en;
            $this->publishedAt = $this->news->published_at?->format('Y-m-d');
            $this->isPublished = $this->news->is_published;
            $this->currentImage = $this->news->image_path;
            // Never clobber the stored slug of an existing article.
            $this->slugEdited = true;
        } else {
            $this->publishedAt = now()->format('Y-m-d');
        }
    }

    /** Keep the slug in sync with the English title until the user customises it. */
    public function updatedTitleEN(string $value): void
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
            'type' => ['required', 'in:internal,external'],
            'titleID' => ['required', 'string', 'max:255'],
            'titleEN' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash'],
            'excerptID' => ['nullable', 'string'],
            'excerptEN' => ['nullable', 'string'],
            'externalUrl' => ['nullable', 'url', 'required_if:type,external'],
            'contentID' => [$this->type === NewsItem::TYPE_INTERNAL ? 'required' : 'nullable', 'string'],
            'contentEN' => [$this->type === NewsItem::TYPE_INTERNAL ? 'required' : 'nullable', 'string'],
            'publishedAt' => ['nullable', 'date'],
            'isPublished' => ['boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'titleID' => 'title (Bahasa Indonesia)',
            'titleEN' => 'title (English)',
            'excerptID' => 'excerpt (Bahasa Indonesia)',
            'excerptEN' => 'excerpt (English)',
            'contentID' => 'content (Bahasa Indonesia)',
            'contentEN' => 'content (English)',
            'externalUrl' => 'external URL',
            'image' => 'image',
        ];
    }

    public function updatedType(): void
    {
        // Re-evaluate conditional validation messages when switching type.
        $this->clearValidation();
    }

    public function save(): void
    {
        $validated = $this->validate();

        $slug = $validated['slug'] !== ''
            ? $validated['slug']
            : Str::slug($validated['titleEN'] ?: $validated['titleID']);

        $base = [
            'type' => $validated['type'],
            'title_id' => $validated['titleID'],
            'title_en' => $validated['titleEN'],
            'slug' => $slug,
            'excerpt_id' => $validated['excerptID'] ?: null,
            'excerpt_en' => $validated['excerptEN'] ?: null,
            'external_url' => $validated['type'] === NewsItem::TYPE_EXTERNAL ? $validated['externalUrl'] : null,
            'content_id' => $validated['type'] === NewsItem::TYPE_INTERNAL ? $validated['contentID'] : null,
            'content_en' => $validated['type'] === NewsItem::TYPE_INTERNAL ? $validated['contentEN'] : null,
            'published_at' => $validated['publishedAt'],
            'is_published' => $validated['isPublished'],
        ];

        if ($this->image) {
            $base['image_path'] = 'storage/' . $this->image->store('news', 'public');

            if ($this->news && ($old = storage_media_path($this->news->image_path))) {
                @unlink($old);
            }
        }

        if ($this->news) {
            $this->news->update($base);
            Toaster::success('News updated.');
        } else {
            NewsItem::create($base);
            Toaster::success('News created.');
        }

        $this->redirect(route('cms.news.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.cms.news-form');
    }
}
