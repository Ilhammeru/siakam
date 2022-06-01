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

if (!function_exists('generateRandomWallet')) {
    function generateRandomWallet($length = 35) {
        $chars = '0123456789abcdef';
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

if (!function_exists('getSetting')) {
    function getSetting($name){
        $result = Setting::where('name', $name)->first();
        if(!$result){
            return NULL;
        } else {
            return $result->value;
        }
    }
}

if (!function_exists('calculateBonuses')) {
    function calculateBonuses($user_id){
        $sponsor_bonus = calculateSponsorBonus($user_id);
        calculateRoyaltyBonus($user_id, $sponsor_bonus);
        calculateGenerationBonus($user_id);

    }
}

if (!function_exists('calculateSponsorBonus')) {
    function calculateSponsorBonus($user_id){
        $user = User::find($user_id);

        if(!$user->sponsor_id){
            return;
        }

        $amount = getSetting('registration_payment');
        $presentase_bonus = getSetting('sponsor_bonus');
        $sponsor_bonus = $amount * $presentase_bonus / 100;

        $bonus = Bonus::where('user_id', $user->sponsor_id)->where('type', 'sponsor')->first();
        $sponsor = User::find($user->sponsor_id);
        if(!$bonus){
            $bonus = new Bonus;
            $bonus->user_id = $user->sponsor_id;
            $bonus->type = 'sponsor';
            $bonus->amount = $sponsor_bonus;
            $bonus->save();
        } else {
            $bonus->amount = $bonus->amount + $sponsor_bonus;
            $bonus->save();
        }

        $sponsor->wallet_balance = $sponsor->wallet_balance + $sponsor_bonus;
        $sponsor->save();

        $bonus_log = new BonusLog;
        $bonus_log->user_id = $user->sponsor_id;
        $bonus_log->type = 'sponsor';
        $bonus_log->from_id = $user->id;
        $bonus_log->bonus_id = $bonus->id;
        $bonus_log->amount = $sponsor_bonus;
        $bonus_log->generation = 0;
        $bonus_log->save();

        return $sponsor_bonus;
    }
}

if (!function_exists('usageUserPerEmail')) {
    function usageUserPerEmail(String $email) {
        $usage = Prospect::where('email', $email)->count();
        $maxUsage = Setting::where('name', 'max_usage_per_email')->first()->value;
        return $usage >= $maxUsage ? false : true;
    }
}

if (!function_exists('calculateRoyaltyBonus')) {
    function calculateRoyaltyBonus($user_id, $sponsor_bonus){
        $user = User::find($user_id);

        if(!$user->sponsor_id){
            return;
        }
        
        $royalty = UserNetwork::where('user_id', $user->id)->where('gen', 2)->with('user')->first();
        if($royalty){
            $userGetRoyalty = User::find($royalty->user->id);
    
            $presentase_bonus = getSetting('royalty_bonus');
            $royalty_bonus = $sponsor_bonus * $presentase_bonus / 100;
    
            $bonus = Bonus::where('user_id', $userGetRoyalty->id)->where('type', 'royalty')->first();
            if(!$bonus){
                $bonus = new Bonus;
                $bonus->user_id = $userGetRoyalty->id;
                $bonus->type = 'royalty';
                $bonus->amount = $royalty_bonus;
                $bonus->save();
            } else {
                $bonus->amount = $bonus->amount + $royalty_bonus;
                $bonus->save();
            }

            $userGetRoyalty->wallet_balance = $userGetRoyalty->wallet_balance + $royalty_bonus;
            $userGetRoyalty->save();
    
            $bonus_log = new BonusLog;
            $bonus_log->user_id = $userGetRoyalty->id;
            $bonus_log->type = 'royalty';
            $bonus_log->from_id = $user->id;
            $bonus_log->bonus_id = $bonus->id;
            $bonus_log->amount = $royalty_bonus;
            $bonus_log->generation = 0;
            $bonus_log->save();
        }

        return;
    }
}

if (!function_exists('saveSerialPin')) {
    function saveSerialPin($user_id) {
        $serial = new Serial();
        $serial->serial = generateRandomString();
        $serial->pin = generateRandomNumber();
        $serial->owner_id = $user_id;
        $serial->save();

        return $serial;
    }
}

if (!function_exists('calculateGenerationBonus')) {
    function calculateGenerationBonus($user_id){
        $user = User::find($user_id);

        if(!$user->sponsor_id){
            return;
        }

        $generations = UserNetwork::where('user_id', $user->id)->where('gen','<=', 10)->with('user')->get();
        $gen = 1;
        foreach ($generations as $key => $generation) {
            // $userGetGeneration = User::find($generation->user->id);

            $amount = getSetting('registration_payment');
            $presentase_bonus = getSetting('generation_bonus');
            $generation_bonus = $amount * $presentase_bonus / 100;

            $bonus = Bonus::where('user_id', $generation->user->id)->where('type', 'generation')->first();
            $userGetGeneration = User::find($generation->user->id);
            if(!$bonus){
                $bonus = new Bonus;
                $bonus->user_id = $generation->user->id;
                $bonus->type = 'generation';
                $bonus->amount = $generation_bonus;
                $bonus->save();
            } else {
                $bonus->amount = $bonus->amount + $generation_bonus;
                $bonus->save();
            }

            $userGetGeneration->wallet_balance = $userGetGeneration->wallet_balance + $generation_bonus;
            $userGetGeneration->save();

            $bonus_log = new BonusLog;
            $bonus_log->user_id = $generation->user->id;
            $bonus_log->type = 'generation';
            $bonus_log->from_id = $user->id;
            $bonus_log->bonus_id = $bonus->id;
            $bonus_log->amount = $generation_bonus;
            $bonus_log->generation = $gen;
            $bonus_log->save();

            $gen = $gen + 1;
        }

        return;
    }
}

if(!function_exists('getSponsorTree')){

    function getSponsorTree($sponsor_id, $include_parent = false)
    {   
        $sponsors = User::select('id', 'name as text', 'username', 'referral_code')->where('role', 'member')->where('sponsor_id', $sponsor_id)->get();
        
        $sponsors = $sponsors->map(function($sponsor){
            $sponsor->icon = asset('images/user_sm.png');
            $sponsor->children = getSponsorTree($sponsor->id)['sponsors'];
            $sponsor->state = [
                'opened' => true,
            ];
            $sponsor->action = "";
            return $sponsor;
        });
        if($include_parent){
            $parents = User::select('id', 'name as text', 'username')->where('role', 'member')->where('id', $sponsor_id)->get();
            $parents = $parents->map(function($parent) use ($sponsors){
                $parent->icon = asset('images/user_sm.png');
                $parent->children = $sponsors;
                $parent->state = [
                    'opened' => true,
                ];
                return $parent;
            });
            return [
                'sponsors' => $parents
            ];
        }
        $dummy_parent = getParentTree($sponsor_id,$include_parent);
        return [
            'sponsors' => $sponsors,
            'parent' => $dummy_parent
        ];
    }
}

if (!function_exists('getChildrenTree')) {
    function getChildrenTree($sponsor_id) {
        $childrens = User::select('id', 'name as text', 'referral_code')
            ->where(['sponsor_id'=>$sponsor_id])
            ->get();

        $childrens = $childrens->map(function($child) {
            $child->icon = asset('images/user_sm.png');
            $child->state = [
                'opened' => true
            ];
            return $child;
        });

        return $childrens;
    }
}

if (!function_exists('getParentTree')) {
    function getParentTree($id, $include_parent = FALSE) {
        $users = User::select('id', 'name as text', 'username', 'referral_code')
                ->where(['sponsor_id'=>$id])
                ->where(['role'=>'member'])
                ->get();

        $users = $users->map(function($user) {
            $user->icon = asset('images/user_sm.png');
            $user->state = [
                'opened' => true
            ];
            $user->children = getParentTree($user->id);
            $user->action = '';

            return $user;
        });
        $a = 0;
        $users = $users->map(function($us) use ($a) {
            $us->children = array_merge($us->children->toArray(), [
                [
                    'id' => $a,
                    'text' => '<a href="#">Tambah Member</a>',
                    'icon' => asset('images/add-button.png'),
                    'username' => '',
                    'action' => url('register/' . $us->id . '/in')
                ]
            ]);
            $a++;
            return $us;
        });
        if ($include_parent) {
            $parents = User::select('id', 'name as text', 'username', 'referral_code')
                    ->where(['id'=>$id,'role'=>'member'])
                    ->get();

            $b = 0;
            $parents = $parents->map(function($parent) use($b, $users) {
                $parent->icon = asset('images/user_sm.png');
                $parent->state = [
                    'opened' => true
                ];
                $parent->children = array_merge($users->toArray(), [
                    [
                        'id' => $b + 1,
                        'text' => '<a href="#">Tambah Member</a>',
                        'icon' => asset('images/add-button.png'),
                        'username' => '',
                        'action' => url('register/' . $parent->id . '/in')
                    ]
                ]);
                $b++;
                return $parent;
            });
            return $parents;
        }
        return $users;
    }
}

if (!function_exists('getIpInfo')) {

    function getIpInfo()
    {
        $ip = $_SERVER["REMOTE_ADDR"];

        //Deep detect ip
        if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        $xml = @simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=" . $ip);

        $country = @$xml->geoplugin_countryName;
        $city = @$xml->geoplugin_city;
        $area = @$xml->geoplugin_areaCode;
        $code = @$xml->geoplugin_countryCode;
        $long = @$xml->geoplugin_longitude;
        $lat = @$xml->geoplugin_latitude;

        $data['country'] = $country;
        $data['city'] = $city;
        $data['area'] = $area;
        $data['code'] = $code;
        $data['long'] = $long;
        $data['lat'] = $lat;
        $data['ip'] = request()->ip();
        $data['time'] = date('d-m-Y h:i:s A');

        return $data;
    }
}

if (!function_exists('getOsBrowser')) {

    function getOsBrowser()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $osPlatform = "Unknown OS Platform";
        $osArray = array(
            '/windows nt 10/i' => 'Windows 10',
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile'
        );
        foreach ($osArray as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                $osPlatform = $value;
            }
        }
        $browser = "Unknown Browser";
        $browserArray = array(
            '/msie/i' => 'Internet Explorer',
            '/firefox/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/chrome/i' => 'Chrome',
            '/edge/i' => 'Edge',
            '/opera/i' => 'Opera',
            '/netscape/i' => 'Netscape',
            '/maxthon/i' => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i' => 'Handheld Browser'
        );
        foreach ($browserArray as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                $browser = $value;
            }
        }

        $data['os_platform'] = $osPlatform;
        $data['browser'] = $browser;

        return $data;
    }
}

