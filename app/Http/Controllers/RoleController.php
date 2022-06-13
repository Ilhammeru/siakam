<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Role';
        return view('role.index', compact('pageTitle'));
    }
    
    /**
     * Showing data for DataTables
     *
     * @return \Illuminate\Http\Response
     */
    public function json() {
        $data = Role::all();
        return DataTables::of($data)
            ->editColumn('name', function($data) {
                return ucwords($data->name);
            })
            ->addColumn('action', function($data) {
                return '<span class="text-info me-3" style="cursor:pointer;" onclick="deleteRole('. $data->id .')"><i class="fa fa-trash"></i></span>';
            })
            ->rawColumns(['name', 'action'])
            ->make(true);
    }

    /**
     * Get all Role data
     * 
     * @return \Illuminate\Http\Response
     */
    public function getAll() {
        try {
            $roles = Role::all();
            return sendResponse(
                $roles,
                'SUCCESS',
                201
            );
        } catch (\Throwable $th) {
            return sendResponse(
                $th->getMessage(),
                'FAILED',
                500
            );
        }
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
        $name = strtolower($request->name);
        $data = [
            'name' => $name
        ];

        try {
            $role = Role::updateOrCreate(
                $data,
                ['created_at' => Carbon::now()]
            );

            return sendResponse($data, 'SUCCESS', 201);
        } catch (\Throwable $th) {
            return sendResponse(['error' => $th->getMessage()], 'FAILED', 500);
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
            $role = Role::find($id);
            
            return sendResponse(
                $role,
                'SUCCESS',
                201
            );
        } catch (\Throwable $th) {
            return sendResponse(
                $th->getMessage(),
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
        $name = strtolower($request->name);
        $data = [
            'id' => $id
        ];

        try {
            $role = Role::updateOrCreate(
                $data,
                ['name' => $name, 'updated_at' => Carbon::now()]
            );

            return sendResponse(
                $role,
                'SUCCESS',
                201
            );
        } catch (\Throwable $th) {
            return sendResponse(
                $th->getMessage(),
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
        $roleName = Role::find($id);
        $userRole = User::select('id')
            ->where('role', $roleName)
            ->get();

        if (count($userRole) > 0) {
            return sendResponse(
                ['error' => "Masih ada user yang menggunakan Role $roleName"],
                'FAILED',
                500
            );
        }

        try {
            $delete = Role::where('id', $id)
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
