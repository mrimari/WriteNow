<?php

namespace App\Helpers;

class AssetHelper
{
    /**
     * Получить версионированный URL для статического файла
     *
     * @param string $path
     * @return string
     */
    public static function versioned($path)
    {
        $fullPath = public_path($path);
        
        if (file_exists($fullPath)) {
            return asset($path) . '?v=' . filemtime($fullPath);
        }
        
        return asset($path);
    }
} 