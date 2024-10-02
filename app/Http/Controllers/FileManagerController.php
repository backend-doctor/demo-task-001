<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileManager\DirectoryRequest;
use App\Http\Requests\FileManager\RenameDirectoryRequest;
use App\Services\FileManager\Exceptions\DirectoryExistsException;
use App\Services\FileManager\Exceptions\DirectoryNotFoundException;
use App\Services\FileManager\Interfaces\FileManager;
use Symfony\Component\HttpFoundation\JsonResponse;

class FileManagerController extends Controller
{
    public function __construct(private readonly FileManager $service) {}
    public function createDirectory(DirectoryRequest $request): JsonResponse
    {
        try {
            return response()->json([
                'success' => $this->service->createDirectory($request->name)
            ]);
        } catch (DirectoryExistsException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], \Symfony\Component\HttpFoundation\Response::HTTP_CONFLICT);
        }
    }
    public function deleteDirectory(DirectoryRequest $request): JsonResponse
    {
        try {
            return response()->json([
                'success' => $this->service->deleteDirectory($request->name)
            ]);
        } catch (DirectoryNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND);
        }
    }
    public function renameDirectory(RenameDirectoryRequest $request)
    {
        try {
            return response()->json([
                'success' => $this->service->renameDirectory($request->from, $request->to)
            ]);
        } catch (DirectoryNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND);
        }
    }
}
