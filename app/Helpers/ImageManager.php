<?php

use App\Models\UserImage;
use App\Services\ImageService;
use Illuminate\Support\Facades\Storage;

if (!function_exists('get_readable_file_size')) {
    /**
     * @param $size
     * @param int $precision
     * @return bool
     */
    function get_readable_file_size(int $size, $precision = 2)
    {

        if ( $size > 0 ) {
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
            return round(1024 ** ($base - floor($base)), $precision) . $suffixes[floor($base)];
        }

        return $size;
    }
}

if (!function_exists('get_images_storage_path')) {
    /**
     * @return string
     */
    function get_images_storage_path()
    {
        return Storage::disk(ImageService::DISK)->getDriver()->getAdapter()->getPathPrefix();
    }
}

if (!function_exists('get_user_folder')) {
    /**
     * @param $userId
     * @return integer
     */
    function get_user_folder($userId)
    {
        return 'user-' . (int)$userId;
    }
}

if (!function_exists('get_image_paths')) {
    /**
     * @param UserImage $userImage
     * @return array
     */
    function get_image_paths(UserImage $userImage)
    {
        $imagePath = get_user_folder($userImage->user_id) . DIRECTORY_SEPARATOR . $userImage->name;
        $storagePath  = get_images_storage_path();
        $filePath = $storagePath . $imagePath;

        return [
            "image" => $imagePath,
            "storage" => $storagePath,
            "file" => $filePath
        ];
    }
}

if (!function_exists('get_file_response_headers')) {
    /**
     * @param string $imagePath
     * @return array
     */
    function get_file_response_headers(string $imagePath)
    {
        $mimeTye = Storage::disk(ImageService::DISK)->mimeType($imagePath);

        return [
            'Content-Description' => 'File Transfer',
            'Content-Type' => $mimeTye
        ];
    }
}
