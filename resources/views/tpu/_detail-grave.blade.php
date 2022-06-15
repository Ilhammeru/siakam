@if (count($graves) > 0)
    @foreach ($graves as $grave)
        <div class="col-md-3 col-xl-3 mb-4">
            <div class="grave d-flex align-items-center justify-content-center" style="background: yellow; height: 60px; width: auto;">
                <div>
                    <p class="text-center mb-0">Blok</p>
                    <h3 class="text-center mb-0">{{ $grave->grave_block }}</h3>
                </div>
            </div>
        </div>
    @endforeach
@else
<div class="col">
    <h3 class="text-center">Tidak ada Blok Makam yang Tersedia</h3>
</div>
@endif