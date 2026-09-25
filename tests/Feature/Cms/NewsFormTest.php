<?php

namespace Tests\Feature\Cms;

use App\Livewire\Cms\NewsForm;
use App\Models\NewsItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class NewsFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_internal_type_shows_the_rich_text_editor_and_slug(): void
    {
        Livewire::test(NewsForm::class)
            ->assertSee('richtext-contentEN', false)
            ->assertSee('richtext-contentID', false)
            ->assertSee('name="slug"', false)
            ->assertDontSee('name="externalUrl"', false);
    }

    public function test_external_type_hides_the_rich_text_editor_and_shows_only_the_url(): void
    {
        Livewire::test(NewsForm::class)
            ->set('type', 'external')
            ->assertDontSee('richtext-contentEN', false)
            ->assertDontSee('richtext-contentID', false)
            ->assertSee('name="externalUrl"', false)
            ->assertSee('no article body', false);
    }

    public function test_switching_back_to_internal_restores_the_editor_and_keeps_typed_content(): void
    {
        Livewire::test(NewsForm::class)
            ->set('contentEN', 'Internal body in English')
            ->set('type', 'external')
            ->assertDontSee('richtext-contentEN', false)
            ->set('type', 'internal')
            ->assertSee('richtext-contentEN', false)
            ->assertSee('Internal body in English', false);
    }

    public function test_external_news_requires_a_valid_url_and_saves_without_content(): void
    {
        Livewire::test(NewsForm::class)
            ->set('titleEN', 'Coverage on partner site')
            ->set('titleID', 'Liputan di situs mitra')
            ->set('type', 'external')
            ->set('externalUrl', 'not-a-url')
            ->call('save')
            ->assertHasErrors(['externalUrl']);

        $this->assertDatabaseCount('news', 0);

        Livewire::test(NewsForm::class)
            ->set('titleEN', 'Coverage on partner site')
            ->set('titleID', 'Liputan di situs mitra')
            ->set('type', 'external')
            ->set('externalUrl', 'https://example.com/coverage')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('news', [
            'type' => 'external',
            'title_en' => 'Coverage on partner site',
            'external_url' => 'https://example.com/coverage',
        ]);

        $news = NewsItem::where('title_en', 'Coverage on partner site')->first();
        $this->assertNull($news->content_id);
        $this->assertNull($news->content_en);
        $this->assertNull($news->excerpt_id);
    }

    public function test_internal_news_requires_content_before_saving(): void
    {
        Livewire::test(NewsForm::class)
            ->set('titleEN', 'Internal article')
            ->set('titleID', 'Artikel internal')
            ->call('save')
            ->assertHasErrors(['contentID', 'contentEN']);

        $this->assertDatabaseCount('news', 0);
    }

    public function test_editing_an_external_news_item_hides_the_rich_text_editor(): void
    {
        $news = NewsItem::create([
            'type' => 'external',
            'title_id' => 'Berita eksternal',
            'title_en' => 'External headline',
            'slug' => 'berita-eksternal',
            'external_url' => 'https://example.com/coverage',
            'published_at' => now(),
        ]);

        Livewire::test(NewsForm::class, ['record' => $news->id])
            ->assertDontSee('richtext-contentEN', false)
            ->assertDontSee('richtext-contentID', false)
            ->assertSee('External URL', false)
            ->assertSet('externalUrl', 'https://example.com/coverage');
    }
}