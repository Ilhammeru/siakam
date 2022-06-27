<html>
  <head>
    <style>
    
    .table {
        width: 100%;
        border-spacing:0;
        border-collapse: collapse;
    }

    .table > thead > tr > th {
        text-transform: uppercase;
    }
    
    .table > thead > tr > th {
      padding: 2px;
      border: 1px solid #000;
      font-size: 10px;
    }
    
    .table > tbody > tr > td {
        padding: 7px;
        border: 1px solid #000;
        font-size: 10px;
    }
     
    p {
        margin-top: 0 !important;
        margin-bottom: 0 !important;
    }

    .thNo {
        width: 2%;
    }

    .thNik {
        width: 7%;
    }
    
    .thName {
        width: 10%;
    }

    .tdAddress {
        width: 18%;
    }

    .thAddress {
        width: 25%;
    }

    .thRt,
    .thRw {
        width: 2%;
    }

    .tdBirthPlace,
    .tdBirthDate {
        width: 12% !important;
    }

    .thBuriedDate {
        width: 7%%;
    }

    .thDateDeath {
        width: 7%;
    }

    .thRepName {
        width: 7%;
    }

    .thRepNik {
        width: 7%;
    }

    .thBlock {
        width: 7%;
    }

    .thNotes {
        width: 10%;
    }

    .thRepAll {
        width: 12%;
    }

    .text-center {
        text-align: center;
    }

    .main-section {
        padding: 40px;
    }

    .section-header {
        margin-bottom: 20px;
    }

    .header-1,
    .header-2 {
        text-transform: uppercase;
        margin-bottom: 5px !important;
        font-weight: bold;
    }

    .empty-data {
        font-weight: bold;
        text-transform: uppercase;
        font-size: 24px;
    }
    </style>
  </head>
  <body>
    <div class="main-section">
        <div class="section-header">
            <p class="header-1">NAMA TEMPAT PEMAKAMAN : {{ strtoupper($tpu->name) }}</p>
            <p class="header-2">ALAMAT : {{ strtoupper($tpu->address) }}</p>
        </div>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="thNo" rowspan="2">No.</th>
              <th class="thNik" rowspan="2">NIK</th>
              <th class="thName" rowspan="2">Nama</th>
              <th class="thAddress" colspan="3">Alamat</th>
              <th rowspan="2" colspan="2">tempat tanggal lahir</th>
              <th class="thDateDeath" rowspan="2">Tanggal Wafat</th>
              <th class="thBuriedDate" rowspan="2">Tanggal dimakamkan</th>
              <th class="thRepName" rowspan="2">Nama Pelapor</th>
              <th class="thRepNik" rowspan="2">NIK Pelapor</th>
              <th class="thRepAll" rowspan="2">Nama & No.HP Ahli Waris</th>
              <th class="thBlock" rowspan="2">Blok</th>
              <th class="thNotes" rowspan="2">Ket</th>
            </tr>
            <tr>
                <th class="thVillage">Jalan / Dukuh</th>
                <th class="thRt">RT</th>
                <th class="thRw">RW</th>
            </tr>
          </thead>
          <tbody>
            @if (count($data) == 0)
                <tr>
                    <td class="text-center" colspan="15">
                        <p class="empty-data">Belum Ada Data</p>
                    </td>
                </tr>
            @else
                @php
                    $a = 1;
                @endphp
                @foreach ($data as $item)
                    @php
                        $reportName = $item->reporters_name == NULL ? '' : $item->reporters_name;
                        $reportPhone = $item->reporters_phone == NULL ? '' : $item->reporters_phone;
                        $reportNik = $item->reporters_nik == NULL ? '' : $item->reporters_nik;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $a }}</td>
                        <td>{{ $item->nik }}</td>
                        <td>{{ strtoupper($item->name) }}</td>
                        <td class="tdAddress">{{ strtoupper($item->address) }}</td>
                        <td class="text-center">{{ $item->rt }}</td>
                        <td class="text-center">{{ $item->rw }}</td>
                        <td class="tdBirthPlace text-center">{{ $item->birthPlace->name }}</td>
                        <td class="tdBirthDate text-center">{{ date('d.m.Y', strtotime($item->birth_date)) }}</td>
                        <td class="text-center">{{ date('d.m.Y', strtotime($item->date_of_death)) }}</td>
                        <td class="text-center">{{ date('d.m.Y', strtotime($item->buried_date)) }}</td>
                        <td>{{ $reportName == "" ? '-' : $reportName }}</td>
                        <td>{{ $reportPhone == "" ? '-' : $reportPhone }}</td>
                        <td>
                            <p>{{ $reportName }}</p>
                            <p>{{ $reportPhone }}</p>
                        </td>
                        <td>{{ $item->graveBlock == NULL ? '-' : $item->graveBlock->grave_block }}</td>
                        <td>{{ $item->notes }}</td>
                    </tr>
                @php
                    $a++;
                @endphp
                @endforeach
            @endif
          </tbody>
        </table>
    </div>
  </body>
</html>