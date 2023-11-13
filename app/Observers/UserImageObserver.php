<?php

namespace App\Observers;

use App\Models\UserImage;
use App\Services\ImageService;

/**
 * Class UserImageObserver
 * @package App\Observers
 */
class UserImageObserver
{

    /**
     * @var ImageService
     */
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Handle the  "deleted" event.
     *
     * @param UserImage $userImage
     * @return void
     */
    public function deleted(UserImage $userImage)
    {
        $this->imageService->deleteUserImage($userImage, true);

    }

    /**
     * @param UserImage $userImage
     */
    public function forceDeleted(UserImage $userImage)
    {
        $this->imageService->deleteUserImage($userImage, true);
    }
}
