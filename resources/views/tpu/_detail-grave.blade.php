@if (count($graves) > 0)
    <div class="col mb-4">
        <table class="table table-striped">
            <thead class="table-primary">
                <tr>
                    <th class="text-center">No.</th>
                    <th class="text-center" style="width: 150px;">Blok Makam</th>
                    <th class="text-center" style="width: 150px;">Kuota</th>
                    <th class="text-center" style="width: 150px;">Sisa Kuota</th>
                    @if (Auth::user()->role == 'tpu' || Auth::user()->role == 'admin' || Auth::user()->role == 'superadmin')
                    <th class="text-center"></th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @php
                    $a = 1;
                    $x = 0;
                @endphp
                @foreach ($graves as $grave)
                    <tr id="editableGrave{{ $grave->id }}">
                        <td class="text-center">{{ $a }}</td>
                        <td class="text-center">
                            <span id="viewGraveBlock{{ $grave->id }}">
                                {{ $grave->grave_block }}
                            </span>
                            <input type="text" id="editGraveBlock{{ $grave->id }}" class="form-control" hidden value="{{ $grave->grave_block }}">
                        </td>
                        <td class="text-center">
                            <span id="viewGraveQuota{{ $grave->id }}">
                                {{ $grave->quota }}
                            </span>
                            <input type="text" id="editGraveQuota{{ $grave->id }}" hidden class="form-control" value="{{ $grave->quota }}">
                        </td>
                        <td class="text-center">
                            <span id="viewGraveQuotaa{{ $grave->id }}">
                                {{ $grave->quota - $burialData[$x] }}
                            </span>
                            <input type="text" id="editGraveQuota{{ $grave->id }}" hidden class="form-control" value="{{ $grave->quota }}">
                        </td>
                        @if (Auth::user()->role == 'tpu' || Auth::user()->role == 'admin' || Auth::user()->role == 'superadmin')
                        <td class="text-center">
                            <div id="actionEditGrave{{ $grave->id }}">
                                <span class="text-info me-4" onclick="editGrave({{ $grave->id }}, '{{ $grave->grave_block }}', {{ $grave->quota }})"><i class="fas fa-edit"></i></span>
                                <span class="text-info" onclick="deleteGrave({{ $grave->id }})"><i class="fas fa-trash"></i></span>
                            </div>
                            <div id="actionSaveGrave{{ $grave->id }}"></div>
                        </td>
                        @endif
                    </tr>

                    @php
                        $a++;
                        $x++;
                    @endphp
                @endforeach
            </tbody>
        </table>
    </div>
@else
<div class="col">
    <h3 class="text-center">Tidak ada Blok Makam yang Tersedia</h3>
</div>
@endif