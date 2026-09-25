<?php

namespace Tests\Feature\Cms;

use App\Livewire\CmsLogin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class CmsLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_cms_login_component_logs_in_and_redirects_to_the_dashboard(): void
    {
        $user = \App\Models\User::create([
            'name' => 'Administrator',
            'email' => 'admin@mapbiomas.id',
            'password' => 'password',
            'role_id' => \App\Models\User::ROLE_ADMIN,
        ]);

        Livewire::test(\App\Livewire\CmsLogin::class)
            ->set('email', 'admin@mapbiomas.id')
            ->set('password', 'password')
            ->call('login')
            ->assertRedirect(route('cms.dashboard'));

        $this->actingAs($user)
            ->get(route('cms.news.create'))
            ->assertOk()
            ->assertSee('richtext-contentEN', false);
    }
}