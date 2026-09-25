<?php

namespace App\Livewire\Cms;

use App\Models\Faq;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class FaqIndex extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    public ?int $deleteId = null;

    public string $deleteName = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $faq = Faq::find($id);

        if (! $faq) {
            return;
        }

        $this->deleteId = $id;
        $this->deleteName = Str::limit(strip_tags($faq->questionEN), 60);
    }

    public function destroy(): void
    {
        if ($this->deleteId) {
            Faq::find($this->deleteId)?->delete();
            Toaster::success('FAQ deleted.');
        }

        $this->closeDelete();
    }

    public function closeDelete(): void
    {
        $this->reset('deleteId', 'deleteName');
    }

    public function render()
    {
        $faqs = Faq::query()
            ->when($this->search !== '', fn ($query) => $query->where(fn ($w) => $w
                ->where('questionID', 'like', "%{$this->search}%")
                ->orWhere('questionEN', 'like', "%{$this->search}%")
                ->orWhere('answerID', 'like', "%{$this->search}%")
                ->orWhere('answerEN', 'like', "%{$this->search}%")))
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.cms.faq-index', ['faqs' => $faqs]);
    }
}
