<?php

namespace DaydreamLab\User\Helpers;

use Carbon\Carbon;
use DaydreamLab\JJAJ\Exceptions\ForbiddenException;
use DaydreamLab\User\Notifications\GetOtpNotification;
use Illuminate\Support\Facades\Hash;

class OtpHelper
{
    public static function createOtp($user, $digits = 6, $expiredSecond = 900)
    {
        $now = now('UTC');
        $maxCodeNum = intval(str_pad('', $digits, '9', STR_PAD_LEFT));
        $twoFactor = $user->twofactor ?? [];
        $twoFactor['otp']['digits'] = $digits;
        $twoFactor['otp']['created_at'] = $now->format('Y-m-d H:i:s');
        $twoFactor['otp']['expiredSecond'] = $expiredSecond;
        $twoFactor['otp']['expiredDate'] = $now->addSeconds($expiredSecond)->format('Y-m-d H:i:s');
        if (config('app.env') != 'production') {
            $code = "000000";
        } else {
            $code = str_pad(strval(rand(0, $maxCodeNum)), $digits, '0', STR_PAD_LEFT);
        }
        $twoFactor['otp']['code'] = bcrypt($code); // 加密
        $user->twofactor = $twoFactor;
        $user->save();

        $user->notify(new GetOtpNotification($user, $code));
    }

    public static function isExpired($verifyMethod, $user)
    {
        $twoFactor = $user->twofactor ?? [];

        if ($verifyMethod == 'OTP') {
            $target = @$twoFactor['otp'] ?? [];
        }

        if (empty($target)) {
            return true;
        } elseif (Carbon::now('UTC') > Carbon::parse($target['expiredDate'], 'UTC')) {
            return true;
        }

        return false;
    }

    public static function verify($verifyMethod, $code, $user)
    {
        if ($verifyMethod == 'OTP') {
            if (! self::isExpired($verifyMethod, $user) && Hash::check($code, $user->twofactor['otp']['code'])) {
                $twofactor = $user->twofactor;
                $twofactor['otp'] = null;
                $user->twofactor = $twofactor;
                $user->save();

                return true;
            }
        }

        return false;
    }
}