<?php

namespace Tests\Feature;

use App\Interfaces\FileRepositoryInterface;
use App\Models\File as FileModel;
use App\Services\FileManager\FileManagerService;
use App\Services\FileManager\Interfaces\FileManager;
use Illuminate\Filesystem\LocalFilesystemAdapter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileManagerControllerTest extends TestCase
{
    use RefreshDatabase;
    private LocalFilesystemAdapter $storage;
    protected function setUp(): void
    {
        parent::setUp();
        $this->storage = Storage::fake('testing');

        $this->app->bind(FileManager::class, function (\Illuminate\Foundation\Application $app) {
            return new FileManagerService($this->storage, $app->get(FileRepositoryInterface::class));
        });
    }

    public function test_upload_files_with_empty_request()
    {
        $response = $this->postJson(route('upload-files'));

        $response->assertStatus(422);
    }
    public function test_upload_files_with_limited_size()
    {
        $files = [
            UploadedFile::fake()->create($file1 = 'file-1.txt', 5001),
            UploadedFile::fake()->image($file2 = 'photo-2.jpg')
        ];
        $response = $this->postJson(route('upload-files', [
            'files' => $files
        ]));

        $response->assertStatus(422);
        $this->assertFalse($this->storage->fileExists($file1));
        $this->assertFalse($this->storage->fileExists($file2));
    }

    public function test_upload_files_without_path()
    {
        $files = [
            UploadedFile::fake()->create($file1 = 'file-1'),
            UploadedFile::fake()->image($file2 = 'file-2')
        ];

        $response = $this->postJson(route('upload-files'), [
            'files' => $files,
        ]);
        $response->assertStatus(422);
    }
    public function test_upload_files()
    {
        $this->withoutExceptionHandling();
        $files = [
            UploadedFile::fake()->create($file1 = 'file-1'),
            UploadedFile::fake()->image($file2 = 'file-2')
        ];

        $response = $this->postJson(route('upload-files'), [
            'files' => $files,
            'path' => $path = 'dir'
        ]);
        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertTrue(FileModel::where('name', $file1)->exists());
        $this->assertTrue(FileModel::where('name', $file2)->exists());
        $this->assertTrue($this->storage->fileExists(FileModel::where('name', $file1)->first()->path));
        $this->assertTrue($this->storage->fileExists(FileModel::where('name', $file2)->first()->path));
    }
    public function test_upload_file()
    {
        $this->withoutExceptionHandling();
        $file = UploadedFile::fake()->create('file-1');

        $response = $this->postJson(route('upload-files'), [
            'file' => $file,
            'path' => 'dir'
        ]);
        $response->assertOk();

        $this->assertEquals(FileModel::first()->name, json_decode($response->getContent(), 1)['name']);
        $this->assertTrue(FileModel::where('name', $file->name)->exists());
        $this->assertTrue($this->storage->fileExists(FileModel::where('name', $file->name)->first()->path));
    }


    // public function test_rename_not_exists_file()
    // {
    //     $response = $this->postJson(route('rename-file', [
    //         'files' => 'file.txt'
    //     ]));

    //     $response->assertStatus(404);
    // }
    // public function test_rename_file()
    // {
    //     $file = UploadedFile::fake()->create($file = 'file-1.txt', 300);
    //     $this->storage->putFile($file);
    //     $response = $this->postJson(route('rename-file', [
    //         'files' => 'file.txt'
    //     ]));

    //     $response->assertOk();
    //     $this->assertTrue($this->storage->fileMissing($file));
    //     $this->assertTrue($this->storage->fileExists($file));
    // }

    // public function test_show_file()
    // {
    //     $file = UploadedFile::fake()->create($file = 'file-1.txt', 300);
    //     $this->storage->putFile($file);
    //     $response = $this->postJson(route('rename-file', [
    //         'files' => 'file.txt'
    //     ]));

    //     $response->assertOk();
    //     $this->assertTrue($this->storage->fileMissing($file));
    //     $this->assertTrue($this->storage->fileExists($file));
    // }
}
