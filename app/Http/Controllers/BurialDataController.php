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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PDF;
use Optimizer;

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
            $data = BurialData::with(['graveBlock', 'addressPlace', 'tpu'])->where('tpu_id', $tpuId)->whereRaw($where)->get();
        } else {
            $data = BurialData::with(['graveBlock', 'addressPlace', 'tpu'])->whereRaw($where)->get();
        }
        return DataTables::of($data)
            ->editColumn('name', function($data) {
                return '<a href="'. route('burial-data.show', $data->id) .'">'. ucwords(strtolower($data->name)) .'</a>';
            })
            ->editColumn('address', function($data) {
                $city = $data->addressPlace == NULL ? '-' : $data->addressPlace->name;
                $address = $data->address . ' , RT ' . $data->rt . ' RW ' . $data->rw . ' , ' . $city;
                return $address;
            })
            ->editColumn('date_of_death', function($data) {
                $date = $data->date_of_death;
                if ($date == NULL) {
                    $format = '-';
                } else {
                    $format = formatIndonesiaDate(date('Y-m-d', strtotime($data->date_of_death)));
                }
                return $format;
            })
            ->editColumn('tpu_id', function($data) {
                return ucwords($data->tpu->name);
            })
            ->editColumn('buried_date', function($data) {
                $date = $data->buried_date;
                if ($date == NULL) {
                    $format = '-';
                } else {
                    $format = formatIndonesiaDate(date('Y-m-d', strtotime($data->buried_date)));
                }
                return $format;
            })
            ->editColumn('reporters_name', function($data) {
                return ucwords($data->reporters_name) ?? '-';
            })
            ->editColumn('grave_block', function($data) {
                $block = $data->graveBlock == NULL ? '-' : 'Blok ' . ucwords(strtolower($data->graveBlock->grave_block));
                return $block;
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
            $number = "";
        }
        if (Auth::user()->role == 'tpu') {
            $numberData = str_pad($burialData, 3, '0', STR_PAD_LEFT);    
            $tpu = Tpu::find($tpuId);
            $name = implode('', explode(' ', $tpu->name));
            $number = $name . '/' . $numberData . '/' . romawiMonth(date('Y-m-d')) . '/' . date('Y');
        }
        $religion = [
            ['name' => 'ISLAM'],
            ['name' => 'KRISTEN'],
            ['name' => 'PROTESTAN'],
            ['name' => 'KATOLIK'],
            ['name' => 'BUDHA'],
            ['name' => 'KONGHUCU'],
            ['name' => 'HINDU'],
            ['name' => 'PENGHAYAT KEPERCAYAAN'],
        ];
        return view('burial-data.create', compact('pageTitle', 'religion', 'city', 'burialTypes', 'graveBlocks', 'number', 'tpus'));
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
        $religion = $request->corpse_religion;
        $gender = $request->corpse_gender;
        $birthDate = $request->date_of_birth;
        $regencyBirthDate = $request->regency_of_birth;
        $address = $request->address;
        $villageId = $request->village_id;
        $rt = $request->rt;
        $rw = $request->rw;
        $reportersName = strtolower($request->reporter_name);
        $reportersNik = $request->reporter_nik;
        $reporterRelation = $request->reporter_relationship;
        $reporterPhone = $request->reporter_phone;
        $reporterAddress = $request->reporter_address;
        $dateOfDeath = $request->date_of_death;
        $regencyOfDeath = $request->regency_of_death;
        $buriedDate = $request->burial_date;
        $buriedMonth = date('m', strtotime($buriedDate));
        $burialTypeId = $request->burial_type_id;
        $graveBlock = $request->grave_block;
        $graveNumber = $request->grave_number;
        $notes = $request->notes;
        $latLong = $request->lat_long;
        $tpuId = $request->tpu_id ?? Auth::user()->tpu_id;
        // Validation
        $this->validation($request);

        if (Auth::user()->role == 'admin' || Auth::user()->role == 'superadmin') {
            if ($burialDataId == NULL) {
                return sendResponse(
                    ['error' => ['No Pemakaman Harus Diisi, Silahkan Pilih TPU']],
                    'VALIDATION_FAILED',
                    500
                );
            }
        }

        DB::beginTransaction();
        try {
            $splitCoor = explode(',', $latLong);
            $data = [
                'name' => strtolower($name),
                'burial_data_id' => $burialDataId,
                'nik' => $nik,
                'gender' => $gender,
                'religion' => $religion,
                'birth_date' => $birthDate,
                'regency_of_birth' => $regencyBirthDate,
                'address' => strtolower($address),
                'village_id' => $villageId,
                'rt' => $rt,
                'rw' => $rw,
                'reporters_name' => $reportersName,
                'reporters_nik' => $reportersNik,
                'reporters_phone' => $reporterPhone,
                'reporters_address' => strtolower($reporterAddress),
                'reporters_relationship' => strtolower($reporterRelation),
                'date_of_death' => $dateOfDeath,
                'regency_of_death' => $regencyOfDeath,
                'buried_date' => $buriedDate,
                'buried_month' => $buriedMonth,
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
                    $uploaded = $this->upload(array_values($request->photo), $this->staticKeyPhoto);
                    for ($a = 0; $a < count($uploaded); $a++) {
                        $dataFile[$uploaded[$a]['key']] = $uploaded[$a]['file'];
                    }
                    BurialData::where('id', $burial)->update($dataFile);
                }
            }

            // update quota
            // $this->updateQuota($graveBlock, 'decrement');

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

    public function funeralLetterStatus($data) {
        if (
            $data->grave_photo != NULL &&
            $data->application_letter_photo != NULL &&
            $data->ktp_corpse_photo != NULL &&
            $data->cover_letter_photo != NULL &&
            $data->reporter_ktp_photo != NULL &&
            $data->reporter_kk_photo != NULL &&
            $data->letter_of_hospital_statement_photo != NULL &&
            $data->reporters_name != NULL &&
            $data->reporters_nik != NULL
        ) {
            return true;
        } else {
            return false;
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
                            $ext =$photos[$a][$val]->getClientOriginalExtension();
                            $file = $photos[$a][$val];
                            $fileName = 'TPU' . Auth::user()->tpu_id . '-' . date('ymdHis') . '-' . $fileName;
                            // $pathPhoto = Storage::putFileAs("public/buried-data/$key", $photos[$a][$val], $fileName);
                            if ($ext == 'jpg' || $ext == 'jpeg') {
                                $file->storeAs("buried-data/$key", $fileName, 'public');
                                $compress = imagejpeg(imagecreatefromjpeg($file), 'buried-data/' . $key . '/' . $fileName, 50);
                            } else if ($ext == 'png') {
                                $file->storeAs("buried-data/$key", $fileName, 'public');
                                $compress = imagejpeg(imagecreatefrompng($file), 'buried-data/' . $key . '/' . $fileName, 50);
                            } else if ($ext == 'pdf') {
                                $compress =$file->storeAs("buried-data/$key", $fileName, 'public');
                            } else {
                                $compress =$file->storeAs("buried-data/$key", $fileName, 'public');
                            }
                            if ($compress) {
                                $linkToPhoto[$a] = [
                                    'file' => 'buried-data/' . $key . '/' . $fileName,
                                    'ext' => $ext,
                                    'key' => $val,
                                    'compress' => $compress
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
            'reporter_phone' => 'required',
            'grave_block' => 'required_with:tpu_id'
        ];
        if (Auth::user()->role == 'admin' || Auth::user()->role == 'superadmin') {
            $rules['burial_data_id'] = 'required';
        }
        return $rules;
        $messageRule = [
            'name.required' => 'Nama Jenazah Harus Diisi',
            'nik.required' => 'NIK Jenazah Harus Diisi',
            'address.required' => 'Alamat Jenazah Harus Diisi',
            'village_id.required' => 'Kota Alamat Jenazah Harus Diisi',
            'rt.required' => 'RT Jenazah Harus Diisi',
            'rw.required' => 'RW Jenazah Harus Diisi',
            'reporter_name.required' => 'Nama Pelapor Jenazah Harus Diisi',
            'reporter_phone.required' => 'No. Telfon Pelapor Jenazah Harus Diisi',
            'grave_block.required_with' => 'Blok Makam Harus Diisi Bila TPU Sudah Dipilih',
            'burial_data_id.required' => 'No Pemakaman Harus Diisi, Silahkan Pilih TPU Terlebih Dahulu'
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
    // application_letter_photo, ktp_corpse_photo,
    // cover_letter_photo, reporter_ktp_photo, reporter_kk_photo,
    // letter_of_hospital_statement_photo
        return;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BurialData  $burialData
     * @return \Illuminate\Http\Response
     */
    public function show($burial_datum)
    {
        $data = BurialData::with(['graveBlock', 'tpu'])->find($burial_datum);
        $city = Regency::where('id', $data->village_id)->first()->name;
        $address = ucwords($data->address) . ' , RT ' . $data->rt . ' RW ' . $data->rw . ' , ' . ucwords(strtolower($city));
        $regencyOfBirth = Regency::where('id', $data->regency_of_birth)->first()->name;
        $pageTitle = 'Detail Data Pemakaman #' . $data->burial_data_id;
        $tpuBlock = '-';
        if ($data->grave_block != NULL) {
            $tpuBlock = $data->tpu->name . ' / ' . $data->graveBlock->grave_block . ' - ' . $data->grave_number;
        }
        $dateOfDeath = $data->date_of_death != NULL ? formatIndonesiaDate(date('Y-m-d', strtotime($data->date_of_death))) : '-';
        $buriedDate = $data->buried_date != NULL ? formatIndonesiaDate(date('Y-m-d', strtotime($data->buried_date))) : '-';
        $latLong = $data->longitude != NULL ? $data->latitude . ',' . $data->longitude : '-';
        $funeralStatus = $this->funeralLetterStatus($data);
        return view('burial-data.detail', compact(
            'data', 'pageTitle', 'address', 'regencyOfBirth', 'tpuBlock',
            'buriedDate', 'dateOfDeath', 'latLong', 'funeralStatus'
        ));
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
        $number = "";
        if (Auth::user()->role == 'tpu') {
            $numberData = str_pad($burialData, 3, '0', STR_PAD_LEFT); 
            $tpu = Tpu::find($tpuId);
            $name = implode('', explode(' ', $tpu->name));
            $number = $name . '/' . $numberData . '/' . romawiMonth(date('Y-m-d')) . '/' . date('Y');
        }
        $latLong = "";
        if ($burialData->latitude != "") {
            $latLong = $burialData->latitude . ',' . $burialData->longitude;
        }
        $religion = [
            ['name' => 'ISLAM'],
            ['name' => 'KRISTEN'],
            ['name' => 'KATOLIK'],
            ['name' => 'BUDHA'],
            ['name' => 'KONGHUCU'],
            ['name' => 'HINDU'],
        ];
        return view('burial-data.edit', compact('burialData', 'latLong', 'number', 'religion', 'pageTitle', 'city', 'burialTypes', 'graveBlocks', 'tpus'));
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
        $religion = $request->corpse_religion;
        $gender = $request->corpse_gender;
        $birthDate = $request->date_of_birth;
        $regencyBirthDate = $request->regency_of_birth;
        $address = $request->address;
        $villageId = $request->village_id;
        $rt = $request->rt;
        $rw = $request->rw;
        $reportersName = strtolower($request->reporter_name);
        $reportersNik = $request->reporter_nik;
        $reporterRelation = $request->reporter_relationship;
        $reporterPhone = $request->reporter_phone;
        $reporterAddress = $request->reporter_address;
        $dateOfDeath = $request->date_of_death;
        $regencyOfDeath = $request->regency_of_death;
        $buriedDate = $request->burial_date;
        $buriedMonth = date('m', strtotime($buriedDate));
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
                'name' => strtolower($name),
                'burial_data_id' => $burialDataId,
                'nik' => $nik,
                'gender' => $gender,
                'religion' => $religion,
                'birth_date' => $birthDate,
                'regency_of_birth' => $regencyBirthDate,
                'address' => strtolower($address),
                'village_id' => $villageId,
                'rt' => $rt,
                'rw' => $rw,
                'reporters_name' => $reportersName,
                'reporters_nik' => $reportersNik,
                'reporters_phone' => $reporterPhone,
                'reporters_address' => strtolower($reporterAddress),
                'reporters_relationship' => strtolower($reporterRelation),
                'date_of_death' => $dateOfDeath,
                'regency_of_death' => $regencyOfDeath,
                'buried_date' => $buriedDate,
                'buried_month' => $buriedMonth,
                'burial_type_id' => $burialTypeId,
                'grave_block' => $graveBlock,
                'grave_number' => $graveNumber,
                'notes' => $notes,
                'latitude' => $splitCoor[0] ?? "",
                'longitude' => $splitCoor[1] ?? "",
                'tpu_id' => $tpuId
            ];
            // update quota
            // if ($currentData->grave_block == NULL && $graveBlock != NULL) {
            //     $this->updateQuota($graveBlock, 'decrement');
            // } else if ($currentData->grave_block != NULL && $graveBlock == NULL) {
            //     $this->updateQuota($currentData->grave_block, 'increment');
            // } else if ($currentData->grave_block != NULL && $graveBlock != NULL && ($currentData->grave_block != $graveBlock)) {
            //     $this->updateQuota($currentData->grave_block, 'increment');
            //     $this->updateQuota($graveBlock, 'decrement');
            // }

            $burial = BurialData::where('id', $id)->update($data);

            if ($burial) {
                // upload photo
                if ($request->has('photo')) {
                    $uploaded = $this->upload(array_values($request->photo), $this->staticKeyPhoto);
                    $uploaded = array_values($uploaded);
                    // compress image
                    for ($x = 0; $x < count($uploaded); $x++) {
                        imagejpeg(imagecreatefromjpeg($uploaded[$x]['file']), $uploaded[$x]['file'], 50);
                    }
                    $uploaded = array_values($uploaded);
                    if (count($uploaded) > 0) {
                        // File::delete([
                        //     $currentData->grave_photo,
                        //     $currentData->application_letter_photo,
                        //     $currentData->ktp_corpse_photo,
                        //     $currentData->cover_letter_photo,
                        //     $currentData->reporter_ktp_photo,
                        //     $currentData->reporter_kk_photo,
                        //     $currentData->letter_of_hospital_statement_photo,
                        // ]);

                        for ($a = 0; $a < count($uploaded); $a++) {
                            $dataFile[$uploaded[$a]['key']] = $uploaded[$a]['file'];
                        }
                        // delete current file
                        // Storage::delete([
                        //     'public/' . $currentData->grave_photo,
                        //     'public/' . $currentData->application_letter_photo,
                        //     'public/' . $currentData->ktp_corpse_photo,
                        //     'public/' . $currentData->cover_letter_photo,
                        //     'public/' . $currentData->reporter_ktp_photo,
                        //     'public/' . $currentData->reporter_kk_photo,
                        //     'public/' . $currentData->letter_of_hospital_statement_photo,
                        // ]);
                        
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

    public function deletePhoto($id, $type) {
        try {
            // buried-data/ktp-corpse/TPU1-220620130409-150-3.jpg
            $data = BurialData::find($id);
            $currentFile = $data->$type;
            $data->$type = NULL;
            $data->save();
            if ($data) {
                File::delete($currentFile);
            }
            return sendResponse([
                'delete' => $data
            ]);
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

    public function downloadFuneralLetter($id) {
        $data = BurialData::with(['tpu', 'birthPlace', 'graveBlock'])->find($id);
        $tpuNoSpace = implode('', explode(' ', $data->tpu->name));
        $burialDataId = $data->burial_data_id;

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('Format_Surat_Pemakaman.docx');
        $templateProcessor->setValue('format_number', $burialDataId);
        $templateProcessor->setValue('tpu_name', $data->tpu->name);
        $templateProcessor->setValue('tpu_address', $data->tpu->address);
        $templateProcessor->setValue('reporters_name', ucwords($data->reporters_name));
        $templateProcessor->setValue('reporters_nik', $data->reporters_nik);
        $templateProcessor->setValue('reporters_relationship', ucwords($data->reporters_relationship));
        $templateProcessor->setValue('reporters_address', ucwords($data->reporters_address));
        $templateProcessor->setValue('reporters_phone', $data->reporters_phone);
        $templateProcessor->setValue('name', ucwords($data->name));
        $templateProcessor->setValue('nik', $data->nik);
        $templateProcessor->setValue('birth', ucwords(strtolower($data->birthPlace->name)) . '/' . formatIndonesiaDate(date('Y-m-d', strtotime($data->birth_date))));
        $templateProcessor->setValue('gender', $data->gender == 'L' ? 'Laki-laki' : 'Perempuan');
        $templateProcessor->setValue('religion', ucwords(strtolower($data->religion)));
        $templateProcessor->setValue('address', ucwords($data->address));
        $templateProcessor->setValue('day_of_death', formatIndonesiaDay($data->date_of_death));
        $templateProcessor->setValue('date_of_death', formatIndonesiaDate(date('Y-m-d', strtotime($data->date_of_death))));
        $templateProcessor->setValue('buried_date', formatIndonesiaDay($data->buried_date) . '/' . formatIndonesiaDate(date('Y-m-d', strtotime($data->buried_date))));
        $templateProcessor->setValue('tpu', $data->tpu->name);
        $templateProcessor->setValue('grave_block', ucwords($data->graveBlock->grave_block));
        $templateProcessor->setValue('grave_number', $data->grave_number);
        $templateProcessor->setValue('timestamp', formatIndonesiaDate(date('Y-m-d')));
        $temp_file = tempnam(sys_get_temp_dir(), 'PHPWord');
        $templateProcessor->saveAs($temp_file);

        header("Content-Disposition: attachment; filename=SuratKeteranganPemakaman.docx");
        readfile($temp_file);
        unlink($temp_file);
    }

    public function downloadPdf(Request $request) {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        if ($request->tpu_id == NULl || $request->tpu_id == "") {
            $tpuId = Auth::user()->tpu_id;
        } else {
            $tpuId = $request->tpu_id;
        }

        $tpu = Tpu::find($tpuId);

        $data = BurialData::with(['birthPlace', 'graveBlock', 'tpu'])
            ->whereBetween('buried_date', [$startDate, $endDate])
            ->where('tpu_id', $tpuId)
            ->get();
        $pdf = PDF::loadView('burial-data.pdf', compact('data', 'tpu', 'startDate', 'endDate'))->setPaper('a4', 'landscape');
        return $pdf->download('LaporanPemakaman_' . implode('', explode(' ', $tpu->name)) . '.pdf');
    }

    public function viewPdf() {
        $data = BurialData::with(['birthPlace', 'graveBlock', 'tpu'])->get();
        $tpu = Tpu::find(1);
        return view('burial-data.pdf', compact('data', 'tpu'));
    }
}
