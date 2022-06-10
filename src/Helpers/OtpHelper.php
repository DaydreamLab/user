<?php

namespace DaydreamLab\User\Helpers;

use Carbon\Carbon;
use DaydreamLab\JJAJ\Exceptions\ForbiddenException;
use DaydreamLab\User\Notifications\User\UserGetOtpCodeNotification;
use DaydreamLab\User\Notifications\User\UserGetTotpQrCodeNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use ParagonIE\ConstantTime\Base32;
use function PHPUnit\Framework\isEmpty;

class OtpHelper
{
    public static function createOtp($user, $digits = 6, $expiredSecond = 900)
    {
        $now = Carbon::now('UTC');
        $maxCodeNum = intval(str_pad('', $digits, '9', STR_PAD_LEFT));

        $twoFactor = $user->twofactor ?? [];
        $twoFactor['otp']['digits'] = $digits;
        $twoFactor['otp']['created_at'] = $now->format('Y-m-d H:i:s');
        $twoFactor['otp']['expiredSecond'] = $expiredSecond;
        $twoFactor['otp']['expiredDate'] = $now->addSeconds($expiredSecond)->format('Y-m-d H:i:s');
        if (config('app.env') != 'production') {
            $twoFactor['otp']['code'] = "000000";
        } else {
            $twoFactor['otp']['code'] = str_pad(strval(rand(0, $maxCodeNum)), $digits, '0', STR_PAD_LEFT);
        }
        $user->twofactor = $twoFactor;
        $user->save();

        Notification::route('mail', $user->email)->notify(new UserGetOtpCodeNotification($user));
        if (preg_match("/^09[0-9]{8}$/", $user->mobilePhone)) {
            Notification::route(config('daydreamlab.user.sms.channel'), $user->fullMobilePhone)->notify(new UserGetOtpCodeNotification($user));
        }
    }

    public static function createTotp($user, $digits = 6, $period = 30, $expiredSecond = 15552000, $algorithm = 'SHA1')
    {
        $now = Carbon::now('UTC');

        $twoFactor = $user->twofactor ?? [];
        $twoFactor['totp']['secret'] = rtrim(Base32::encodeUpper(Str::random(10)), '=');
        $twoFactor['totp']['algorithm'] = $algorithm;
        $twoFactor['totp']['digits'] = $digits;
        $twoFactor['totp']['period'] = $period;
        $twoFactor['totp']['created_at'] = $now->format('Y-m-d H:i:s');
        $twoFactor['totp']['expiredSecond'] = $expiredSecond;
        $twoFactor['totp']['expiredDate'] = $now->addSeconds($expiredSecond)->format('Y-m-d H:i:s');
        $twoFactor['totp']['issure'] = "zerone.com.tw";
        $twoFactor['totp']['label'] = $user->email;
        $twoFactor['totp']['url'] = "otpauth://totp/{$twoFactor['totp']['label']}?secret={$twoFactor['totp']['secret']}&issuer={$twoFactor['totp']['issure']}&algorithm={$twoFactor['totp']['algorithm']}&digits={$twoFactor['totp']['digits']}&period={$twoFactor['totp']['period']}";
        $user->twofactor = $twoFactor;
        $user->save();

        // 發送 otp 驗證信
        Notification::route('mail', $user->email)->notify(new UserGetTotpQrCodeNotification($user));
    }

    public static function isExpired($verifyMethod, $user)
    {
        $twoFactor = $user->twofactor ?? [];

        if ($verifyMethod == 'OTP') {
            $target = @$twoFactor['otp'] ?? [];
        } elseif ($verifyMethod == 'TOTP') {
            $target =  @$twoFactor['totp'] ?? [];
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
        if ($verifyMethod == "TOTP") {
            $currentTimeSlice = floor(time() / $user->twofactor['totp']['period']);
            $discrepancy = 1;

            if (strlen($code) != 6) {
                throw new ForbiddenException('TotpCodeIncorrect');
            }

            for ($i = -$discrepancy; $i <= $discrepancy; ++$i) {
                $calculatedCode = self::getCode($user->twofactor['totp']['secret'], $currentTimeSlice + $i);
                if (self::timingSafeEquals($calculatedCode, $code)) {
                    return true;
                }
            }

            return false;
        } elseif ($verifyMethod == 'OTP') {
            if (! self::isExpired($verifyMethod, $user) && $code == $user->twofactor['otp']['code']) {
                $twofactor = $user->twofactor;
                $twofactor['otp'] = null;
                $user->twofactor = $twofactor;
                $user->save();

                return true;
            }

            return false;
        }

        return false;
    }


    private static function getCode($secret, $timeSlice = null)
    {
        if ($timeSlice === null) {
            $timeSlice = floor(time() / 30);
        }

        $secretkey =  Base32::decodeUpper($secret);
        // Pack time into binary string
        $time = chr(0).chr(0).chr(0).chr(0).pack('N*', $timeSlice);
        // Hash it with users secret key
        $hm = hash_hmac('SHA1', $time, $secretkey, true);
        // Use last nipple of result as index/offset
        $offset = ord(substr($hm, -1)) & 0x0F;
        // grab 4 bytes of the result
        $hashpart = substr($hm, $offset, 4);

        // Unpak binary value
        $value = unpack('N', $hashpart);
        $value = $value[1];
        // Only 32 bits
        $value = $value & 0x7FFFFFFF;

        $modulo = pow(10, 6);

        return str_pad($value % $modulo, 6, '0', STR_PAD_LEFT);
    }

    private static function timingSafeEquals($safeString, $userString)
    {
        if (function_exists('hash_equals')) {
            return hash_equals($safeString, $userString);
        }
        $safeLen = strlen($safeString);
        $userLen = strlen($userString);

        if ($userLen != $safeLen) {
            return false;
        }

        $result = 0;

        for ($i = 0; $i < $userLen; ++$i) {
            $result |= (ord($safeString[$i]) ^ ord($userString[$i]));
        }

        // They are only identical strings if $result is exactly 0...
        return $result === 0;
    }
}
