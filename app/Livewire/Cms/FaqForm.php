<?php

namespace App\Livewire\Cms;

use App\Models\Faq;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class FaqForm extends Component
{
    public ?Faq $faq = null;

    public string $questionID = '';

    public string $questionEN = '';

    public string $answerID = '';

    public string $answerEN = '';

    public function mount(?int $record = null): void
    {
        if ($record) {
            $this->faq = Faq::findOrFail($record);
            $this->questionID = (string) $this->faq->questionID;
            $this->questionEN = (string) $this->faq->questionEN;
            $this->answerID = (string) $this->faq->answerID;
            $this->answerEN = (string) $this->faq->answerEN;
        }
    }

    protected function rules(): array
    {
        return [
            'questionID' => ['required', 'string', 'max:500'],
            'questionEN' => ['required', 'string', 'max:500'],
            'answerID' => ['required', 'string'],
            'answerEN' => ['required', 'string'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->faq) {
            $this->faq->update($validated);
            Toaster::success('FAQ updated.');
        } else {
            Faq::create($validated);
            Toaster::success('FAQ created.');
        }

        $this->redirect(route('cms.faq.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.cms.faq-form');
    }
}
