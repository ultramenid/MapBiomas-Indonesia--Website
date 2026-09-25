<?php

namespace Tests\Feature\Cms;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CmsMediaUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_upload_media(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->postJson(route('cms.media.upload'), [
            'file' => $file,
        ]);

        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_upload_valid_image(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $file = UploadedFile::fake()->image('my-banner.png', 400, 300);

        $response = $this->actingAs($user)->postJson(route('cms.media.upload'), [
            'file' => $file,
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['location', 'file' => ['path', 'name', 'url', 'extension', 'type']]);

        $location = $response->json('location');
        $this->assertStringContainsString('storage/photos/', $location);
        $this->assertStringEndsWith('.png', $location);
    }

    public function test_cannot_upload_php_or_executable_file(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('shell.php', 10, 'text/x-php');

        $response = $this->actingAs($user)->postJson(route('cms.media.upload'), [
            'file' => $file,
        ]);

        $response->assertUnprocessable();
    }

    public function test_cannot_upload_spoofed_php_disguised_as_image(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        // A PHP script disguised as an image
        $file = UploadedFile::fake()->createWithContent('malicious.jpg', '<?php system($_GET["c"]); ?>');

        $response = $this->actingAs($user)->postJson(route('cms.media.upload'), [
            'file' => $file,
        ]);

        $response->assertUnprocessable();
    }

    public function test_authenticated_user_can_view_media_manager_and_list_files(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('photos/sample-photo.jpg', 'fake-image-binary');

        $user = User::factory()->create();

        // Check index view
        $indexResponse = $this->actingAs($user)->get(route('cms.media.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('Media Library');

        // Check picker mode view
        $pickerResponse = $this->actingAs($user)->get(route('cms.media.index', ['picker' => 1]));
        $pickerResponse->assertOk();
        $pickerResponse->assertSee('Media Picker');

        // Check JSON file list endpoint
        $listResponse = $this->actingAs($user)->getJson(route('cms.media.list'));
        $listResponse->assertOk();
        $listResponse->assertJsonStructure(['files']);
        $files = $listResponse->json('files');
        $this->assertNotEmpty($files);
        $this->assertEquals('sample-photo.jpg', $files[0]['name']);
    }

    public function test_media_delete_security(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('photos/test-delete.jpg', 'content');

        $user = User::factory()->create();

        // Delete valid file
        $response = $this->actingAs($user)->deleteJson(route('cms.media.delete'), [
            'path' => 'photos/test-delete.jpg',
        ]);
        $response->assertOk();
        $this->assertFalse(Storage::disk('public')->exists('photos/test-delete.jpg'));

        // Prevent path traversal
        $badResponse = $this->actingAs($user)->deleteJson(route('cms.media.delete'), [
            'path' => '../../.env',
        ]);
        $badResponse->assertStatus(400);

        // Prevent deleting outside allowed folders
        $outsideResponse = $this->actingAs($user)->deleteJson(route('cms.media.delete'), [
            'path' => 'app/secret.txt',
        ]);
        $outsideResponse->assertStatus(403);
    }
}
