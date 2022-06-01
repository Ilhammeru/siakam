<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Setting;
use App\Models\Prospect;
use App\Models\District;
use App\Models\Serial;
use App\Models\SerialLog;
use App\Models\ServiceCode;
use App\Models\UserNetwork;
use App\Services\RegisterService;
use App\Services\Whatsapp;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', [
            'except' => ['showRegistrationForm', 'submitRegister']
        ]);
    }

    public function index()
    {
        return view('auth.index');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'referral_code' => 'required',
        ]);

        $user = User::where('referral_code', $request->referral_code)->first();
        if(!$user){
            $notify[] = ['error',"Kode Referral tidak ditemukan"];
            return back()->withNotify($notify);
        }
        return redirect()->to('/register/'.$request->referral_code);
    }

    public function showRegistrationForm(String $referral_code, String $status)
    {
        if ($status == 'in') { // ***************** when request came from dashboard ********************
            $isDashboard = TRUE;
            $role = Auth::user()->role;
            $sponsors = [];
            $isSponsor = $referral_code != 0 ? TRUE : FALSE;
            $referral = User::where('id', $referral_code)->first();

            // >>>>>>>>>>>>>>>> ADMIN AREA <<<<<<<<<<<<<<<< //
            if ($role == 'admin' || $role == 'superadmin') {
                $isAdmin = TRUE;
                $sponsorsRaw = UserNetwork::with('user')->where('user_id',$referral_code)->orderBy('gen', 'desc')->get();
                foreach ($sponsorsRaw as $sp) {
                    $sponsors[] = [
                        'id' => $sp->user->id,
                        'name' => $sp->user->username
                    ];
                }
                if ($referral_code != 0) {
                    $sponsors[count($sponsors)] = [
                        'id' => $referral->id,
                        'name' => $referral->username
                    ];
                }
            } else {
                // >>>>>>>>>>>>>>>> MEMBER AREA <<<<<<<<<<<<<<<< //
                $isAdmin = FALSE;
                $sponsorsRaw = User::where(['id' => $referral_code])->first();
                $sponsors = [
                    'id' => $sponsorsRaw->id,
                    'name' => $sponsorsRaw->username
                ];

                // get serial pin
                $serialsRaw = Serial::where(['owner_id' => $sponsorsRaw->id, 'is_used' => FALSE])->orderBy('id', 'desc')->first();
                $isAlert = !$serialsRaw || $serialsRaw == NULL ? TRUE : FALSE;
            }
        } else { // ************** when request came from Referral Link ********************
            // always have sponsors
            $isDashboard = FALSE;
            $isSponsor = TRUE;
            $sponsorsRaw = User::where('referral_code', $referral_code)->first();
            $sponsors = [
                'id' => $sponsorsRaw->id,
                'name' => $sponsorsRaw->username
            ];
        }

        if ($status == 'in') {
            $serial = $serialsRaw->serial ?? ($isAdmin ? generateRandomString() : "");
            $pin = $serialsRaw->pin ?? ($isAdmin ? generateRandomNumber() : "");
        }

        $param = [
            'isDashboard' => $isDashboard,
            'sponsors' => $sponsors ?? [],
            'isAlert' => $isAlert ?? FALSE,
            'isAdmin' => $isAdmin ?? FALSE,
            'isSponsor' => $isSponsor ?? FALSE,
            'serial' => $serial ?? "",
            'pin' => $pin ?? "",
            'role' => $role ?? "",
            'status' => $status
        ];
        return view('auth.register')->with($param);
    }

    public function submitRegister(Request $request, Whatsapp $whatsapp)
    {
        $rules = [
            'name' => 'required',
            'nik' => 'required|numeric',
            'username' => 'required|unique:users|max:20|unique:prospects',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'password' => 'required|confirmed',
            'gender' => 'required',
            'address' => 'required',
            'district' => 'required',
            'post_code' => 'required|numeric',
        ];
        $status = $request->status;
        if ($status == 'in') {
            $role = Auth::user()->role;
            if ($role == 'admin' || $role == 'superadmin' || $role == 'super-admin') {
                $rules['member_type'] = 'required';
            }
        }
        $request->validate($rules);

        // validate usage user per email
        $usageUsername = usageUserPerEmail($request->email);
        if (!$usageUsername) {
            $notify[] = ['error','Jumlah user pada email ' . $request->email . ' telah mencapai batas'];
            return back()->withNotify($notify);
        }

        $referral = User::where('username', $request->referral_username)->first();
        if ($referral) {
            $sponsor = User::find($referral->id);
            if(!$sponsor){
                $notify[] = ['error',"Sponsor tidak ditemukan"];
                return back()->withNotify($notify);
            }
        }
        $sponsorId = $sponsor->id ?? NULL;

        $district = District::find($request->district);
        if(!$district){
            $notify[] = ['error',"Kode alamat tidak ditemukan"];
            return back()->withNotify($notify);
        }

        $registrationCode = date('y').generateRandomNumber();
        DB::beginTransaction();
        try {
            $prospect = new Prospect;
            $prospect->name = $request->name;
            $prospect->nik = $request->nik;
            $prospect->username = $request->username;
            $prospect->email = $request->email;
            $prospect->phone = $request->phone;
            $prospect->password = Hash::make($request->password);
            $prospect->gender = $request->gender;
            $prospect->address = $request->address;
            $prospect->is_whatsapp = $request->verify_whatsapp;
            $prospect->district_id = $request->district;
            $prospect->post_code = $request->post_code;
            $prospect->sponsor_id = $sponsorId;
            $prospect->status = 'unpaid';
            $prospect->registration_code = $registrationCode;
            if ($status == 'in') {
                $prospect->status = 'registered';
                $prospect->paid_at = date('Y-m-d H:i:s');
                $prospect->paid_confirmed_by = Auth::user()->id;

                // save to table users
                $userData = [
                    'name' => $request->name,
                    'nik' => $request->nik,
                    'username' => $request->username,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                    'gender' => $request->gender,
                    'address' => $request->address,
                    'district_id' => $request->district,
                    'post_code' => $request->post_code,
                    'is_whatsapp' => $request->verify_whatsapp,
                    'sponsor_id' => $sponsorId,
                    'is_free' => FALSE,
                    'role' => 'member'
                ];
                if ($request->has('member_type')) {
                    $userData['is_free'] = $request->member_type;
                }
                $user = User::create($userData);

                // handle serial action
                if (isset($sponsor) && gettype($sponsor) != 'array') {
                    $serial = Serial::updateOrCreate(
                        ['serial' => $request->serial_user, 'pin' => $request->pin_user],
                        ['is_used' => TRUE, 'owner_id' => $sponsor->id]
                    );
    
                    $serialLog = new SerialLog;
                    $serialLog->type = 'use';
                    $serialLog->serial_id = $serial->id;
                    $serialLog->from_id = $serial->owner_id;
                    $serialLog->to_id = $user->id;
                    $serialLog->status = 'success';
                    $serialLog->save();
                }

                if ($sponsorId != null) {
                    $dna_upline = UserNetwork::where('user_id', $prospect->sponsor_id)->get();
    
                    UserNetwork::create([
                        'user_id' => $user->id,
                        'parent_id' => $prospect->sponsor_id,
                        'gen' => 1,
                    ]);
        
                    foreach ($dna_upline as $key => $node) {
                        UserNetwork::create([
                            'user_id' => $user->id,
                            'parent_id' => $node->parent_id,
                            'gen' => $node->gen + 1,
                        ]);
                    }
                } 
                // save to prospect when its not first gen of member
                if ($sponsorId != null) {
                    $prospect->save();
                }
            } else {
                $prospect->save();
            }

            if ($status == 'in') {
                $expire = date('Y-m-d H:i:s', strtotime('+1 day'));
                $code = generateRandomString(16);
                $serviceCodeData = [
                    'user_id' => $user->id,
                    'service' => 'register',
                    'code' => $code,
                    'expire' => $expire,
                ];
    
                $service_code = ServiceCode::create($serviceCodeData);
    
                $content = [
                    'name' => $request->name,
                    'username' => $request->username,
                    'code' => $code,
                    'expire' => $expire,
                    'setting' => Setting::all(),
                ];
            } else {
                $content = [
                    'name' => $request->name,
                    'username' => $request->username,
                    'password' => $request->password,
                    'registration_code' => $registrationCode,
                    'setting' => Setting::all(),
                ];
            }

            $data = [
                'subject' => $status == 'in' ? 'prospect_registered' : 'prospect_register',
                'receiver' => $prospect->email,
                'receiver_name' => $prospect->name,
                'service' => $status == 'in' ? 'prospect_registered' : 'prospect_register',
                'content' => $content,
            ];

            sendEmail($data);
            if ($status == 'in') {
                $whatsapp->sendRegistrationMessage($request->name,$request->phone,$request->username,$request->password,$status, $registrationCode);
            } else {
                $registerService = new RegisterService();
                $registerService->notificationNewProspect($request, $sponsorId);
            }

        } catch (\Throwable $e) {
            DB::rollback();
            $notify[] = ['error',$e->getMessage()];
            return back()->withNotify($notify);
        }
        DB::commit();

        if ($status == 'in') {
            $notify[] = ['success', 'Pendaftaran berhasil'];
            return redirect()->to('/sponsor-tree')->withNotify($notify);
        } else {
            $notify[] = ['success','Pendaftaran berhasil. Silahkan lakukan pembayaran untuk aktivasi akun'];
            return redirect()->to('/register/'.$registrationCode.'/success/regis')->withNotify($notify);
        }
    }

    public function confirmEmail($username, $code)
    {
        $user = User::where('username', $username)->where('role', 'member')->first();
        if(!$user || $user->email_verified_at != null){
            $notify[] = ['error','Kode verifikasi tidak ditemukan'];
            return redirect()->to('login')->withNotify($notify);
        }

        $serviceCode = ServiceCode::where('code', $code)->where('service', 'register')->where('user_id', $user->id)->where('expire', '>=', Carbon::now())->first();
        if(!$serviceCode){
            $notify[] = ['error','Kode verifikasi tidak ditemukan'];
            return redirect()->to('login')->withNotify($notify);
        }

        $user->email_verified_at = Carbon::now();
        $user->save();

        $notify[] = ['success','Akun anda telah aktif. Silahkan login'];
        return redirect()->to('login')->withNotify($notify);
    }

    public function showSuccess($registrationCode)
    {
        $prospect = Prospect::where('registration_code', $registrationCode)->first();
        $settings = Setting::all();

        return view('auth.success', compact('prospect', 'settings'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users', 'unique:prospects'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'nik' => $data['nik'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'member',
        ]);
    }
    
    /**
     * automateGenerate
     *
     * @param  mixed $request
     * @return void
     */
    public function automateGenerate(Request $request) {
        $downlineID = $request->id;

        
    }
}
