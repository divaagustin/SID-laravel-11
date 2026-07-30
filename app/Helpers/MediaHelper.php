<?php

use Illuminate\Support\Facades\Storage;

if (! function_exists('get_media_url')) {
    /**
     * Resolves storage media file path to a clean, absolute public URL.
     * Automatically handles HTTP URLs, strips 'public/' prefixes, and fallbacks.
     *
     * @param string|null $path
     * @param string|null $fallbackType
     * @return string
     */
    function get_media_url(?string $path, ?string $fallbackType = 'default'): string
    {
        if (empty($path)) {
            return get_fallback_media($fallbackType);
        }

        // If path is already a full external URL (http/https)
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Clean path string: remove leading slash or 'public/' prefix if stored by Filament
        $cleanPath = ltrim($path, '/');
        if (str_starts_with($cleanPath, 'public/')) {
            $cleanPath = substr($cleanPath, 7);
        }
        if (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        }

        // Check if file exists via Laravel Storage disk
        if (Storage::disk('public')->exists($cleanPath)) {
            return asset('storage/' . $cleanPath);
        }

        // Fallback check on physical disk
        $fullSystemPath = storage_path('app/public/' . $cleanPath);
        if (file_exists($fullSystemPath) && ! is_dir($fullSystemPath)) {
            return asset('storage/' . $cleanPath);
        }

        // Return direct storage asset URL if path is non-empty string
        return asset('storage/' . $cleanPath);
    }
}

if (! function_exists('get_fallback_media')) {
    /**
     * Returns SVG data URI or high quality fallback placeholder image.
     */
    function get_fallback_media(?string $type = 'default'): string
    {
        switch ($type) {
            case 'logo':
                return 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="%23d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>';
            case 'pamong':
            case 'avatar':
                return 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="150" height="150" viewBox="0 0 24 24" fill="%23165b2d"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>';
            case 'berita':
            case 'galeri':
            default:
                return 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="400" height="250" viewBox="0 0 24 24" fill="none" stroke="%232d8a4e" stroke-width="1.5"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>';
        }
    }
}
