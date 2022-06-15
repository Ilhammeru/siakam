<?php

namespace App\Http\Controllers;

use App\Models\Tpu;
use App\Models\TpuGrave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            ->addColumn('available_grave', function($data) {
                $graves = collect($data->graves);
                $avail = $graves->where('is_available', 1)->all();
                $count = count($avail);
                return '<span class="text-info" onclick="detailGrave('. $data->id .')" style="color: blue; text-decoration: underline; cursor: pointer;">'. $count .' Tersedia</span>';
            })
            ->addColumn('action', function($data) {
                return '<span class="text-info me-3" style="cursor:pointer;" onclick="edit('. $data->id .')"><i class="fa fa-edit"></i></span>
                <span class="text-info me-3" style="cursor:pointer;" onclick="deleteTpu('. $data->id .')"><i class="fa fa-trash"></i></span>';
            })
            ->rawColumns(['action', 'grave', 'available_grave'])
            ->make(true);
    }

    /**
     * Function to store grave's block
     * 
     * @return \Illuminate\Http\Response
     */
    public function storeGrave(Request $request) 
    {
        $block = $request->grave_block;
        $status = $request->status;
        $tpuId = Auth::user()->tpu_id;
        try {
            $data = [
                'tpu_id' => $tpuId,
                'is_available' => $status,
                'created_at' => Carbon::now()
            ];
            $grave = TpuGrave::updateOrCreate(
                ['grave_block' => $block],
                $data
            );
            return sendResponse($grave);
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
        $tpu = Tpu::find($id);

        return sendResponse($tpu);
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
        $tpu = Tpu::with(['graves' => function($query) {
            $query->where('is_available', 1);
        }])->find($id);
        $graves = $tpu->graves;
        $view = view('tpu._detail-grave', compact('graves'))->render();
        return sendResponse(['view' => $view]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
