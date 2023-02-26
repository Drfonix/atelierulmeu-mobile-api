<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AuthRequest extends Model
{
    use HasFactory;

    public const TYPE_LOGIN = 'login';
    public const TYPE_REGISTRATION = 'registration';
    public const TYPE_CHANGE = 'change';

    public const TYPES = [
        self::TYPE_LOGIN,
        self::TYPE_REGISTRATION,
        self::TYPE_CHANGE
    ];

    protected $fillable = [
        'phone', 'confirmed', 'code', 'user_id', 'type'
    ];

    /**
     * Check whether the request is still valid or not
     *
     * @return boolean
     */
    public function isValid(): bool
    {
        $twoHours = Carbon::now()->subHours(2);
        return !(Carbon::parse($this->created_at)->lt($twoHours) || $this->confirmed);
    }

    /**
     * Generate a unique registration code
     *
     * @return string
     */
    public static function generateUniqueCode(): string
    {
        $unique = false;
        while (!$unique) {
            $code = Str::random(5);
            $exists = self::where('code', $code)->first();
            if (!$exists) {
                $unique = true;
            }
        }
        return $code;
    }
}
