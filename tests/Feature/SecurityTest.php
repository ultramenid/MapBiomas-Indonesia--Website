<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_lfm_fire_filemanager_routes_return_404(): void
    {
        $this->get('/cms/fire-filemanager')->assertNotFound();
        $this->get('/cms/fire-filemanager?editor=content&type=Images')->assertNotFound();
        $this->get('/cms/fire-filemanager/upload')->assertNotFound();
        $this->post('/cms/fire-filemanager/upload')->assertNotFound();
        $this->get('/cms/fire-filemanager/delete')->assertNotFound();
        $this->get('/cms/fire-filemanager/rename')->assertNotFound();
    }

    public function test_unauthenticated_requests_to_tinymce_upload_are_redirected_to_login(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->post('/cms/tinymce-upload', [
            'file' => $file,
        ]);

        $response->assertRedirect('/cms/login');
    }

    public function test_authenticated_tinymce_uploads_successfully_store_files_with_randomized_hashes_and_return_location(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $file = UploadedFile::fake()->image('original_image_name.png', 500, 500);

        $response = $this->actingAs($user)->postJson('/cms/tinymce-upload', [
            'file' => $file,
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['location']);

        $location = $response->json('location');
        $this->assertNotEmpty($location);

        // Verify cryptographic / randomized naming (original file name not used directly)
        $this->assertStringNotContainsString('original_image_name.png', $location);
        $this->assertStringEndsWith('.png', $location);
    }

    public function test_malicious_and_executable_uploads_are_rejected_with_status_422(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        // Test PHP shell upload
        $phpFile = UploadedFile::fake()->create('webshell.php', 20, 'application/x-php');
        $this->actingAs($user)
            ->postJson('/cms/tinymce-upload', ['file' => $phpFile])
            ->assertStatus(422);

        // Test executable script disguised with image extension
        $fakeJpg = UploadedFile::fake()->createWithContent('exploit.jpg', '<?php system($_GET["c"]); ?>');
        $this->actingAs($user)
            ->postJson('/cms/tinymce-upload', ['file' => $fakeJpg])
            ->assertStatus(422);

        // Test phtml / phar / sh / exe
        foreach (['shell.phtml', 'malware.phar', 'script.sh', 'binary.exe'] as $badName) {
            $badFile = UploadedFile::fake()->create($badName, 10);
            $this->actingAs($user)
                ->postJson('/cms/tinymce-upload', ['file' => $badFile])
                ->assertStatus(422);
        }
    }
}
