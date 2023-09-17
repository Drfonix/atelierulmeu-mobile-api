<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserDocument;
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
    public function checkFilePaths(string $filePath, string $imagePath)
    {
        if(!Storage::disk(self::DISK)->exists($imagePath)) {
            throw new FileNotFoundException("User file not found");
        }
    }

    /**
     * Save the image and create userImage model data
     *
     * @param $userId
     * @param $file
     * @return array
     */
    protected function createFile($userId, $file)
    {
        $ext = $file->getClientOriginalExtension();
        $imageName = Str::random(6) . '_' . time() . '.' . $ext;

        Storage::disk(self::DISK)->putFileAs(get_user_folder($userId), $file, $imageName);

        return [
            'user_id' => $userId,
            'name' => $imageName,
            'visible_name' => $file->getClientOriginalName(),
            'type' => $ext,
            'size' => $file->getSize(),
            'h_size' => get_readable_file_size($file->getSize())
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
        $modelData = $this->createFile($user->id, $image);

        if($carId) {
            $user->checkCarId($carId);
            $modelData["car_id"] = $carId;
        }

        return UserImage::create(array_merge($modelData, $imageData));
    }

    /**
     * Uploads a new user image
     *
     * @param User $user
     * @param $documentData
     * @param $document
     * @return mixed
     * @throws FileNotFoundException
     */
    public function uploadDocument(User $user, $documentData, $document)
    {
        $carId = array_key_exists("car_id", $documentData) ? $documentData['car_id'] : null;

        $this->checkAndCreateUserFolder($user->id);
        $modelData = $this->createFile($user->id, $document);

        if($carId) {
            $user->checkCarId($carId);
            $modelData["car_id"] = $carId;
        }

        return UserDocument::create(array_merge($modelData, $documentData));
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
     * Delete the user image object and file
     *
     * @param UserDocument $userDocument
     */
    public function deleteUserDocument(UserDocument $userDocument)
    {
        $imagePath = get_image_paths($userDocument);

        if(File::exists($imagePath["file"])) {
            Storage::disk(self::DISK)->delete($imagePath["image"]);
        }
        $userDocument->delete();
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
