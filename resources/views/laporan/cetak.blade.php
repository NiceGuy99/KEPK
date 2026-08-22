<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan KEPK - {{ ucfirst($jenis) }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 11pt; color: #333; margin: 20px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h3, .header h4 { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #0d9488; color: white; border: none; border-radius: 4px; cursor: pointer;">Cetak Laporan</button>
    </div>

    <div class="header">
        <h3>KOMITE ETIK PENELITIAN KESEHATAN (KEPK)</h3>
        <h4>RSUD SIDOARJO BARAT</h4>
        <p style="margin: 5px 0 0 0; font-size: 10pt;">Laporan: {{ strtoupper($jenis) }} | Periode: {{ date('d/m/Y', strtotime($tglAwal)) }} s/d {{ date('d/m/Y', strtotime($tglAkhir)) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                @if($jenis == 'pengajuan')
                    <th>Kode</th><th>Judul Penelitian</th><th>Peneliti</th><th>Jenis Riset</th><th>Tgl Pengajuan</th>
                @elseif($jenis == 'telaah')
                    <th>Kode</th><th>Judul Penelitian</th><th>Penelaah</th><th>Tgl Penugasan</th><th>Status</th>
                @elseif($jenis == 'selesai')
                    <th>Kode</th><th>Judul Penelitian</th><th>Peneliti</th><th>Jenis Persetujuan</th>
                @elseif($jenis == 'sertifikat')
                    <th>No Sertifikat</th><th>Kode</th><th>Judul Penelitian</th><th>Peneliti</th><th>Tgl Terbit</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($data as $idx => $item)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    @if($jenis == 'pengajuan')
                        <td>{{ $item->kode_proposal }}</td>
                        <td>{{ $item->judul_penelitian }}</td>
                        <td>{{ $item->peneliti?->nama_lengkap }}</td>
                        <td>{{ $item->jenisPenelitian?->nama_jenis }}</td>
                        <td>{{ date('d/m/Y', strtotime($item->tanggal_pengajuan)) }}</td>
                    @elseif($jenis == 'telaah')
                        <td>{{ $item->proposal?->kode_proposal }}</td>
                        <td>{{ $item->proposal?->judul_penelitian }}</td>
                        <td>{{ $item->penelaah?->nama_lengkap }}</td>
                        <td>{{ date('d/m/Y', strtotime($item->tanggal_penugasan)) }}</td>
                        <td>{{ $item->status_review }}</td>
                    @elseif($jenis == 'selesai')
                        <td>{{ $item->kode_proposal }}</td>
                        <td>{{ $item->judul_penelitian }}</td>
                        <td>{{ $item->peneliti?->nama_lengkap }}</td>
                        <td>{{ $item->jenisPersetujuan?->nama_persetujuan ?? 'Lolos' }}</td>
                    @elseif($jenis == 'sertifikat')
                        <td>{{ $item->nomor_sertifikat }}</td>
                        <td>{{ $item->proposal?->kode_proposal }}</td>
                        <td>{{ $item->proposal?->judul_penelitian }}</td>
                        <td>{{ $item->proposal?->peneliti?->nama_lengkap }}</td>
                        <td>{{ date('d/m/Y', strtotime($item->tanggal_terbit)) }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 40px; float: right; text-align: center; width: 250px;">
        <div>Sidoarjo, {{ date('d F Y') }}</div>
        <div>Ketua KEPK RSUD Sidoarjo Barat</div>
        <div style="height: 60px;"></div>
        <div><strong>Dr. dr. Arif Rahman Nurdianto, M.Imun., S.H., M.H.</strong></div>
        <div>NIP 198704162011011007</div>
    </div>
</body>
</html>
