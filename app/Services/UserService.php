<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

/**
 * Class UserService
 * @package App\Services
 */
class UserService
{

    public function deleteUserWithAllData(User $user)
    {
        $user->appointments()->delete();
        $user->alerts()->delete();
        $user->images()->delete();
        $user->documents()->delete();
        $user->cars()->delete();

        $userFolder = get_user_folder($user->id);
        if (Storage::disk(ImageService::DISK)->exists($userFolder)) {
            Storage::disk(ImageService::DISK)->deleteDirectory($userFolder);
        }

        $user->delete();
    }
}
