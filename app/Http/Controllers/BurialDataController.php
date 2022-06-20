<?php

namespace App\Http\Controllers;

use App\Models\BurialData;
use App\Models\BurialType;
use App\Models\Regency;
use App\Models\Tpu;
use App\Models\TpuGrave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BurialDataController extends Controller
{
    private $staticKeyPhoto;
    private $staticErrorFile;
    private $automaticNumber;

    public function __construct()
    {
        $this->staticKeyPhoto = [
            'grave_photo' => 'grave',
            'application_letter_photo' => 'application-letter',
            'ktp_corpse_photo' => 'ktp-corpse',
            'cover_letter_photo' => 'cover-letter',
            'reporter_ktp_photo' => 'reporter-ktp',
            'reporter_kk_photo' => 'reporter-kk',
            'letter_of_hospital_statement_photo' => 'letter-statement'
        ];

        $this->staticErrorFile = [
            'grave_photo' => 'Upload Foto Makam Gagal Disimpan',
            'application_letter_photo' => 'Upload Surat Permohonan Gagal Disimpan',
            'ktp_corpse' => 'Upload Ktp Jenazah Gagal Disimpan',
            'cover_letter' => 'Upload Surat Pengantar Gagal Disimpan',
            'reporter_ktp' => 'Upload KTP Pelapor Gagal Disimpan',
            'reporter_kk' => 'Upload KK Pelapor Gagal Disimpan',
            'letter_of_hospital_statement' => 'Upload Keterangan Rumah Sakit Gagal Disimpan',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Data Pemakaman';
        $tpus = Tpu::all();
        return view('burial-data.index', compact('pageTitle', 'tpus'));
    }

    /**
     * Showing data for DataTables
     *
     * @return \Illuminate\Http\Response
     */
    public function json($filter) {
        $tpuId = Auth::user()->tpu_id;
        $role = Auth::user()->role;
        $where = "id > 0";
        if ($filter != 00) {
            $where = "tpu_id = $filter";
        } else if ($filter == 0) {
            $where = "tpu_id > 0";
        }
        if ($role == 'tpu') {
            $data = BurialData::where('tpu_id', $tpuId)->whereRaw($where)->get();
        } else {
            $data = BurialData::whereRaw($where)->get();
        }
        return DataTables::of($data)
            ->editColumn('name', function($data) {
                return ucwords($data->name);
            })
            ->editColumn('address', function($data) {
                $city = Regency::where('id', $data->village_id)->first()->name;
                $address = $data->address . ' , RT ' . $data->rt . ' RW ' . $data->rw . ' , ' . $city;
                return $address;
            })
            ->editColumn('date_of_death', function($data) {
                $date = $data->date_of_death;
                if ($date == NULL) {
                    $format = '-';
                } else {
                    $format = date('d F Y', strtotime($data->date_of_death)) ?? '-';
                }
                return $format;
            })
            ->editColumn('tpu_id', function($data) {
                $tpu = Tpu::find($data->tpu_id);
                return ucwords($tpu->name);
            })
            ->editColumn('buried_date', function($data) {
                $date = $data->buried_date;
                if ($date == NULL) {
                    $format = '-';
                } else {
                    $format = date('d F Y', strtotime($data->buried_date)) ?? '-';
                }
                return $format;
            })
            ->editColumn('reporters_name', function($data) {
                return ucwords($data->reporters_name) ?? '-';
            })
            ->editColumn('grave_block', function($data) {
                $number = $data->grave_number;
                $numberText = $number == NULL ? '( Nomor makam belum dipilih )' : $number;
                $block = 'Blok ' . $data->grave_block . ' - ' . $numberText;
                return $data->grave_block == NULL ? '-' : $block;
            })
            ->addColumn('action', function($data) {
                return '<a class="text-info me-3" style="cursor:pointer;" href="'. route('burial-data.edit', $data->id) .'"><i class="fa fa-edit"></i></a>
                    <span class="text-info me-3" style="cursor:pointer;" onclick="deleteBurialData('. $data->id .')"><i class="fa fa-trash"></i></span>';
            })
            ->rawColumns(['name', 'tpu_id', 'action', 'address', 'date_of_death', 'buried_date', 'reporters_name', 'grave_block'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id = Auth::id();
        $tpuId = Auth::user()->tpu_id;
        $pageTitle = 'Tambah Data Pemakaman';
        $city = Regency::all();
        $burialTypes = BurialType::all();
        $graveBlocks = TpuGrave::where('tpu_id', $tpuId)
            ->where('quota', '>', 0)
            ->get();
        $tpus = Tpu::all();
        $burialData = BurialData::where('tpu_id', $tpuId)->count() + 1;
        if (Auth::user()->role != 'tpu') {
            $burialData = BurialData::count();
        }
        $number = "TPU0$tpuId-00" . $burialData + 1;
        return view('burial-data.create', compact('pageTitle', 'city', 'burialTypes', 'graveBlocks', 'number', 'tpus'));
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
        $burialDataId = $request->burial_data_id;
        $nik = $request->nik;
        $birthDate = $request->date_of_birth;
        $regencyBirthDate = $request->regency_of_birth;
        $address = $request->address;
        $villageId = $request->village_id;
        $rt = $request->rt;
        $rw = $request->rw;
        $reportersName = $request->reporter_name;
        $reportersNik = $request->reporter_nik;
        $placeOfDeath = $request->place_of_death;
        $dateOfDeath = $request->date_of_death;
        $regencyOfDeath = $request->regency_of_death;
        $buriedDate = $request->burial_date;
        $burialTypeId = $request->burial_type_id;
        $graveBlock = $request->grave_block;
        $graveNumber = $request->grave_number;
        $notes = $request->notes;
        $latLong = $request->lat_long;
        $guardianName = $request->guardian_name;
        $guardianPhone = $request->guardian_phone;
        $tpuId = $request->tpu_id ?? Auth::user()->tpu_id;
        // Validation
        $this->validation($request);

        DB::beginTransaction();
        try {
            $splitCoor = explode(',', $latLong);
            $data = [
                'name' => $name,
                'burial_data_id' => $burialDataId,
                'nik' => $nik,
                'birth_date' => $birthDate,
                'regency_of_birth' => $regencyBirthDate,
                'address' => $address,
                'village_id' => $villageId,
                'rt' => $rt,
                'rw' => $rw,
                'reporters_name' => $reportersName,
                'reporters_nik' => $reportersNik,
                'place_of_death' => $placeOfDeath,
                'date_of_death' => $dateOfDeath,
                'regency_of_death' => $regencyOfDeath,
                'buried_date' => $buriedDate,
                'burial_type_id' => $burialTypeId,
                'grave_block' => $graveBlock,
                'grave_number' => $graveNumber,
                'notes' => $notes,
                'latitude' => $splitCoor[0] ?? "",
                'longitude' => $splitCoor[1] ?? "",
                'tpu_id' => $tpuId
            ];

            $burial = BurialData::insertGetId($data);

            if ($burial) {
                // upload photo
                if ($request->has('photo')) {
                    $uploaded = $this->upload($request->photo, $this->staticKeyPhoto);
                    for ($a = 0; $a < count($uploaded); $a++) {
                        $dataFile[$uploaded[$a]['key']] = $uploaded[$a]['file'];
                    }
                    BurialData::where('id', $burial)->update($dataFile);
                }
            }

            // update quota
            $this->updateQuota($graveBlock, 'decrement');

            DB::commit();
            return sendResponse(
                []
            );
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }

    public function updateQuota($idBlock, $type) {
        try {
            $grave = TpuGrave::find($idBlock);
            if ($type == 'decrement') {
                $grave->quota = $grave->quota - 1;
            } else {
                $grave->quota = $grave->quota + 1;
            }
            $grave->save();
            return true;
        } catch (\Throwable $th) {
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }

    public function upload($photos, $photoKey) {
        try {
            $fileName = [];
            for ($a = 0; $a < count($photos); $a++) {
                foreach ($photoKey as $val => $key) {
                    if (isset($photos[$a][$val])) {
                        $fileName = $photos[$a][$val]->getClientOriginalName();
                        if ($fileName != 'blob') {
                            $fileName = 'TPU' . Auth::user()->tpu_id . '-' . date('ymdHis') . '-' . $fileName;
                            $pathPhoto = Storage::putFileAs("public/buried-data/$key", $photos[$a][$val], $fileName);
                            if ($pathPhoto) {
                                $linkToPhoto[$a] = [
                                    'file' => 'storage/buried-data/' . $key . '/' . $fileName,
                                    'key' => $val
                                ];
                            }
                        }
                    }
                }
            }
            return $linkToPhoto ?? [];
        } catch (\Throwable $th) {
            // Storage::delete('public/buried-data/' . $type . '/' . $photo->getClientOriginalName());
            return [
                'error' => $th->getMessage(),
                'status' => 500
            ];
        }
    }

    /**
     * Function to validate all request
     * 
     * @return JsonResponse
     */
    public function validation(Request $request) {
        $rules = [
            'name' => 'required',
            'nik' => 'required',
            'address' => 'required',
            'village_id' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'reporter_name' => 'required',
            'reporter_phone' => 'required'
        ];
        $messageRule = [
            'name.required' => 'Nama Jenazah Harus Diisi',
            'nik.required' => 'NIK Jenazah Harus Diisi',
            'address.required' => 'Alamat Jenazah Harus Diisi',
            'village_id.required' => 'Kota Alamat Jenazah Harus Diisi',
            'rt.required' => 'RT Jenazah Harus Diisi',
            'rw.required' => 'RW Jenazah Harus Diisi',
            'reporter_name.required' => 'Nama Pelapor Jenazah Harus Diisi',
            'reporter_phone.required' => 'No. Telfon Pelapor Jenazah Harus Diisi',
        ];
        $validator = Validator::make(
            $request->all(),
            $rules,
            $messageRule
        );
        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return sendResponse(
                ['error' => $error],
                'VALIDATION_FAILED',
                500
            );
        }

        return;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BurialData  $burialData
     * @return \Illuminate\Http\Response
     */
    public function show(BurialData $burialData)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BurialData  $burialData
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $burialData = BurialData::find($id);
        $id = Auth::id();
        $tpuId = Auth::user()->tpu_id;
        $pageTitle = 'Edit Data Pemakaman';
        $city = Regency::all();
        $burialTypes = BurialType::all();
        $graveBlocks = TpuGrave::where('tpu_id', $tpuId)->get();
        $tpus = Tpu::all();
        return view('burial-data.edit', compact('burialData', 'pageTitle', 'city', 'burialTypes', 'graveBlocks', 'tpus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BurialData  $burialData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $name = $request->input('name');
        $burialDataId = $request->burial_data_id;
        $nik = $request->nik;
        $birthDate = $request->date_of_birth;
        $regencyBirthDate = $request->regency_of_birth;
        $address = $request->address;
        $villageId = $request->village_id;
        $rt = $request->rt;
        $rw = $request->rw;
        $reportersName = $request->reporter_name;
        $reportersNik = $request->reporter_nik;
        $placeOfDeath = $request->place_of_death;
        $dateOfDeath = $request->date_of_death;
        $regencyOfDeath = $request->regency_of_death;
        $buriedDate = $request->burial_date;
        $burialTypeId = $request->burial_type_id;
        $graveBlock = $request->grave_block;
        $graveNumber = $request->grave_number;
        $notes = $request->notes;
        $latLong = $request->lat_long;
        $guardianName = $request->guardian_name;
        $guardianPhone = $request->guardian_phone;
        $tpuId = $request->tpu_id ?? Auth::user()->tpu_id;
        $currentData = BurialData::find($id);
        // Validation
        $this->validation($request);

        DB::beginTransaction();
        try {
            $splitCoor = explode(',', $latLong);
            $data = [
                'name' => $name,
                'burial_data_id' => $burialDataId,
                'nik' => $nik,
                'birth_date' => $birthDate,
                'regency_of_birth' => $regencyBirthDate,
                'address' => $address,
                'village_id' => $villageId,
                'rt' => $rt,
                'rw' => $rw,
                'reporters_name' => $reportersName,
                'reporters_nik' => $reportersNik,
                'place_of_death' => $placeOfDeath,
                'date_of_death' => $dateOfDeath,
                'regency_of_death' => $regencyOfDeath,
                'buried_date' => $buriedDate,
                'burial_type_id' => $burialTypeId,
                'grave_block' => $graveBlock,
                'grave_number' => $graveNumber,
                'notes' => $notes,
                'latitude' => $splitCoor[0] ?? "",
                'longitude' => $splitCoor[1] ?? "",
                'tpu_id' => $tpuId
            ];
            // update quota
            if ($currentData->grave_block == NULL && $graveBlock != NULL) {
                $this->updateQuota($graveBlock, 'decrement');
            } else if ($currentData->grave_block != NULL && $graveBlock == NULL) {
                $this->updateQuota($currentData->grave_block, 'increment');
            } else if ($currentData->grave_block != NULL && $graveBlock != NULL && ($currentData->grave_block != $graveBlock)) {
                $this->updateQuota($currentData->grave_block, 'increment');
                $this->updateQuota($graveBlock, 'decrement');
            }

            $burial = BurialData::where('id', $id)->update($data);

            if ($burial) {
                // upload photo
                if ($request->has('photo')) {
                    $uploaded = $this->upload($request->photo, $this->staticKeyPhoto);
                    $uploaded = array_values($uploaded);
                    if (count($uploaded) > 0) {
                        for ($a = 0; $a < count($uploaded); $a++) {
                            $dataFile[$uploaded[$a]['key']] = $uploaded[$a]['file'];
                        }
                        // delete current file
                        Storage::delete([
                            'public/' . $currentData->grave_photo,
                            'public/' . $currentData->application_letter_photo,
                            'public/' . $currentData->ktp_corpse_photo,
                            'public/' . $currentData->cover_letter_photo,
                            'public/' . $currentData->reporter_ktp_photo,
                            'public/' . $currentData->reporter_kk_photo,
                            'public/' . $currentData->letter_of_hospital_statement_photo,
                        ]);
        
                        BurialData::where('id', $id)->update($dataFile);
                    }
                }
            }

            DB::commit();
            return sendResponse(
                []
            );
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
     * @param  \App\Models\BurialData  $burialData
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $data = BurialData::find($id);
            // update quota
            $this->updateQuota($data->grave_block, 'increment');

            $data->delete();

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
}
