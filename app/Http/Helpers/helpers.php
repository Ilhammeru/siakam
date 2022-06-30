<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserImage;
use App\Models\UserNetwork;
use App\Models\Bonus;
use App\Models\BonusLog;
use App\Models\Prospect;
use App\Models\Serial;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

/**
 * Write code on Method
 *
 * @return response()
 */
if (! function_exists('menuActive')) {
    function menuActive($routeName)
    {
        $class = 'active';
        
        if (is_array($routeName)) {
            foreach ($routeName as $key => $value) {
                if (request()->routeIs($value)) {
                    return $class;
                }
            }
        } elseif (request()->routeIs($routeName)) {
            return $class;
        }
    }
}

if (! function_exists('menuShow')) {
    function menuShow($routeName)
    {
        $class = 'show';
        
        if (is_array($routeName)) {
            foreach ($routeName as $key => $value) {
                if (request()->routeIs($value)) {
                    return $class;
                }
            }
        } elseif (request()->routeIs($routeName)) {
            return $class;
        }
    }
}

if (!function_exists('generateRandomString')) {
    function generateRandomString($length = 8) {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charsLength = strlen($chars);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $chars[rand(0, $charsLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('generateRandomNumber')) {
    function generateRandomNumber($length = 6) {
        $chars = '0123456789';
        $charsLength = strlen($chars);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $chars[rand(0, $charsLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('formatWA')) {
    function formatWA($phone) {
        $phone = preg_replace('/[\(\)\s.+-]/i', "", $phone);
        if(!preg_match('/[^0-9]/', trim($phone))) {
            if(substr(trim($phone), 0, 1) === '0'){
                $phone = '62'.substr(trim($phone), 1);
            }
        }
        return $phone;
    }
}

if (!function_exists('formatRupiah')) {
    function formatRupiah($number){
    $result = "Rp " .number_format($number,0,',','.'). ",-";
    return $result;
    }
}

if (!function_exists('sendResponse')) {
    function sendResponse($data, $message = 'SUCCESS', $status = 201) {
        $format = [
            'data' => $data,
            'message' => $message
        ];
        return response()->json($format, $status);
    }
}

if (!function_exists('formatIndonesiaDate')) {
    function formatIndonesiaDate($dateFormat) {
        $month = date('m', strtotime($dateFormat));
        $monthInd = formatIndonesiaMonth($month);
        $date = date('d', strtotime($dateFormat));
        $year = date('Y', strtotime($dateFormat));
        $new = $date . ' ' . $monthInd . ' ' . $year;

        return $new;
    }
}

if (!function_exists('formatIndonesiaMonth')) {
    function formatIndonesiaMonth($month) {
        $newMonth = "";
        $split = str_split($month);
        if (count($split) > 1) {
            if ($split[0] == 0) {
                $month = $split[1];
            } else {
                $month = implode('', $split);
            }
        }
        switch ($month) {
            case '1':
                $newMonth = 'Januari';
                break;
            case '2':
                $newMonth = 'Febuari';
                break;
            case '3':
                $newMonth = 'Maret';
                break;
            case '4':
                $newMonth = 'April';
                break;
            case '5':
                $newMonth = 'Mei';
                break;
            case '6':
                $newMonth = 'Juni';
                break;
            case '7':
                $newMonth = 'Juli';
                break;
            case '8':
                $newMonth = 'Agustus';
                break;
            case '9':
                $newMonth = 'September';
                break;
            case '10':
                $newMonth = 'Oktober';
                break;
            case '11':
                $newMonth = 'November';
                break;
            case '12':
                $newMonth = 'Desember';
                break;
            default:
                $newMonth = 'Tidak Ter Generate';
                break;
        }

        return $newMonth;
    }
}

if (!function_exists('formatIndonesiaDay')) {
    function formatIndonesiaDay($date) {
        $days = date('D', strtotime($date));
        switch (strtolower($days)) {
            case 'sun':
                $day = 'Minggu';
                break;
            case 'mon':
                $day = 'Senin';
                break;
            case 'tue':
                $day = 'Selasa';
                break;
            case 'wed':
                $day = 'Rabu';
                break;
            case 'thu':
                $day = 'Kamis';
                break;
            case 'fri':
                $day = 'Jumat';
                break;
            case 'sat':
                $day = 'Sabtu';
                break;
            
            default:
                $day = 'Belum Ter Generate';
                break;
        }

        return $day;
    }
}

if (!function_exists('romawiMonth')) {
    function romawiMonth($date) {
        $month = date('m', strtotime($date));
        $split = str_split($month);
        if (count($split) > 1) {
            if ($split[0] == 0) {
                $month = $split[1];
            } else {
                $month = implode('', $split);
            }
        }
        switch ($month) {
            case '1':
                $ret = 'I';
                break;

            case '2':
                $ret = 'II';
                break;

            case '3':
                $ret = 'III';
                break;

            case '4':
                $ret = 'IV';
                break;

            case '5':
                $ret = 'V';
                break;

            case '6':
                $ret = 'VI';
                break;

            case '7':
                $ret = 'VII';
                break;

            case '8':
                $ret = 'VIII';
                break;

            case '9':
                $ret = 'IX';
                break;

            case '10':
                $ret = 'X';
                break;

            case '11':
                $ret = 'XI';
                break;

            case '12':
                $ret = 'XII';
                break;
            
            default:
                $ret = "Belum ter generate";
                break;
        }

        return $ret;
    }
}