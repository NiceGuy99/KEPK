<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Ethical Approval - {{ $proposal->kode_proposal ?? '-' }}</title>
    <style>
        /* Pengaturan Ukuran Kertas F4 (Folio: 215.9mm x 330mm) */
        @page {
            size: 215.9mm 330mm;
            margin: 10mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            margin: 0;
            padding: 0;
            background: #fff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .certificate-box {
            border: 3px double #1e3a8a;
            padding: 40px 50px; 
            width: 100%;
            height: 305mm; /* Memastikan batas kotak sampai bawah pada kertas F4 */
            box-sizing: border-box;
            margin: 0; 
            position: relative;
            background: url("{{ asset('image/background/bg_sertifikat.jpeg') }}") no-repeat center center;
            background-size: cover;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .header-center {
            text-align: center;
            margin-bottom: 10px;
        }
        .logo {
            width: 70px;
            height: auto;
            margin-bottom: 8px;
        }
        .institution-title {
            font-size: 16pt; /* Naik dari 15pt */
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
        }
        .institution-subtitle {
            font-size: 13.5pt; /* Naik dari 12.5pt */
            font-style: italic;
            margin-top: 2px;
            margin-bottom: 2px;
            letter-spacing: 0.3px;
        }
        .institution-name {
            font-size: 14.5pt; /* Naik dari 13.5pt */
            font-weight: bold;
            text-transform: uppercase;
        }
        .cert-title {
            text-align: center;
            margin-top: 15px;
            margin-bottom: 20px;
        }
        .cert-title h3 {
            font-size: 15pt; /* Naik dari 14pt */
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .cert-title .sub {
            font-size: 12.5pt; /* Naik dari 11.5pt */
            font-style: italic;
            margin-top: 2px;
        }
        .approval-heading {
            text-align: center;
            font-size: 15pt; /* Naik dari 14pt */
            font-weight: bold;
            margin: 15px 0 5px 0;
        }
        .approval-number {
            text-align: center;
            font-size: 12.5pt; /* Naik dari 11.5pt */
            font-weight: bold;
            margin-bottom: 25px;
        }
        .body-text {
            font-size: 13pt; /* Naik dari 12pt */
            text-align: justify;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .project-title {
            text-align: center;
            font-size: 15pt; /* Naik dari 14pt */
            font-weight: bold;
            color: #1e3a8a;
            margin: 20px 0;
            text-transform: uppercase;
        }
        .details-table {
            width: 100%;
            margin-top: 20px;
            margin-bottom: 20px;
            font-size: 13pt; /* Naik dari 12pt */
            border-collapse: collapse;
        }
        .details-table td {
            padding: 6px 0;
            vertical-align: top;
        }
        .details-table .label {
            width: 250px;
        }
        .details-table .label-en {
            font-style: italic;
            font-size: 11.5pt; /* Naik dari 10.5pt */
            color: #333;
        }
        .details-table .separator {
            width: 20px;
            text-align: center;
        }
        .closing-text {
            font-size: 13pt; /* Naik dari 12pt */
            margin-top: 20px;
            margin-bottom: 40px;
            line-height: 1.5;
        }
        .signature-section {
            position: absolute;
            bottom: 120px; 
            right: 50px;
            text-align: left;
            width: 300px;
            font-size: 13pt; /* Naik dari 12pt */
        }
        .signature-space {
            min-height: 115px;
            margin: 8px 0 10px 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .qr-ttd-box {
            width: 110px;
            height: 110px;
            flex-shrink: 0;
            display: inline-block;
            background: #ffffff !important;
            padding: 4px;
            border-radius: 4px;
            border: none !important;
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .qr-ttd-box img,
        .qr-ttd-box svg {
            width: 100% !important;
            height: 100% !important;
            display: block;
            image-rendering: pixelated;
        }
        .qr-ttd-info {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.5pt;
            color: #1e293b;
            line-height: 1.35;
        }
        .qr-ttd-info .title {
            font-weight: bold;
            color: #1e3a8a;
            font-size: 9pt;
            display: block;
            margin-bottom: 3px;
        }
        .footer-note {
            position: absolute;
            bottom: 40px;
            left: 50px;
            right: 50px;
            font-size: 10.5pt; /* Naik dari 9.5pt */
            font-style: italic;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
        @media print {
            body { padding: 0; background: none; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none; }
            .certificate-box { 
                border: 3px double #000; 
                margin: 0 !important;
                height: 330mm !important; 
                background: url("{{ asset('image/background/bg_sertifikat.jpeg') }}") no-repeat center center !important;
                background-size: cover !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <!-- Tombol Cetak Saja (Hilang saat diprint) -->
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 25px; background: #2563eb; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 12pt;">Cetak Sertifikat</button> <!-- Naik dari 11pt -->
    </div>

    <div class="certificate-box">
        @php
            $pengaturan = $pengaturan ?? \App\Models\PengaturanSertifikat::getActive();
        @endphp
        <!-- Header Center -->
        <div class="header-center">
            <img src="{{ asset('image/sibar.png') }}" class="logo" alt="Logo">
            <div class="institution-title">{{ $pengaturan->nama_lembaga ?? 'Komite Etik Penelitian Kesehatan' }}</div>
            <div class="institution-subtitle">{{ $pengaturan->nama_lembaga_en ?? 'Health Research Ethics Committee' }}</div>
            <div class="institution-name">{{ $pengaturan->nama_instansi ?? 'Rumah Sakit Umum Daerah Sidoarjo Barat' }}</div>
        </div>
        <hr style="border: 1px solid #1e3a8a; margin-top: 10px; margin-bottom: 15px;">

        <!-- Judul Keterangan -->
        <div class="cert-title">
            <h3>Keterangan Lolos Kaji Etik</h3>
            <div class="sub">Description of Ethical Approval</div>
        </div>

        <div class="approval-heading">&ldquo;ETHICAL APPROVAL&rdquo;</div>
        <div class="approval-number">NO : {{ $proposal->sertifikat->nomor_sertifikat ?? '-' }}</div>

        <div class="body-text">
            {{ $pengaturan->nama_lembaga ?? 'Komite Etik Penelitian Kesehatan' }} {{ $pengaturan->nama_instansi ?? 'Rumah Sakit Umum Daerah Sidoarjo Barat' }} dalam upaya melindungi hak asasi dan kesejahteraan subjek penelitian kesehatan, telah mengkaji dengan teliti protokol berjudul:
        </div>
        <div class="body-text" style="font-style: italic;">
            The Committee of Ethical Approval in the {{ $pengaturan->nama_instansi ?? 'Rumah Sakit Umum Daerah Sidoarjo Barat' }}, with regards to the protection of Human Rights and welfare in health research, carefully reviewed the research protocol entitled:
        </div>

        <!-- Judul Proposal -->
        <div class="project-title">
            "{{ $proposal->judul_penelitian }}"
        </div>

        <!-- Tabel Detail Peneliti -->
        <table class="details-table">
            <tr>
                <td class="label">
                    <u>Peneliti Utama</u><br>
                    <span class="label-en">Principal Investigator</span>
                </td>
                <td class="separator">:</td>
                <td><strong>{{ $proposal->peneliti?->nama_lengkap ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td class="label" style="padding-top: 8px;">
                    <u>Nama Institusi</u><br>
                    <span class="label-en">Name of the Institution</span>
                </td>
                <td class="separator" style="padding-top: 8px;">:</td>
                <td style="padding-top: 8px;"><strong>{{ $proposal->peneliti?->profil?->asal_kampus ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td class="label" style="padding-top: 8px;">
                    <u>Unit/Lembaga/Tempat Penelitian</u><br>
                    <span class="label-en">Setting of research</span>
                </td>
                <td class="separator" style="padding-top: 8px;">:</td>
                <td style="padding-top: 8px;"><strong>{{ $proposal->tempat_penelitian ?? '-' }}</strong></td>
            </tr>
        </table>

        @php
            $tgl = $proposal->sertifikat->tanggal_terbit ?? date('Y-m-d');
            $bulanArr = ['01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'];
            $tglFormatted = date('d', strtotime($tgl)) . " " . ($bulanArr[date('m', strtotime($tgl))] ?? '') . " " . date('Y', strtotime($tgl));
            $kotaPenerbitan = $pengaturan->kota_penerbitan ?? 'Sidoarjo';
            $tanggal_pengesahan = "{$kotaPenerbitan}, {$tglFormatted}";
            $namaPersetujuan = $proposal->jenisPersetujuan?->nama_persetujuan ?? 'Approved';

            if (!isset($qrCodeSvg) || empty($qrCodeSvg)) {
                try {
                    $noSertifikat = $proposal->sertifikat->nomor_sertifikat ?? '-';
                    $judulProposal = $proposal->judul_penelitian ?? '-';
                    $namaPeneliti = $proposal->peneliti?->nama_lengkap ?? '-';

                    $penandatanganLines = [];
                    if (!empty($pengaturan->nama_penandatangan)) {
                        $penandatanganLines[] = "ditandatangani oleh " . $pengaturan->nama_penandatangan;
                    }
                    if (!empty($pengaturan->pangkat_golongan)) {
                        $penandatanganLines[] = $pengaturan->pangkat_golongan;
                    }
                    if (!empty($pengaturan->nip_penandatangan)) {
                        $penandatanganLines[] = "NIP " . $pengaturan->nip_penandatangan;
                    }

                    $qrPayload = "No. Sertifikat: {$noSertifikat}\n"
                               . "Judul Proposal: {$judulProposal}\n"
                               . "Nama Peneliti: {$namaPeneliti}\n"
                               . implode("\n", $penandatanganLines);

                    if (extension_loaded('gd')) {
                        $options = new \chillerlan\QRCode\QROptions([
                            'outputInterface'     => \chillerlan\QRCode\Output\QRGdImagePNG::class,
                            'outputBase64'        => true,
                            'scale'               => 8,
                            'eccLevel'            => \chillerlan\QRCode\Common\EccLevel::L,
                            'addQuietzone'        => true,
                            'quietzoneSize'       => 4,
                            'bgColor'             => [255, 255, 255],
                            'imageTransparent'    => false,
                        ]);
                        $qrCodeSvg = (new \chillerlan\QRCode\QRCode($options))->render($qrPayload);
                    } else {
                        $options = new \chillerlan\QRCode\QROptions([
                            'outputInterface'     => \chillerlan\QRCode\Output\QRMarkupSVG::class,
                            'outputBase64'        => false,
                            'svgAddXmlHeader'     => false,
                            'eccLevel'            => \chillerlan\QRCode\Common\EccLevel::L,
                            'addQuietzone'        => true,
                            'quietzoneSize'       => 4,
                            'drawLightModules'    => false,
                            'connectPaths'        => true,
                        ]);
                        $rawSvg = (new \chillerlan\QRCode\QRCode($options))->render($qrPayload);
                        $qrCodeSvg = preg_replace('/(<svg[^>]*>)/i', '$1<rect width="100%" height="100%" fill="#ffffff"/>', $rawSvg);
                        $qrCodeSvg = str_replace('<svg ', '<svg shape-rendering="crispEdges" ', $qrCodeSvg);
                    }
                } catch (\Throwable $e) {
                    $qrCodeSvg = null;
                }
            }
        @endphp

        <!-- Penutup & Jenis Persetujuan -->
        <div class="closing-text">
            Dan telah menyetujui protokol tersebut di atas melalui <strong>({{ $namaPersetujuan }})</strong><br>
            <span style="font-style: italic;">And approved the above-mentioned protocol with ({{ strtolower($namaPersetujuan) }})</span>
        </div>

        <!-- Bagian Tanda Tangan -->
        <div class="signature-section">
            <div>{{ $tanggal_pengesahan }}</div>
            <div>{{ $pengaturan->jabatan_penandatangan ?? 'Ketua KEPK RSUD Sidoarjo Barat' }}</div>
            <div class="signature-space">
                @if(!empty($qrCodeSvg))
                    <div class="qr-ttd-box">
                        @if(str_starts_with($qrCodeSvg, 'data:image'))
                            <img src="{{ $qrCodeSvg }}" alt="QR TTD">
                        @else
                            {!! $qrCodeSvg !!}
                        @endif
                    </div>
                    <div class="qr-ttd-info">
                        <span class="title">Ditandatangani Elektronik</span>
                        <span>Scan untuk verifikasi keaslian dokumen</span>
                    </div>
                @else
                    <div style="height: 70px;"></div>
                @endif
            </div>
            <div><strong>{{ $pengaturan->nama_penandatangan ?? 'Dr. dr. Arif Rahman Nurdianto, M.Imun., S.H., M.H.' }}</strong></div>
            @if(!empty($pengaturan->pangkat_golongan))
                <div>{{ $pengaturan->pangkat_golongan }}</div>
            @endif
            @if(!empty($pengaturan->nip_penandatangan))
                <div>NIP {{ $pengaturan->nip_penandatangan }}</div>
            @endif
        </div>

        <!-- Catatan Kaki Masa Berlaku -->
        <div class="footer-note">
            {{ $pengaturan->masa_berlaku_teks ?? '*Masa berlaku 1 tahun' }}<br>
            <i>{{ $pengaturan->masa_berlaku_teks_en ?? '1 year validity period' }}</i>
        </div>
    </div>

</body>
</html>