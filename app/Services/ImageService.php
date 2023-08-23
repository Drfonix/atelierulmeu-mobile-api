<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserImage;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as ImageFacade;

class ImageService
{
    public const DISK = "user-image";

    /**
     * Checks user folder and create it
     *
     * @param $userId
     * @throws FileNotFoundException
     */
    public function checkAndCreateUserFolder($userId)
    {
        if(!$userId) {
            throw new FileNotFoundException("User folder not found");
        }
        $userFolder = get_user_folder($userId);
        if (!Storage::disk(self::DISK)->exists($userFolder)) {
            Storage::disk(self::DISK)->makeDirectory($userFolder);
        }
    }

    /**
     * Checks image and file paths
     *
     * @param string $filePath
     * @param string $imagePath
     * @throws FileNotFoundException
     */
    public function checkImagePaths(string $filePath, string $imagePath)
    {
        if(!File::exists($filePath) || !Storage::disk(self::DISK)->exists($imagePath)) {
            throw new FileNotFoundException("User file not found");
        }
    }

    /**
     * Save the image and create userImage model data
     *
     * @param $userId
     * @param $image
     * @return array
     */
    protected function createImage($userId, $image)
    {
        $ext = $image->getClientOriginalExtension();
        $imageName = Str::random(6) . '_' . time() . '.' . $ext;

        Storage::disk(self::DISK)->putFileAs(get_user_folder($userId), $image, $imageName);

        return [
            'user_id' => $userId,
            'name' => $imageName,
            'visible_name' => $image->getClientOriginalName(),
            'type' => $ext,
            'size' => $image->getSize(),
            'h_size' => get_readable_file_size($image->getSize())
        ];
    }

    /**
     * Uploads a new user image
     *
     * @param User $user
     * @param $imageData
     * @param $image
     * @return mixed
     * @throws FileNotFoundException
     */
    public function uploadImage(User $user, $imageData, $image)
    {
        $carId = array_key_exists("car_id", $imageData) ? $imageData['car_id'] : null;

        $this->checkAndCreateUserFolder($user->id);
        $modelData = $this->createImage($user->id, $image);

        if($carId) {
            $user->checkCarId($carId);
            $modelData["car_id"] = $carId;
        }

        return UserImage::create(array_merge($modelData, $imageData));
    }

    /**
     * Delete the user image object and file
     *
     * @param UserImage $userImage
     */
    public function deleteUserImage(UserImage $userImage)
    {
        $imagePath = get_image_paths($userImage);

        if(File::exists($imagePath["file"])) {
            Storage::disk(self::DISK)->delete($imagePath["image"]);
        }
        $userImage->delete();
    }

    /**
     * Return the user files count and size
     *
     * @param User $user
     * @return array
     */
    public function getUserFilesAndSizes(User $user)
    {
        $userFolder = get_user_folder($user->id);
        $userFiles = Storage::disk(self::DISK)->allFiles($userFolder);
        $totalCount = count($userFiles);
        $totalSize = 0;

        foreach ($userFiles as $userFile) {
            $totalSize += Storage::disk(self::DISK)->size($userFile);
        }

        return [
            "files_count" => $totalCount,
            "storage_size" => $totalSize,
            "storage_h_size" => get_readable_file_size($totalSize)
        ];
    }

    public function resizeImage($originalImage)
    {
        $image = ImageFacade::make($originalImage);

        // Resize the image if needed
        if ($image->width() > 800 || $image->height() > 800) {
            $image->fit(800, 800, function ($constraint) {
                $constraint->upsize();
            });
            $image->save();
        }
        return $image;
    }


}
