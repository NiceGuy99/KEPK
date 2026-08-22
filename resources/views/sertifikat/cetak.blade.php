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
            height: 70px;
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
        <!-- Header Center -->
        <div class="header-center">
            <img src="{{ asset('image/sibar.png') }}" class="logo" alt="Logo">
            <div class="institution-title">Komite Etik Penelitian Kesehatan</div>
            <div class="institution-subtitle">Health Research Ethics Committee</div>
            <div class="institution-name">Rumah Sakit Umum Daerah Sidoarjo Barat</div>
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
            Komite Etik Penelitian Kesehatan Rumah Sakit Umum Daerah Sidoarjo Barat dalam upaya melindungi hak asasi dan kesejahteraan subjek penelitian kesehatan, telah mengkaji dengan teliti protokol berjudul:
        </div>
        <div class="body-text" style="font-style: italic;">
            The Committee of Ethical Approval in the Rumah Sakit Umum Daerah Sidoarjo Barat, with regards to the protection of Human Rights and welfare in health research, carefully reviewed the research protocol entitled:
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
            $tanggal_pengesahan = "Sidoarjo, " . date('d', strtotime($tgl)) . " " . ($bulanArr[date('m', strtotime($tgl))] ?? '') . " " . date('Y', strtotime($tgl));
            $namaPersetujuan = $proposal->jenisPersetujuan?->nama_persetujuan ?? 'Approved';
        @endphp

        <!-- Penutup & Jenis Persetujuan -->
        <div class="closing-text">
            Dan telah menyetujui protokol tersebut di atas melalui <strong>({{ $namaPersetujuan }})</strong><br>
            <span style="font-style: italic;">And approved the above-mentioned protocol with ({{ strtolower($namaPersetujuan) }})</span>
        </div>

        <!-- Bagian Tanda Tangan -->
        <div class="signature-section">
            <div>{{ $tanggal_pengesahan }}</div>
            <div>Ketua KEPK RSUD Sidoarjo Barat</div>
            <div class="signature-space"></div>
            <div><strong>Dr. dr. Arif Rahman Nurdianto, M.Imun., S.H., M.H.</strong></div>
            <div>Pembina Tingkat I / IVb</div>
            <div>NIP 198704162011011007</div>
        </div>

        <!-- Catatan Kaki Masa Berlaku -->
        <div class="footer-note">
            *Masa berlaku 1 tahun<br>
            <i>1 year validity period</i>
        </div>
    </div>

</body>
</html>