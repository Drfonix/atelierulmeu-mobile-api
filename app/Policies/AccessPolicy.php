<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class AccessPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can access the model.
     *
     * @param User $user
     * @param $model
     * @return bool
     */
    public function access(User $user, $model = null)
    {
        try {
            if (!$model || !is_object($model)) {
                return true;
            }
            if (!$model->id || !$model->user_id) {
                return false;
            }
            return $user->id === $model->user_id;

        } catch (\Exception $ex) {
//            Log::channel('http-error-response')->error(sprintf('Access ERROR, file => %s, line => %s, message => %s',  $ex->getFile(), $ex->getLine(), $ex->getMessage()));
        }

        return false;

    }
}
