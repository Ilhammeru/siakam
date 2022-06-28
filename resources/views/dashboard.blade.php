@extends('layouts.master')

@push('scripts')
    <script src="{{ asset('plugins/custom/canvasjs/canvasjs.min.js') }}"></script>
@endpush

@section('content')

@if (Auth::user()->role != 'tpu')
{{-- begin::card-filter --}}
<div class="card card-flush mb-5">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <form method="GET" id="filterDashboard">
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label for="filterTpu" class="col-form-label">Pilih Tpu</label>
                        </div>
                        <div class="col-md-4">
                            <select name="filter_tpu" id="filterTpu" class="form-select form-control" onchange="filter()">
                                @foreach ($tpus as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- end::card-filter --}}
@endif

<!--begin::Card-->
<div class="card" style="height: 100%;">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <div id="targetChart" class="text-center">
                    <div id="chartContainer1" style="width: 45%; height: 300px;display: inline-block; margin-bottom: 60px;"></div> 
                    <div id="chartContainer2" style="width: 45%; height: 300px;display: inline-block; margin-bottom: 60px;"></div><br/>
                    <div id="chartContainer3" style="width: 100%; height: 300px;display: inline-block;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Card-->

@endsection

{{-- begin::scripts --}}
@push('scripts')
    <script>
        $(document).ready(function() {
            let filter = $('#filterTpu').val();
            init(filter);
        });

        function init(filter) {
            $.ajax({
                type: "GET",
                url: "{{ url('/dashboard') }}" + "/" + filter,
                beforeSend: function() {
                    let loading = `<div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                                </div>`;
                    
                    $('#targetChart').html(loading);
                },
                success: function(res) {
                    let view = res.data.view;
                    $('#targetChart').html(view);
                    var chart = new CanvasJS.Chart("chartContainer1", {
                        title: {
                            text: "Jumlah Petak Makam",
                            padding: 10
                        },
                        animationEnabled: true,
                        legend: {
                            verticalAlign: "bottom",
                            horizontalAlign: "center",
                            fontSize: 10,
                            fontFamily: "Helvetica"
                        },
                        theme: "light1",
                        data: [
                            {
                                type: "pie",
                                indexLabelFontFamily: "Garamond",
                                indexLabelFontSize: 10,
                                indexLabelPlacement: "inside",
                                indexLabel: "{y}",
                                startAngle: -20,
                                showInLegend: true,
                                toolTipContent: "{legendText} {y}",
                                dataPoints: res.data.format
                            }
                        ]
                    });
                    chart.render();
                    var chart = new CanvasJS.Chart("chartContainer2", {
                        title: {
                            text: "Sisa Petak Makam",
                            padding: 10
                        },
                        animationEnabled: true,
                        legend: {
                            verticalAlign: "bottom",
                            horizontalAlign: "center",
                            fontSize: 10,
                            fontFamily: "Helvetica"
                        },
                        theme: "light1",
                        data: [
                            {
                                type: "pie",
                                indexLabelFontFamily: "Garamond",
                                indexLabelFontSize: 10,
                                indexLabelPlacement: "inside",
                                indexLabel: "{y}",
                                startAngle: -20,
                                showInLegend: true,
                                toolTipContent: "{legendText} {y}",
                                dataPoints: res.data.left
                            }
                        ]
                    });
                    chart.render();
                    var chart = new CanvasJS.Chart("chartContainer3", {
                        title: {
                            text: "Jumlah Pemakaman Per Bulan",
                            padding: 10
                        },
                        animationEnabled: true,
                        legend: {
                            verticalAlign: "bottom",
                            horizontalAlign: "center",
                            fontSize: 10,
                            fontFamily: "Helvetica"
                        },
                        theme: "light1",
                        data: [
                            {
                                type: "column",
                                indexLabelFontFamily: "Garamond",
                                indexLabelFontSize: 10,
                                indexLabelPlacement: "inside",
                                indexLabel: "{y}",
                                startAngle: -20,
                                showInLegend: true,
                                toolTipContent: "{legendText} {y} pemakaman",
                                dataPoints: res.data.dataPerMonth
                            }
                        ]
                    });
                    chart.render();
                },
                error: function(err) {
                    handleError(err);
                    $('#targetChart').html(`
                    <h3>Data Error</h3>`);
                }
            })
        }

        function filter() {
            let tpuId = $('#filterTpu').val();
            init(tpuId);
        }
    </script>
@endpush
{{-- end::scripts --}}
