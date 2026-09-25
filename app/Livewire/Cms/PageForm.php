<?php

namespace App\Livewire\Cms;

use App\Models\Page;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class PageForm extends Component
{
    public string $slug;

    public string $pageTitle = '';

    public string $contentID = '';

    public string $contentEN = '';

    public function mount(string $slug): void
    {
        $this->slug = $slug;
        $this->pageTitle = ucfirst($slug);

        $page = Page::where('name', $slug)->first();

        $this->contentID = (string) ($page->contentID ?? '');
        $this->contentEN = (string) ($page->contentEN ?? '');
    }

    protected function rules(): array
    {
        return [
            'contentID' => ['required', 'string'],
            'contentEN' => ['required', 'string'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        Page::updateOrCreate(
            ['name' => $this->slug],
            $validated
        );

        Toaster::success('Page updated.');

        $this->redirect(route('cms.pages.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.cms.page-form');
    }
}
