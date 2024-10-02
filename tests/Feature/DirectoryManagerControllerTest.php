<?php

namespace Tests\Feature;

use App\Services\FileManager\DirectoryManagerService;
use App\Services\FileManager\FileManagerService;
use App\Services\FileManager\Interfaces\DirectoryManager;
use App\Services\FileManager\Interfaces\FileManager;
use Illuminate\Filesystem\LocalFilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DirectoryManagerControllerTest extends TestCase
{
    private LocalFilesystemAdapter $storage;
    protected function setUp(): void
    {
        parent::setUp();
        $this->withExceptionHandling();
        $this->storage = Storage::fake('testing');

        $this->app->bind(DirectoryManager::class, function () {
            return new DirectoryManagerService($this->storage);
        });
    }

    public function test_create_dir_with_empty_request(): void
    {
        $response = $this->postJson(route('create.dir'));

        $response->assertStatus(422);
    }
    public function test_create_dir(): void
    {
        $this->withExceptionHandling();
        $this->storage->deleteDirectory($directory = 'testDir');
        $response = $this->postJson(route('create.dir'), [
            'name' => $directory
        ]);

        $response->assertOk();
        $this->assertEquals(json_decode($response->getContent(), 1), ['success' => true]);
        $this->assertTrue($this->storage->directoryExists($directory));
    }
    public function test_create_exists_dir(): void
    {
        $this->storage->makeDirectory($directory = 'testDir');
        $response = $this->postJson(route('create.dir'), [
            'name' => $directory
        ]);

        $response->assertStatus(409);
        $response->assertJson([
            'success' => false,
            'message' => sprintf('directory "%s" exists', $directory)
        ]);
    }

    public function test_delete_dir_with_empty_request(): void
    {
        $response = $this->postJson(route('delete.dir'));

        $response->assertStatus(422);
    }
    public function test_delete_dir(): void
    {
        $this->storage->makeDirectory($directory = 'testDir');
        $response = $this->deleteJson(route('delete.dir'), [
            'name' => $directory
        ]);

        $response->assertOk();
        $this->assertEquals(json_decode($response->getContent(), 1), ['success' => true]);
        $this->assertTrue($this->storage->directoryMissing($directory));
    }
    public function test_delete_not_exists_dir(): void
    {
        $response = $this->deleteJson(route('delete.dir'), [
            'name' => $directory = 'testDir'
        ]);

        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'message' => sprintf('directory "%s" not found', $directory)
        ]);
    }
    public function test_rename_dir_with_empty_request()
    {
        $response = $this->patchJson(route('rename.dir'));

        $response->assertStatus(422);
    }
    public function test_rename_not_exists_dir()
    {
        $response = $this->patchJson(route('rename.dir'), [
            'from' => 'dir',
            'to' => 'newDir'
        ]);

        $response->assertStatus(404);
    }
    public function test_rename_dir()
    {
        $this->storage->makeDirectory($directory = 'testDir');
        $response = $this->patchJson(route('rename.dir'), [
            'from' => $directory,
            'to' => $name = 'newDir',
        ]);

        $response->assertOk();
        $this->assertTrue($this->storage->directoryMissing($directory));
        $this->assertTrue($this->storage->directoryExists($name));
    }
}
