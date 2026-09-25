<?php

if (! function_exists('media_url')) {
    /**
     * Resolve a stored media path to a public URL. Seeded/legacy rows may
     * point at public/assets files, uploads live under storage/, and some
     * rows store full external image URLs.
     */
    function media_url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '//')
            ? $path
            : asset($path);
    }
}

if (! function_exists('storage_media_path')) {
    /** Absolute filesystem path of an uploaded (storage/) media file, for deletion. */
    function storage_media_path(?string $path): ?string
    {
        if (! $path || ! str_starts_with($path, 'storage/')) {
            return null;
        }

        return public_path($path);
    }
}
