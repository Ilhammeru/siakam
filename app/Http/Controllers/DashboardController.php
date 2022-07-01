<?php

namespace App\Http\Controllers;

use App\Models\BurialData;
use App\Models\Tpu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() {
        $pageTitle = "Dashboard";
        $tpus = Tpu::all();
        return view('dashboard', compact('pageTitle', 'tpus'));
    }
    
    public function json($tpuId) {
        if (Auth::user()->role == 'tpu') {
            $tpuId = Auth::user()->tpu_id;
        }
        $tpu = Tpu::with(['graves'])->find($tpuId);
        $tpus = Tpu::all();
        $graves = $tpu->graves;
        
        $dataPerMonth = [];
        for ($a = 1; $a <= 12; $a++) {
            $burialData = BurialData::where('buried_month', $a)->where('tpu_id', $tpuId)->whereYear('buried_date', date('Y'))->count();
            $dataPerMonth[] = [
                'label' => formatIndonesiaMonth($a),
                'legendText' => formatIndonesiaMonth($a),
                'y' => $burialData,
                'x' => $a
            ];
        }
        
        $format = [];
        $left = [];
        foreach($graves as $gr) {
            $burialData = BurialData::where('grave_block', $gr->id)->count();
            $format[] = [
                'y' => $burialData,
                'legendText' => ucwords($gr->grave_block)
            ];
            $left[] = [
                'y' => $gr->quota - $burialData,
                'legendText' => ucwords($gr->grave_block)
            ];
        }

        $view = view('_chart', compact('format', 'left', 'dataPerMonth'))->render();
        return sendResponse([
            'view' => $view,
            'format' => $format,
            'left' => $left,
            'dataPerMonth' => $dataPerMonth
        ]);
    }
}
