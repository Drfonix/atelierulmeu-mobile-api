<?php

namespace App\Observers;

use App\Models\UserDocument;
use App\Services\ImageService;

/**
 * Class UserDocumentObserver
 * @package App\Observers
 */
class UserDocumentObserver
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
     * @param UserDocument $userDocument
     * @return void
     */
    public function deleted(UserDocument $userDocument)
    {
        $this->imageService->deleteUserDocument($userDocument, true);

    }

    /**
     * @param UserDocument $userDocument
     */
    public function forceDeleted(UserDocument $userDocument)
    {
        $this->imageService->deleteUserDocument($userDocument, true);
    }
}
