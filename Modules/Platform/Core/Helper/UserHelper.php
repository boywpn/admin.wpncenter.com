<?php

namespace Modules\Platform\Core\Helper;

use Carbon\Carbon;
use Laravolt\Avatar\Avatar;
use Modules\Platform\User\Entities\TimeFormat;
use Modules\Platform\User\Entities\User;
use Modules\Platform\User\Repositories\UserRepository;

/**
 * Logged User Helper
 *
 * Class UserHelper
 * @package Modules\Platform\Core\Helper
 */
class UserHelper
{
    const PROFILE_PICTURE_GRAVATAR = 'gravatar';

    const PROFILE_PICTURE_OWN = 'image';

    const PROFILE_PICTURE_INITIALS = 'initials';

    const PROFILE_PICTURE_UPLOAD = 'storage/files/profile/';


    /**
     * Get User profile image by userId
     * @param $userId
     * @return \Intervention\Image\Image|mixed|string
     */
    public static function getUserProfileImage($userId)
    {
        $userRepository = \App::make(UserRepository::class);

        $user = $userRepository->findWithoutFail($userId);

        if ($user != null) {
            return self::prepareFileImage($user, false);
        } else {
            return config('app.url') . '/bap/images/user.png';
        }
    }

    /**
     * Prepare user image
     * @param $user
     * @param bool $returnImage
     * @return \Intervention\Image\Image|mixed|string
     */
    private static function prepareFileImage($user, $returnImage = true)
    {
        $size = 50;
        $default = config('app.url') . '/bap/images/user.png';

        if ($user->profile_pic_conf == self::PROFILE_PICTURE_GRAVATAR) {
            $image = md5(strtolower($user->email));

            $imageUrl = "https://www.gravatar.com/avatar/" . $image . "&s=" . $size;

            if ($returnImage) {
                $format = '<img src="%1$s" width="%2$s" heigh="%2$s" />';

                return sprintf($format, $imageUrl, $size);
            } else {
                return $imageUrl;
            }
        } elseif ($user->profile_pic_conf == self::PROFILE_PICTURE_OWN) {
            $imageUrl = config('app.url') . '/' . self::PROFILE_PICTURE_UPLOAD . $user->profile_image_path;

            if ($returnImage) {
                $format = '<img src="%1$s" width="%2$s" heigh="%2$s" />';

                return sprintf($format, $imageUrl, $size);
            } else {
                return $imageUrl;
            }
        } elseif ($user->profile_pic_conf == self::PROFILE_PICTURE_INITIALS) {
            $avatar = new Avatar();
            $avatar->create($user->name);

            $avatar->setBackground('#f44336');
            $avatar->setBorder(1, '#f44336');
            $avatar->setDimension(100, 100);
            $avatar->setFontSize(40);

            $imageUrl = $avatar->toBase64();

            if ($returnImage) {
                $format = '<img src="%1$s" width="%2$s" heigh="%2$s" />';

                return sprintf($format, $imageUrl, $size);
            } else {
                return $imageUrl->getEncoded();
            }
        } else {
            if ($returnImage) {
                $format = '<img src="%1$s" width="%2$s" heigh="%2$s" />';
                return sprintf($format, $default, $size);
            } else {
                return $default;
            }
        }
    }

    /**
     * Render user profile image
     * @return \Intervention\Image\Image|mixed|string
     */
    public static function profileImage()
    {
        $user = \Auth::user();

        return self::prepareFileImage($user, true);
    }



    /**
     * Return logged user js date format
     * @return string
     */
    public static function userJsDateFormat()
    {
        $auth = \Auth::user();
        if ($auth->dateFormat != null) {
            return $auth->dateFormat->js_details;
        } else {
            return '';
        }
    }

    /**
     * Return logged user js time format
     * @return string
     */
    public static function userJsTimeFormat()
    {
        $auth = \Auth::user();
        if ($auth->timeFormat != null) {
            return $auth->timeFormat->js_details;
        } else {
            return '';
        }
    }

    /**
     * Format $date in logged user format (DateFormat + TimeZone)
     *
     * @param $date
     * @param string $default
     * @return string
     */
    public static function formatUserDate($date, $default = null)
    {
        $user = \Auth::user();

        if ($user != null && $date != null && $user->dateFormat != null) {
            $date = Carbon::parse($date);

            if ($user->time_zone != '') {
                $date->setTimezone($user->time_zone);
            }

            return $date->format($user->dateFormat->details);
        } else {
            return $default;
        }
    }


    /**
     *
     * Format $datetime in logged user format (DateFormat + TimeZone)
     * @param $datetime
     * @param string $default
     * @return string
     */
    public static function formatUserDateTime($datetime, $default = null)
    {
        $user = \Auth::user();

        if ($user != null && $datetime != null && $user->dateFormat != null && $user->timeFormat != null) {
            $datetime = Carbon::parse($datetime);

            if ($user->time_zone != '') {
                $datetime->setTimezone($user->time_zone);
            }

            return $datetime->format($user->dateFormat->details . ' ' . $user->timeFormat->details);
        } else {
            return $default;
        }
    }

    /**
     * Generate random password
     * @param $length
     * @return bool|string
     */
    public static function randomPassword($length)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, $length);
    }
}
