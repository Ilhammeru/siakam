<?php

namespace App\Http\Controllers;

use App\Models\BurialData;
use App\Models\Tpu;
use App\Models\TpuGrave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TpuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $pageTitle = 'TPU';
        $role = Auth::user()->role;
        $tpuId = Auth::user()->tpu_id;

        if ($role != 'tpu') {
            return view('tpu.index', compact('pageTitle'));
        } else {
            $tpu = Tpu::with(['graves' => function($query) {
                $query->orderBy('grave_block', 'asc');
            }])->find($tpuId);

            // if ajax request
            if ($request->ajax()) {
                $view = view('tpu._identity-tpu', compact('tpu'))->render();
                return sendResponse(['view' => $view]);
            }

            return view('tpu.profile', compact('pageTitle', 'tpu'));
        }
    }

    /**
     * Get JSON detail for DataTables
     * 
     * @return DataTables
     */
    public function json()
    {
        $role = Auth::user()->role;
        $where = "";
        if (strtolower($role) == 'tpu') {
            $where = "id = " . Auth::user()->tpu_id;
        } else {
            $where = "id > 0";
        }
        $data = Tpu::with('graves')
            ->whereRaw($where)
            ->get();
        
        return DataTables::of($data)
            ->addColumn('grave', function($data) {
                $count = count($data->graves);
                return $count . ' Makam';
            })
            ->addColumn('quota', function($data) {
                $graves = collect($data->graves);
                $sum = $graves->sum('quota');
                return '<span style="color: #009ef7; cursor: pointer;" onclick="detailGrave('. $data->id .')">'. $sum .'</span>';
            })
            ->addColumn('quota_left', function($data) {
                $graves = collect($data->graves);
                $sum = $graves->sum('quota');
                $burialData = BurialData::where('tpu_id', $data->id)->count();
                $left = $sum - $burialData;
                return '<span>'. $left .'</span>';
            })
            ->addColumn('action', function($data) {
                return '<span class="text-info me-3" style="cursor:pointer;" onclick="edit('. $data->id .')"><i class="fa fa-edit"></i></span>
                <span class="text-info me-3" style="cursor:pointer;" onclick="deleteTpu('. $data->id .')"><i class="fa fa-trash"></i></span>';
            })
            ->rawColumns(['action', 'grave', 'quota', 'quota_left'])
            ->make(true);
    }

    /**
     * Function to store grave's block
     * 
     * @return \Illuminate\Http\Response
     */
    public function storeGrave(Request $request) 
    {
        $name = $request->name;
        $address = $request->address;
        $phone = $request->phone;
        $graves = array_values($request->graves);

        // begin::validation
        if (Auth::user()->role != 'tpu') {
            $rules = [
                'name' => 'required|unique:tpu,name',
                'address' => 'required',
                'phone' => 'required'
            ];
            $messageRules = [
                'name.required' => 'Nama TPU Harus Diisi',
                'address.required' => 'Alamat TPU Harus Diisi',
                'phone.required' => 'No. Telfon TPU Harus Diisi',
                'name.unique' => 'Nama sudah terdaftar di database'
            ];
            $validation = Validator::make(
                $request->all(),
                $rules,
                $messageRules
            );
            if ($validation->fails()) {
                $error = $validation->errors()->all();
                return sendResponse(
                    ['error' => $error],
                    'VALIDATION_FAILED',
                    500
                );
            }
        } 

        // validation empty field in grave section
        $blockNames = [];
        foreach ($graves as $grave) {
            $blockNames[] = $grave['grave_block'];
            if ($grave['grave_block'] == "" || $grave['quota'] == "") {
                return sendResponse(
                    ['error' => ['Pastikan Blok Makam dan Quota semua terisi']],
                    'VALIDATION_FAILED',
                    500
                );
            }
        }
        $blockNames = array_values(array_count_values($blockNames));
        for ($v = 0; $v < count($blockNames); $v++) {
            if ($blockNames[$v] > 1) {
                return sendResponse(
                    ['error' => ['Pastikan tidak ada nama blok yang sama']],
                    'VALIDATION_FAILED',
                    500
                );
            }
        }
        // end::validation

        DB::beginTransaction();
        try {
            $tpuId = Auth::user()->tpu_id;
            if (Auth::user()->role != 'tpu') {
                $dataTpu = [
                    'name' => $name,
                    'address' => $address,
                    'phone' => $phone,
                    'created_at' => Carbon::now()
                ];
                $tpuId = Tpu::insertGetId($dataTpu);
            }

            $graves = collect($graves)->map(function($item) use($tpuId) {
                $item['tpu_id'] = $tpuId;
                return $item;
            })->all();
            TpuGrave::upsert($graves, ['id', 'tpu_id'], ['grave_block', 'quota']);
            DB::commit();
            return sendResponse([]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }

     /**
     * Function to edit grave's block
     * 
     * @return \Illuminate\Http\Response
     */
    public function editGrave(Request $request, $id) 
    {
        $block = $request->grave_block;
        $quota = $request->quota;
        $tpuGrave = TpuGrave::find($id);
        try {
            $tpuGrave->grave_block = $block;
            $tpuGrave->quota = $quota;
            $tpuGrave->updated_at = Carbon::now();
            $tpuGrave->save();
            return sendResponse($tpuGrave);
        } catch (\Throwable $th) {
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }

     /**
     * Function to dlete grave's block
     * 
     * @return \Illuminate\Http\Response
     */
    public function deleteGrave($id) 
    {
        try {
            $check = BurialData::where('grave_block', $id)->count();
            if ($check > 0) {
                return sendResponse(
                    ['error' => 'Tidak Bisa Menghapus Blok ini Dikarenakan Sudah Ada Data Pemakaman Di Blok Ini'],
                    'FAILED',
                    500
                );
            }
            $delete = TpuGrave::where('id', $id)
                ->delete();
            return sendResponse($delete);
        } catch (\Throwable $th) {
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }

    public function showTpu($id)
    {
        $tpu = Tpu::with('graves')->find($id);
        $name = implode('', explode(' ', $tpu->name));
        $burialData = BurialData::where('tpu_id', $id)->count();
        $graveBlock = [];
        foreach ($tpu->graves as $tg) {
            $graveBlock[] = BurialData::where('grave_block', $tg->id)->count();
        }
        $number = "";
        $formatNumber = $burialData + 1;
        $formatNumber = str_pad($formatNumber, 3, '0', STR_PAD_LEFT); 
        if (Auth::user()->role != 'tpu') {
            $number = $name . '/' . $formatNumber . '/' . romawiMonth(date('Y-m-d')) . '/' . date('Y');
        }

        return sendResponse([
            'tpu' => $tpu,
            'number' => $number,
            'graveBlock' => $graveBlock
        ]);
    }

    public function storeTpu(Request $request, $id)
    {
        $name = $request->name;
        $address = $request->address;
        $phone = (string)$request->phone;
        $currentTpu = Tpu::find($id);

        $rules = [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required'
        ];
        if (strtolower($name) != strtolower($currentTpu->name)) {
            $rules['name'] = 'unique:tpu,name';
        }
        $messageRules = [
            'name.required' => 'Nama TPU Harus Diisi',
            'address.required' => 'Alamat TPU Harus Diisi',
            'phone.required' => 'No. Telfon TPU Harus Diisi',
            'name.unique' => 'Nama sudah ada di database'
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
                "VALIDATION_FAILED",
                500
            );
        }

        try {
            $data = [
                'name' => $name,
                'address' => $address,
                'phone' => $phone,
                'updated_at' => Carbon::now()
            ];
            $update = Tpu::updateOrCreate(
                ['id' => $id],
                $data
            );
            return sendResponse(['update' => $update, 'data' => $data]);
        } catch (\Throwable $th) {
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }

    /**
     * Get available grave detail per TPU by TPU's ID
     * 
     * @return \Illuminate\Http\Response
     */
    public function detailGrave($id) {
        $tpu = Tpu::with(['graves'])->find($id);
        $graves = $tpu->graves;

        $burialData = [];
        foreach($graves as $g) {
            $burialData[] = BurialData::where('grave_block', $g->id)->count();
        }
        
        $view = view('tpu._detail-grave', compact('graves', 'burialData'))->render();
        return sendResponse(['view' => $view]);
    }

    /**
     * Get detail TPU's Grave for edit
     * 
     * @return \Illuminate\Http\Response
     */
    public function detailTpuGrave($id) {
        $grave = TpuGrave::find($id);

        return sendResponse($grave);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $name = $request->name;
        $address = $request->address;
        $phone = $request->phone;
        $graves = array_values($request->graves);
        $currentTpu = Tpu::with('graves')->find($id);

        // begin::validation
        if (Auth::user()->role != 'tpu') {
            $rules = [
                'name' => 'required',
                'address' => 'required',
                'phone' => 'required'
            ];
            if (strtolower($name) != strtolower($currentTpu->name)) {
                $rules['name'] = 'unique:tpu,name';
            }
            $messageRules = [
                'name.required' => 'Nama TPU Harus Diisi',
                'address.required' => 'Alamat TPU Harus Diisi',
                'phone.required' => 'No. Telfon TPU Harus Diisi',
                'name.unique' => 'Nama sudah terdaftar di database'
            ];
            $validation = Validator::make(
                $request->all(),
                $rules,
                $messageRules
            );
            if ($validation->fails()) {
                $error = $validation->errors()->all();
                return sendResponse(
                    ['error' => $error],
                    'VALIDATION_FAILED',
                    500
                );
            }
        } 

        $blockNames = [];
        foreach ($graves as $grave) {
            $blockNames[] = $grave['grave_block'];
            if ($grave['grave_block'] == "" || $grave['quota'] == "") {
                return sendResponse(
                    ['error' => ['Pastikan Blok Makam dan Quota semua terisi']],
                    'VALIDATION_FAILED',
                    500
                );
            }
        }
        $blockNames = array_values(array_count_values($blockNames));
        for ($v = 0; $v < count($blockNames); $v++) {
            if ($blockNames[$v] > 1) {
                return sendResponse(
                    ['error' => ['Pastikan tidak ada nama blok yang sama']],
                    'VALIDATION_FAILED',
                    500
                );
            }
        }
        // end::validation

        DB::beginTransaction();
        try {
            $tpuId = Auth::user()->tpu_id;
            if (Auth::user()->role != 'tpu') {
                $currentTpu->name = $name;
                $currentTpu->address = $address;
                $currentTpu->phone = $phone;
                $currentTpu->updated_at = Carbon::now();
                $currentTpu->save();
                $tpuId = $currentTpu->id;
            }

            $graves = collect($graves)->map(function($item) use($tpuId) {
                $item['tpu_id'] = $tpuId;
                return $item;
            })->all();
            TpuGrave::upsert($graves, ['id', 'tpu_id'], ['grave_block', 'quota']);
            // TpuGrave::where("tpu_id", $tpuId)->delete();
            // TpuGrave::insert($dataGrave);
            DB::commit();
            return sendResponse([]);
        } catch (\Throwable $th) {
            DB::rollBack();
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
        DB::beginTransaction();
        try {
            $check = BurialData::where('tpu_id', $id)->count();
            if ($check > 0) {
                return sendResponse(
                    ['error' => 'Tidak bisa menghapus TPU dikarenakan sudah ada data pemakaman'],
                    'FAILED',
                    500
                );
            }
            // delete all grave data
            TpuGrave::where("tpu_id", $id)->delete();
            // delete tpu data
            $delete = Tpu::where("id", $id)->delete();
            // delete tpu_id in users table
            User::where("tpu_id", $id)->update(
                ['tpu_id' => NULL, 'updated_at' => Carbon::now()]
            );
            DB::commit();
            return sendResponse(['delete' => $delete, 'id' => $id]);
        } catch (\Throwable $th) {
            DB::rollBack();
            //throw $th;
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }
}