if (!function_exists('sendEmail')) {

    function sendEmail($data)
    {
        $subjects = [
            'register' => 'Pendaftaran Berhasil',
            'prospect_register' => 'Pendaftaran Calon Member',
            'prospect_registered' => 'Proses Pendaftaran Telah Selesai',
            'otp' => 'Kode OTP Verifikasi',
            'new_prospect' => 'Prospect Baru',
            'paid_prospect_to_sponsor' => 'Aktivasi Prospek',
            'paid_prospect_to_prospect' => 'Aktivasi Akun',
            'custom_email' => $data['custom_subject'] ?? ""
        ];

        $setting = Setting::all();

        $config = [
            'name' => $setting->where('name', 'email_name')->first()->value,
            'email' => $setting->where('name', 'email_address')->first()->value,
            'host' => $setting->where('name', 'email_host')->first()->value,
            'port' => $setting->where('name', 'email_port')->first()->value,
            'username' => $setting->where('name', 'email_username')->first()->value,
            'password' => $setting->where('name', 'email_password')->first()->value,
            'encryption' => $setting->where('name', 'email_encryption')->first()->value,
        ];

        $mail = new PHPMailer(true);
        $html = isset($data['message']) ? $data['message'] : view('email_template.'.$data['service'], $data['content'])->render();

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = $config['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $config['username'];
            $mail->Password   = $config['password'];
            if ($config['encryption'] == 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            }else{
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            $mail->Port       = $config['port'];
            $mail->CharSet = 'UTF-8';
            //Recipients
            $mail->setFrom($config['email'], $config['name']);
            $mail->addAddress($data['receiver'], $data['receiver_name']);
            $mail->addReplyTo($config['email'], $config['name']);
            // Content
            $mail->isHTML(true);
            $mail->Subject = $subjects[$data['subject']];
            $mail->Body    = $html;
            $mail->send();
        } catch ( \Throwable $e) {
            return $e->getMessage();
        }

        return true;
    }
}

if (!function_exists('formatWhatsappNumber')) {
    function formatWhatsappNumber($number) {
        if ($number != NULL || $number != "") {
            $split = str_split($number);
            if ($split[0] == 0) {
                $split[0] = '62';
                $number = implode('', $split);
            }
        }
        return $number;
    }
}


// if (!function_exists('getUpline')) {

//     function getUpline($userID)
//     {
//         if (!$userID) {
//             return NULL;
//         }
//         $user = User::find($userID);
//         $user->sponsor = getUpline($user->sponsor_id);
//         return $user;
//     }
// }