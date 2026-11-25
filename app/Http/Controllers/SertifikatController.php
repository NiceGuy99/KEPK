<?php

namespace App\Http\Controllers;

use App\Models\PengajuanProposal;
use App\Models\Sertifikat;
use App\Models\PengaturanSertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Output\QRMarkupSVG;
use chillerlan\QRCode\Common\EccLevel;

class SertifikatController extends Controller
{
    public function cetak($proposalId)
    {
        $user = Auth::user();
        $proposal = PengajuanProposal::with([
            'peneliti.profil.statusPengguna',
            'jenisPenelitian',
            'jenisPersetujuan',
            'sertifikat',
        ])->findOrFail($proposalId);

        if ($user && $user->isPeneliti() && $proposal->peneliti_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        if (!$proposal->sertifikat) {
            return back()->with('pesan_error', 'Sertifikat belum diterbitkan untuk proposal ini.');
        }

        // Ambil Pengaturan Sertifikat Dinamis
        $pengaturan = PengaturanSertifikat::getActive();

        // Siapkan Payload Teks Verifikasi untuk QR Code TTD (ringkas & padat agar modul QR besar dan mudah discan)
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

        try {
            if (extension_loaded('gd')) {
                // Gunakan PNG raster pixel-perfect dengan resolusi tinggi (sangat mudah & cepat discan kamera HP)
                $options = new QROptions([
                    'outputInterface'     => \chillerlan\QRCode\Output\QRGdImagePNG::class,
                    'outputBase64'        => true,
                    'scale'               => 8,           // Skala resolusi tajam
                    'eccLevel'            => EccLevel::L, // Modul/dot terbesar
                    'addQuietzone'        => true,
                    'quietzoneSize'       => 4,
                    'bgColor'             => [255, 255, 255],
                    'imageTransparent'    => false,       // Background putih solid 100%
                ]);
                $qrCodeSvg = (new QRCode($options))->render($qrPayload);
            } else {
                // Fallback SVG murni tanpa GD
                $options = new QROptions([
                    'outputInterface'     => QRMarkupSVG::class,
                    'outputBase64'        => false,
                    'svgAddXmlHeader'     => false,
                    'eccLevel'            => EccLevel::L,
                    'addQuietzone'        => true,
                    'quietzoneSize'       => 4,
                    'drawLightModules'    => false,
                    'connectPaths'        => true,
                ]);
                $rawSvg = (new QRCode($options))->render($qrPayload);
                $qrCodeSvg = preg_replace('/(<svg[^>]*>)/i', '$1<rect width="100%" height="100%" fill="#ffffff"/>', $rawSvg);
                $qrCodeSvg = str_replace('<svg ', '<svg shape-rendering="crispEdges" ', $qrCodeSvg);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('[Sertifikat] Gagal generate QR TTD: ' . $e->getMessage());
            $qrCodeSvg = null;
        }

        return view('sertifikat.cetak', compact('proposal', 'qrCodeSvg', 'pengaturan'));
    }
}
