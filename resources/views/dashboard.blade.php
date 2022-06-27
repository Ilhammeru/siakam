@extends('layouts.master')

@section('content')

<!--begin::Card-->
<div class="card" style="height: 100%;">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <div class="chart-container" style="position: relative; height:10vh; width:20vw">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Card-->

@endsection

{{-- begin::scripts --}}
@push('scripts')
    <script src="{{ asset('plugins/custom/chartjs/chart.js') }}"></script>
    <script>
        const data = {
            labels: [
                'Red',
                'Blue',
                'Yellow'
            ],
            datasets: [{
                label: 'My First Dataset',
                data: [300, 50, 100],
                backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
                ],
                hoverOffset: 4
            }]
        };
        const ctx = document.getElementById('myChart');
        const myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May'],
                datasets: [{
                data: [50, 60, 70, 180, 190]
                }]
            },
            options: {
                plugins: {
                datalabels: {
                    display: true,
                    align: 'bottom',
                    backgroundColor: '#ccc',
                    borderRadius: 3,
                    font: {
                    size: 18,
                    }
                },
                }
            }
        });
    </script>
@endpush
{{-- end::scripts --}}
