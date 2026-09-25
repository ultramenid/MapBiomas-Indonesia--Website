<?php

namespace App\Livewire\Cms;

use App\Models\NewsItem;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class NewsIndex extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    public string $type = '';

    public ?int $deleteId = null;

    public string $deleteName = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedType(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $news = NewsItem::find($id);

        if (! $news) {
            return;
        }

        $this->deleteId = $id;
        $this->deleteName = Str::limit($news->title_en, 60);
    }

    public function destroy(): void
    {
        if ($this->deleteId) {
            $news = NewsItem::find($this->deleteId);

            if ($uploaded = storage_media_path($news?->image_path)) {
                @unlink($uploaded);
            }

            $news?->delete();
            Toaster::success('News deleted.');
        }

        $this->closeDelete();
    }

    public function closeDelete(): void
    {
        $this->reset('deleteId', 'deleteName');
    }

    public function render()
    {
        $news = NewsItem::query()
            ->when($this->type !== '', fn ($query) => $query->where('type', $this->type))
            ->when($this->search !== '', fn ($query) => $query->where(fn ($w) => $w
                ->where('title_id', 'like', "%{$this->search}%")
                ->orWhere('title_en', 'like', "%{$this->search}%")))
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.cms.news-index', ['news' => $news]);
    }
}
