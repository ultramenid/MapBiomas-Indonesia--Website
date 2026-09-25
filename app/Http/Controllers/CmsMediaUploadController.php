<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CmsMediaUploadController extends Controller
{
    private const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    private const DOCUMENT_EXTENSIONS = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'txt'];
    private const FORBIDDEN_EXTENSIONS = [
        'php', 'php3', 'php4', 'php5', 'php7', 'php8', 'phtml', 'phar', 'phps',
        'sh', 'bash', 'py', 'pl', 'cgi', 'exe', 'bat', 'cmd', 'ps1',
        'asp', 'aspx', 'jsp', 'jspx', 'shtml', 'htaccess', 'env',
    ];

    private const IMAGE_MIMES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    private const DOCUMENT_MIMES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/zip',
        'text/plain',
    ];

    /**
     * Display media manager page or iframe picker.
     */
    public function index(Request $request): View
    {
        $isPicker = (bool) $request->boolean('picker');
        $type = $request->query('type', 'all');

        return view('cms.media.index', [
            'isPicker' => $isPicker,
            'defaultType' => $type,
        ]);
    }

    /**
     * Get JSON list of stored media files.
     */
    public function listFiles(Request $request): JsonResponse
    {
        $disk = Storage::disk('public');
        $directories = ['photos', 'files', 'cms-media'];
        $filesList = [];

        foreach ($directories as $dir) {
            if (! $disk->exists($dir)) {
                continue;
            }

            foreach ($disk->allFiles($dir) as $path) {
                $filename = basename($path);

                // Ignore hidden and system files
                if (str_starts_with($filename, '.') || str_contains($path, 'thumbs/')) {
                    continue;
                }

                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                // Safety: never show or expose executable files
                if (in_array($extension, self::FORBIDDEN_EXTENSIONS, true)) {
                    continue;
                }

                $isImage = in_array($extension, self::IMAGE_EXTENSIONS, true);
                $isDoc = in_array($extension, self::DOCUMENT_EXTENSIONS, true);

                $sizeBytes = $disk->size($path);
                $lastModified = $disk->lastModified($path);

                $filesList[] = [
                    'path' => $path,
                    'name' => $filename,
                    'url' => asset('storage/' . $path),
                    'size' => $this->formatBytes($sizeBytes),
                    'size_bytes' => $sizeBytes,
                    'modified' => $lastModified,
                    'modified_formatted' => date('Y-m-d H:i', $lastModified),
                    'extension' => $extension,
                    'type' => $isImage ? 'image' : ($isDoc ? 'document' : 'other'),
                ];
            }
        }

        // Sort descending by last modified
        usort($filesList, fn ($a, $b) => $b['modified'] <=> $a['modified']);

        return response()->json([
            'files' => $filesList,
        ]);
    }

    /**
     * Handle media upload from TinyMCE or Media Manager UI.
     */
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'max:25600'], // 25 MB max
            'type' => ['nullable', 'string', 'in:image,file,all'],
        ]);

        $file = $request->file('file');
        if (! $file || ! $file->isValid()) {
            throw ValidationException::withMessages([
                'file' => 'The uploaded file is not valid.',
            ]);
        }

        $clientOriginalName = $file->getClientOriginalName();
        $this->assertFilenameSafe($clientOriginalName);

        $clientExt = strtolower($file->getClientOriginalExtension());
        $guessedExt = strtolower($file->guessExtension() ?: $clientExt);
        $mime = $file->getMimeType();

        // Check if image
        $isImage = in_array($clientExt, self::IMAGE_EXTENSIONS, true) || in_array($guessedExt, self::IMAGE_EXTENSIONS, true);

        if ($isImage) {
            if (! in_array($mime, self::IMAGE_MIMES, true)) {
                throw ValidationException::withMessages([
                    'file' => 'The file MIME type is not allowed for images.',
                ]);
            }

            // Verify real image
            $imageInfo = @getimagesize($file->getRealPath());
            if ($imageInfo === false) {
                throw ValidationException::withMessages([
                    'file' => 'The file is not a valid image.',
                ]);
            }

            $targetDir = 'photos';
            $finalExt = in_array($guessedExt, self::IMAGE_EXTENSIONS, true) ? $guessedExt : $clientExt;
        } else {
            // Document / file
            if (! in_array($clientExt, self::DOCUMENT_EXTENSIONS, true)) {
                throw ValidationException::withMessages([
                    'file' => 'The file extension is not allowed.',
                ]);
            }

            if (! in_array($mime, self::DOCUMENT_MIMES, true)) {
                throw ValidationException::withMessages([
                    'file' => 'The document MIME type is not recognized.',
                ]);
            }

            $targetDir = 'files';
            $finalExt = $clientExt;
        }

        // Build safe sanitized name: sanitized-base-xxxxxx.ext
        $rawBaseName = pathinfo($clientOriginalName, PATHINFO_FILENAME);
        $safeSlug = Str::slug($rawBaseName);
        if (empty($safeSlug)) {
            $safeSlug = 'file';
        }
        $safeFileName = substr($safeSlug, 0, 40) . '-' . Str::random(8) . '.' . $finalExt;

        $path = $file->storeAs($targetDir, $safeFileName, 'public');

        $url = asset('storage/' . $path);

        return response()->json([
            'location' => $url,
            'file' => [
                'path' => $path,
                'name' => $safeFileName,
                'url' => $url,
                'extension' => $finalExt,
                'type' => $isImage ? 'image' : 'document',
            ],
        ]);
    }

    /**
     * Delete a stored media file.
     */
    public function delete(Request $request): JsonResponse
    {
        $request->validate([
            'path' => ['required', 'string'],
        ]);

        $path = $request->input('path');

        // Prevent path traversal
        if (str_contains($path, '..') || str_starts_with($path, '/') || str_starts_with($path, '\\')) {
            return response()->json(['message' => 'Invalid path.'], 400);
        }

        // Must be in one of the allowed storage directories
        $allowedPrefixes = ['photos/', 'files/', 'cms-media/'];
        $isAllowed = false;
        foreach ($allowedPrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $isAllowed = true;
                break;
            }
        }

        if (! $isAllowed) {
            return response()->json(['message' => 'Target path cannot be deleted.'], 403);
        }

        $disk = Storage::disk('public');
        if (! $disk->exists($path)) {
            return response()->json(['message' => 'File not found.'], 404);
        }

        // Do not allow deleting system/config files
        $filename = basename($path);
        if (str_starts_with($filename, '.')) {
            return response()->json(['message' => 'Cannot delete system files.'], 403);
        }

        $disk->delete($path);

        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully.',
        ]);
    }

    /**
     * Disallow executable or dangerous tokens in filenames.
     */
    private function assertFilenameSafe(string $filename): void
    {
        $lower = strtolower($filename);

        // Check for null bytes
        if (str_contains($filename, "\0")) {
            throw ValidationException::withMessages([
                'file' => 'Suspicious filename detected.',
            ]);
        }

        $parts = explode('.', $lower);
        array_shift($parts); // remove the base name

        foreach ($parts as $part) {
            if (in_array($part, self::FORBIDDEN_EXTENSIONS, true)) {
                throw ValidationException::withMessages([
                    'file' => 'Executable files or forbidden extensions are not allowed.',
                ]);
            }
        }
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 0) . ' KB';
        }

        return $bytes . ' B';
    }
}
