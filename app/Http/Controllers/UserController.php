<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Tpu;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = "User";
        return view('user.index', compact('pageTitle'));
    }

    /**
     * Showing data for DataTables
     *
     * @return \Illuminate\Http\Response
     */
    public function json() {
        $data = User::where('id', '!=', Auth::user()->id)->get();
        return DataTables::of($data)
            ->editColumn('name', function($data) {
                return ucwords($data->name);
            })
            ->editColumn('role', function($data) {
                return ucwords($data->role);
            })
            ->addColumn('action', function($data) {
                return '<span class="text-info me-3" style="cursor:pointer;" onclick="edit('. $data->id .')"><i class="fa fa-edit"></i></span>
                <span class="text-info me-3" style="cursor:pointer;" onclick="deleteUser('. $data->id .')"><i class="fa fa-trash"></i></span>';
            })
            ->rawColumns(['name', 'action', 'role'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validation
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'username' => 'required|unique:users,username',
            'password' => 'required',
            'tpu' => 'required',
            'role' => 'required'
        ];
        $messageRules = [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.unique' => "Email $request->email sudah terdapat di database",
            'username.unique' => "Username $request->username sudah terdapat di database",
            'password.required' => 'Password harus diisi',
            'tpu.required' => 'TPU harus diisi',
            'role.required' => 'Role harus diisi',
        ];
        $validator = Validator::make(
            $request->all(),
            $rules,
            $messageRules
        );

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return sendResponse(
                ['error' => $error],
                'VALIDATION_FAILED',
                500
            );
        }

        $name = $request->name;
        $email = $request->email;
        $password = Hash::make($request->password);
        $tpu = $request->tpu;
        $role = $request->role;
        $username = $request->username;
        try {
            $data = [
                'name' => $name,
                'username' => $username,
                'password' => $password,
                'email' => $email,
                'tpu_id' => $tpu,
                'role' => $role,
                'created_at' => Carbon::now()
            ];
            $user = User::insert($data);
            return sendResponse(
                $user,
                'SUCCESS',
                201
            );
        } catch (\Throwable $th) {
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = User::find($id);
            $roles = Role::get();
            $tpus = Tpu::all();
            return sendResponse(
                ['user' => $user, 'roles' => $roles, 'tpus' => $tpus],
                'SUCCESS',
                201
            );
        } catch (\Throwable $th) {
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // variable
        $name = $request->name;
        $email = $request->email;
        $password = Hash::make($request->password);
        $role = $request->role;
        $username = $request->username;
        $tpu = $request->tpu;
        $currentUser = User::find($id);

        // validation
        $rules = [
            'name' => 'required',
            'email' => 'required',
            'username' => 'required',
            'tpu' => 'required',
            'role' => 'required'
        ];
        $messageRules = [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.unique' => "Email $request->email sudah terdapat di database",
            'username.unique' => "Username $request->username sudah terdapat di database",
            'password.required' => 'Password harus diisi',
            'tpu.required' => 'TPU harus diisi',
            'role.required' => 'Role harus diisi',
        ];
        if (strtolower($username) != strtolower($currentUser->username)) {
            $rules['username'] = 'unique:users,username';
        }
        if ($email != $currentUser->email) {
            $rules['email'] = 'unique:users,email';
        }
        $validator = Validator::make(
            $request->all(),
            $rules,
            $messageRules
        );

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return sendResponse(
                ['error' => $error],
                'VALIDATION_FAILED',
                500
            );
        }
        try {
            $data = [
                'name' => $name,
                'username' => $username,
                'password' => $password,
                'email' => $email,
                'tpu_id' => $tpu,
                'role' => $role,
                'updated_at' => Carbon::now()
            ];
            $update = User::updateOrCreate(
                ['id' => $id],
                $data
            );
            return sendResponse(
                $update,
                'SUCCESS',
                201
            );
        } catch (\Throwable $th) {
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }

    public function getDataForm() {
        try {
            $roles = Role::all();
            $tpus = Tpu::all();
            return sendResponse(
                ['roles' => $roles, 'tpus' => $tpus]
            );
        } catch (\Throwable $th) {
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $delete = User::where('id', $id)
                ->delete();

            return sendResponse(
                $delete,
                'SUCCESS',
                201
            );
        } catch (\Throwable $th) {
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }
}
