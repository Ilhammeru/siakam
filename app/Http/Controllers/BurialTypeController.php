<?php

namespace App\Http\Controllers;

use App\Models\BurialType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BurialTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Jenis Pemakaman';
        return view('burial-type.index', compact('pageTitle'));
    }

    /**
     * Showing data for DataTables
     *
     * @return \Illuminate\Http\Response
     */
    public function json() {
        $data = BurialType::all();
        return DataTables::of($data)
            ->editColumn('name', function($data) {
                return ucwords(strtolower($data->name));
            })
            ->addColumn('action', function($data) {
                return '<span class="text-info me-3" style="cursor:pointer;" onclick="edit('. $data->id .')"><i class="fa fa-edit"></i></span>
                    <span class="text-info me-3" style="cursor:pointer;" onclick="deleteBurialType('. $data->id .')"><i class="fa fa-trash"></i></span>';
            })
            ->rawColumns(['name', 'action'])
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
        $name = $request->name;
        $rules = ['name' => 'required|unique:burial_type,name'];
        $messageRule = [
            'name.required' => 'Nama Harus Diisi',
            'name.unique' => 'Nama Sudah Ada di Database'
        ];
        $validator = Validator::make(
            $request->all(),
            $rules,
            $messageRule
        );
        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return sendResponse(
                ['error' =>  $error],
                'VALIDATION_FAILED',
                500
            );
        }

        try {
            $data = [
                'name' => $name,
                'created_at' => Carbon::now()
            ];
            $burial = BurialType::insert($data);
            return sendResponse($burial);
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
     * @param  \App\Models\BurialType  $burialType
     * @return \Illuminate\Http\Response
     */
    public function show(BurialType $burialType)
    {
        return sendResponse($burialType);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BurialType  $burialType
     * @return \Illuminate\Http\Response
     */
    public function edit(BurialType $burialType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BurialType  $burialType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BurialType $burialType)
    {
        $name = $request->name;
        $rules = ['name' => 'required'];
        if (strtolower($name) != strtolower($burialType->name)) {
            $rules['name'] = 'unique:burial_type,name';
        }
        $messageRule = [
            'name.required' => 'Nama Harus Diisi',
            'name.unique' => 'Nama Sudah Ada di Database'
        ];
        $validator = Validator::make(
            $request->all(),
            $rules,
            $messageRule
        );
        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return sendResponse(
                ['error' =>  $error],
                'VALIDATION_FAILED',
                500
            );
        }
        
        try {
            $burialType->name = $name;
            $burialType->updated_at = Carbon::now();
            $burialType->save();
            return sendResponse($burialType);
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
     * @param  \App\Models\BurialType  $burialType
     * @return \Illuminate\Http\Response
     */
    public function destroy(BurialType $burialType)
    {
        try {
            $delete = BurialType::where('id', $burialType->id)
                ->delete();
            sendResponse($delete);
        } catch (\Throwable $th) {
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }
}
