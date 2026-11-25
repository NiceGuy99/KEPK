@extends('layouts.app')

@section('title', 'Formulir 7 Standar Kelaikan Etik')

@section('content')
@php
    // Status telaah spesifik milik penelaah ini
    $isReviewSelesai = ($penugasan->status_review === 'selesai');
    
    // Status keseluruhan proposal (5 = Selesai/Lolos)
    $isProposalSelesai = ($penugasan->proposal?->status_id == 5);
    
    // Mode view eksplisit (misal dibuka dari riwayat/detail)
    $isViewMode = (isset($mode) && $mode === 'view');
    
    // Form TERKUNCI HANYA JIKA proposal sudah selesai ATAU sedang dalam mode view
    $readOnly = ($isProposalSelesai || $isViewMode);
@endphp

<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        @php
            $isKetua = (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->role?->nama_role == 'ketua'));
            $detailTelaahRoute = $isKetua ? route('ketua.detail-telaah', $penugasan->id) : route('sekretaris.detail-telaah', $penugasan->id);
        @endphp
        {{-- Tombol kembali dinamis: Kembali ke lembar telaah atau riwayat/detail tergantung mode --}}
        @if($isViewMode)
            <a href="{{ $detailTelaahRoute }}" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="bi bi-arrow-left me-1"></i> Kembali ke Detail Telaah</a>
        @else
            <a href="{{ route('sekretaris.telaah', $penugasan->id) }}" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="bi bi-arrow-left me-1"></i> Kembali ke Lembar Telaah</a>
        @endif
        
        <h3 class="fw-bold text-dark mb-1">Self Assessment: 7 Standar Kelaikan Etik</h3>
        <p class="text-muted mb-0">Proposal: <strong>{{ $penugasan->proposal?->judul_penelitian ?? '-' }}</strong></p>
    </div>
    
    <div>
        @if($isProposalSelesai)
            <span class="badge bg-danger px-3 py-2 fs-6"><i class="bi bi-lock-fill me-1"></i> Terkunci Permanen</span>
        @elseif($isReviewSelesai)
            <span class="badge bg-success px-3 py-2 fs-6"><i class="bi bi-check-circle me-1"></i> Telah Dinilai</span>
        @endif
    </div>
</div>

@if($readOnly)
    <div class="alert alert-warning border-0 shadow-sm mb-4">
        <i class="bi bi-lock-fill me-2"></i> Formulir ini sudah dikunci dan bersifat hanya-baca (read-only) karena status akhir proposal telah selesai.
    </div>
@elseif($isReviewSelesai)
    <div class="alert alert-info border-0 shadow-sm mb-4">
        <i class="bi bi-pencil-square me-2"></i> Anda sudah menyimpan penilaian sebelumnya. Anda masih dapat mengedit atau memperbarui jawaban di bawah ini.
    </div>
@endif

<div class="card card-modern p-4 mb-4 bg-light border-0">
    <h6 class="fw-bold text-dark mb-2">Informasi & Petunjuk Pengisian</h6>
    <p class="small text-muted mb-0">
        Daftar Tilik ini merupakan Catatan Telaah Protokol Penelitian untuk memperoleh Persetujuan Etik (7 Butir Standar Universal WHO 2011, 25 Pedoman CIOMS 2016, serta Pedoman KEPPKN 2018). Tim penelaah memberikan keputusan Setuju jika standar terpenuhi, atau Tidak Setuju jika belum terpenuhi.
    </p>
</div>

<form action="{{ route('sekretaris.form7standar.simpan', $penugasan->id) }}" method="POST">
    @csrf

    @php
        $formSections = [
            'B. Persyaratan Umum' => [
                'b1' => '1. Nilai Sosial/Klinis',
                'b2' => '2. Nilai Ilmiah',
                'b3' => '3. Pemerataan Beban dan Manfaat',
                'b4a' => '4. a. Potensi risiko > manfaat',
                'b4b' => '4. b. Potensi manfaat > risiko',
                'b4c' => '4. c. Potensi manfaat < risiko',
                'b4d' => '4. d. Standar risiko minimal >/=',
                'b4e' => '4. e. Sedikit diatas standar risiko minimal',
                'b4f' => '4. f. Risiko terhadap peneliti',
                'b5' => '5. Bujukan/Eksploitasi/Inducement',
                'b6' => '6. Rahasia dan Privacy',
                'b7' => '7. Informed Consent/IC (Persetujuan Setelah Penjelasan/PSP)',
            ],
            '1. Indikator Standar-1: Nilai Sosial / Klinis' => [
                'i1_1' => "Penelitian ini memenuhi standar Nilai Sosial/Klinis, minimal terdapat satu diantara 7 (tujuh) nilai berikut:\n1.1. Terdapat Novelty (kebaruan). Dalam penelitian ini terdapat nilai kebaruan, yaitu terdapat minimal satu dari 3 sifat berikut:\na. Potensi menghasilkan informasi yang valid sesuai dengan tujuan yang dinyatakan dalam protokol penelitian.\nb. Memiliki relevansi bermakna dengan masalah kesehatan\nc. Memiliki kontribusi terhadap suatu penciptaan/ kebermanfaatan dalam melakukan evaluasi intervensi kebijakan, atau sebagai bagian dari pelaksanaan kegiatan yang mempromosikan kesehatan individu atau masyarakat",
                'i1_2' => '1.2. Sebagai upaya mendesiminasikan hasil',
                'i1_3' => '1.3. Relevansinya bermanfaat dengan masalah kesehatan',
                'i1_4' => '1.4. Memberikan kontribusi promosi kesehatan',
                'i1_5' => '1.5. Menghasilkan alternatif cara mengatasi masalah',
                'i1_6' => '1.6. Menghasilkan data & informasi yang dapat dimanfaatkan untuk pengambilan keputusan klinis/sosial',
                'i1_7' => '1.7. Terdapat uraian tentang penelitian lanjutan yang dapat dilakukan dari hasil penelitian yang sekarang',
            ],
            '2. Indikator Standar-2: Nilai Ilmiah' => [
                'i2_1a' => "Penelitian ini memenuhi standar nilai ilmiah. Memenuhi beberapa parameter butir 2.1 antara lain:\n2.1. Desain penelitian mengikuti kaidah ilmiah, yang menjelaskan secara rinci meliputi :\na. Desain penelitian;\nTerdapat deskripsi detil tentang desain penelitian, untuk berbagai jenis penelitian.\n1) Bila berupa kuesioner, terdapat uraian mengenai tatacara kuesioner, kartu buku harian dan bahan lain yang relevan digunakan untuk menjawab pertanyaan penelitian\n2) Bila penelitian klinis dan atau ujicoba klinis, deskripsi harus meliputi apakah kelompok intervensi ditentukan secara non-random, random (termasuk bagaimana metodenya), dan apakah blinded (single/double) atau terbuka (open-label)",
                'i2_1b' => 'b. Tempat dan waktu penelitian',
                'i2_1c' => "c. Jenis sampel, besar sampel, kriteria inklusi dan eksklusi; teknik sampling\nTerdapat uraian tentang jumlah subjek yang dibutuhkan sesuai tujuan penelitian dan bagaimana penentuannya secara statistik (tergantung relevansi)",
                'i2_1d' => 'd. Variabel penelitian dan definisi operasional;',
                'i2_1e' => 'e. Instrument penelitian/alat untuk mengambil data/bahan penelitian ;',
                'i2_1f' => 'f. Prosedur penelitian dan keterlibatan subjek, serta dalam protokol menggambarkan peran dan tanggung jawab masing-masing anggota tim',
                'i2_1g' => 'g. Intervensi/cara pengumpulan data (uraikan secara detail langkah-langkah yang akan dilakukan)',
                'i2_1h' => 'h. Tata cara pencatatan selama penelitian, termasuk efek samping dan komplikasi bila ada;',
                'i2_1i' => 'i. Rencana analisis data, jaminan kualitas pengumpulan, penyimpanan dan analisis data',
                'i2_1j' => 'j. Penjelasan mengenai tes laboratorium dan prosedur diagnostik',
                'i2_1k' => 'k. Gambaran protokol mengenai pengkodean spesimen dan/atau data',
                'i2_bio_a' => 'Jika merupakan bahan biologis/spesimen sebagai subyek: a. Uraian mengenai penggunaan sampel spesimen yang akan dimasukkan, baik dalam penelitian saat ini dan dalam jangka panjang',
                'i2_bio_b' => 'b. Penjelasan apabila spesimen akan dikirimkan ke luar negeri atau berpindah dan dimanfaatkan oleh peneliti/pihak lain',
                'i2_bio_c' => 'c. Penjelasan lama spesimen akan disimpan dan cara spesimen akan dihancurkan; termasuk ketentuan untuk subjek dalam memutuskan penggunaan sisa spesimen dalam penelitian masa depan yang bersifat terbatas atau tidak spesifik',
                'i2_bio_d' => 'd. Penjelasan mengenai pengujian genetik / analisis genom yang akan dilakukan pada bahan biologis manusia',
                'i2_bio_e' => 'e. Terdapat penjelasan mengenai prosedur untuk mendapatkan sampel, baik rutin atau intervensi. Jika rutin, terdapat penjelasan bila prosedur merupakan prosedur yang lebih invasif daripada biasanya',
                'i2_2a' => "Jika Intervensi/Penelitian uji klinik, maka:\n2.2. Peneliti harus memahami sepenuhnya kewajiban dan tanggung jawab yang dipersyaratkan dengan:\na. Memiliki sertifikat Etik Dasar Lanjut dan GCP",
                'i2_2b' => 'b. Mengisi dan menyerahkan daftar tilik GCP E6.4.1-13 yang telah ditandatangani peneliti tentang ringkasan tanggung jawab peneliti yang berkaitan dengan uji klinik kepada KEPK',
                'i2_Ktr_a' => "Kontribusinya terhadap penciptaan atau evaluasi intervensi, harus memenuhi: semua atau antara lain\na. Terdapat ringkasan hasil penelitian sebelumnya sesuai topik penelitian yang diusulkan",
                'i2_Ktr_b' => 'b. Terdapat gambaran singkat tentang lokasi penelitian, informasi demografis dan epidemiologis yang relevan',
                'i2_Ktr_c' => 'c. Terdapat deskripsi dan penjelasan semua intervensi (metode perlakuan), termasuk rute pemberian, dosis, interval dosis',
                'i2_Ktr_d' => 'd. Terdapat rencana dan justifikasi untuk meneruskan atau menghentikan standar terapi selama penelitian',
                'i2_Ktr_e' => 'e. Terdapat uraian jenis perlakuan/pengobatan lain yang mungkin diberikan atau diperbolehkan',
                'i2_Ktr_f' => 'f. Terdapat penjelasan tentang pemeriksaan klinis/ non klinis yang harus dilakukan',
                'i2_Ktr_g' => 'g. Terdapat format laporan kasus yang sudah terstandar, metode pencatatan respon terapetik',
                'i2_Ktr_h' => 'h. Terdapat aturan atau kriteria kapan subjek bisa diberhentikan dari penelitian atau uji klinis',
                'i2_Ktr_i' => 'i. Terdapat uraian tentang metode pencatatan dan pelaporan Adverse Events atau reaksi',
                'i2_Ktr_j' => 'j. Terdapat uraian tentang risiko yang diketahui dari Adverse Events',
                'i2_Ktr_k' => 'k. Terdapat deskripsi tentang rencana analisis statistik',
                'i2_Ktr_l' => 'l. Terdapat rincian sumber dan jumlah dana riset',
                'i2_Ktr_m' => 'm. Terdapat dokumen pengaturan (financial disclosure) untuk mengatasi konflik finansial',
                'i2_Ktr_n' => 'n. Terdapat penjelasan jika hasil riset negatif dan memastikan bahwa hasilnya tersedia melalui publikasi',
            ],
            '3. Indikator Standar-3: Pemerataan Beban dan Manfaat' => [
                'i3_1' => '3.1. Tercantum uraian bahwa manfaat dan beban didistribusikan secara merata',
                'i3_2' => "3.2. Rekrutmen subjek dilakukan berdasarkan pertimbangan ilmiah, dan tidak berdasarkan status sosial ekonomi",
                'i3_3' => '3.3. Informasi dalam “media” perekrutan peserta disediakan dalam bahasa Inggris atau bahasa lokal',
                'i3_4' => '3.4. Dalam memilih atau tidak memilih subjek tertentu, pertimbangkan kekhususan subjek',
                'i3_5' => '3.5. Kelompok subjek yang tidak mungkin memperoleh manfaat dapat dipisahkan',
                'i3_6' => '3.6. Kelompok yang kurang terwakili dalam penelitian medis harus diberikan akses yg tepat',
                'i3_7' => '3.7. Pembedaan distribusi beban dan manfaat juga dapat dipertimbangkan',
                'i3_8' => '3.8. Jumlah/proporsi subjek terpinggirkan terwakili secara seimbang',
                'i3_9' => '3.9. Subjek terpilih menerima beban keikutsertaan lebih besar dibanding peluang menikmati manfaat',
                'i3_10' => '3.10. Kelompok rentan tidak dikeluarkan dari partisipasi dalam penelitian',
                'i3_11' => '3.11. Penelitian tidak memanfaatkan subjek secara berlebihan karena kemudahan',
                'i3_11a' => 'a. Terdapat pernyataan yang jelas tentang pentingnya penelitian bagi penduduk',
                'i3_11b' => 'b. Kriteria subjek dan alasan penentuan yang tidak masuk kriteria',
                'i3_11c' => 'c. Terdapat alasan melibatkan anak atau orang dewasa yang tidak bisa mandiri',
                'i3_11d' => 'd. Terdapat rencana dan alasan untuk meneruskan atau menghentikan standar terapi',
                'i3_11e' => 'e. Terdapat penjelasan tentang perlakuan lain yang mungkin diberikan',
                'i3_11f' => 'f. Terdapat penjelasan tentang rencana pemeriksaan klinis atau laboratorium',
                'i3_11g' => 'g. Disertakan format laporan kasus yang sudah distandarisasi',
                'i3_11h' => 'h. Terdapat uraian tentang potensi manfaat/keuntungan secara pribadi',
                'i3_11i' => 'i. Terdapat uraian keuntungan yang dapat diharapkan bagi penduduk',
                'i3_11j' => 'j. Terdapat uraian kemungkinan dapat diberikan kelanjutan akses',
                'i3_11k' => 'k. Ketika penelitian melibatkan ibu hamil, ada rencana memonitor kesehatan',
            ],
            '4. Indikator Standar-4: Potensi Manfaat dan Risiko' => [
                'i4_1' => '4.1. Terdapat uraian potensi manfaat penelitian yang lebih besar bagi individu/subjek',
                'i4_2' => '4.2. Terdapat uraian risiko bahwa risiko sangat minimal',
                'i4_3' => '4.3. Tersedia uraian intervensi efektif sesuai golden standard',
                'i4_4' => '4.4. Terdapat uraian tentang kerugian yang sedikit di atas ambang risiko minimal',
                'i4_5' => '4.5. Terdapat uraian tentang tinggi rendahnya potensi risiko terhadap peneliti',
                'i4_6' => '4.6. Terdapat uraian tentang kerugian fisik, sosial, emosional, stigmatisasi',
                'i4_7' => '4.7. Terdapat uraian tentang tinggi rendahnya risiko terhadap kelompok/masyarakat',
                'i4_8' => '4.8. Terdapat simpulan agregat risiko dan manfaat keseluruhan',
                'i4_9' => '4.9. Terdapat uraian tentang potensi risiko fisik, psikis, sosial lebih besar',
                'i4_10' => '4.10. Terdapat penjelasan keuntungan sosial dan ilmiah dibanding kerugian',
                'i4_11' => '4.11. Terdapat brosur peneliti saat melibatkan obat baru atau vaksin',
                'i4_12' => '4.12. Protokol mendeskripsikan manfaat yang diterima komunitas asal subyek',
                'i4_13' => '4.13. Informasi mengenai perlunya Komite Pemantauan Keamanan Data (DSMB)',
                'i4_14' => '4.14. Protokol menjelaskan kejadian buruk serius (SAE) dan mekanisme pelaporan',
                'i4_15' => '4.15. Deskripsi ketentuan penanganan reaksi negatif dan temuan kebetulan',
            ],
            '5. Indikator Standar-5: Bujukan / Eksploitasi / Undue Inducement' => [
                'i5_1' => '5.1. Terdapat penjelasan tentang insentif bagi subjek (material / non-material)',
                'i5_2' => '5.2. Insentif pada penelitian berisiko luka fisik diuraikan lebih detail',
                'i5_3' => '5.3. Terdapat uraian indikasi bujukan tidak semestinya atau eksploitasi',
            ],
            '6. Indikator Standar-6: Rahasia dan Privacy' => [
                'i6_1' => '6.1. Meminta persetujuan baru ketika ada indikasi kejadian tidak diinginkan',
                'i6_2' => '6.2. Peneliti mengharuskan subjek konsultasi lanjutan saat indikasi penyakit serius',
                'i6_3' => '6.3. Peneliti harus netral terhadap temuan baru',
                'i6_4' => '6.4. Peneliti menjaga kerahasiaan temuan',
                'i6_a' => 'a. Terdapat penjelasan bagaimana menjaga privacy dan kerahasiaan sejak rekruitmen',
                'i6_b' => 'b. Penjelasan menjaga privacy saat menjelaskan prosedur',
                'i6_c' => 'c. Penjelasan menjaga kerahasiaan meski subjek diwakili',
                'i6_d' => 'd. Pemahaman data/informasi mutlak rahasia (misal: tes genetik)',
                'i6_e' => 'e. Uraian pembuatan kode identitas subjek dan tempat penyimpanan',
                'i6_f' => 'f. Penjelasan kemungkinan penggunaan data personal untuk penelitian lanjutan',
                'i6_g' => 'g. Penjelasan jika hasil riset negatif tersedia melalui publikasi',
            ],
            '7. Indikator Standar-7: Informed Consent' => [
                'i7_1' => '7.1. Terdapat Lembar IC dengan penjelasan (PSP)',
                'i7_2' => '7.2. Terdapat penjelasan proses mendapatkan persetujuan',
                'i7_3' => '7.3. Terdapat uraian bagi subjek buta huruf dengan saksi',
                'i7_4' => '7.4. Ketentuan peserta tidak mampu memberi persetujuan pribadi',
                'i7_5' => '7.5. Informasi yang diberikan pada komunitas dan tatacara pengambilan persetujuan',
                'i7_6a' => 'a. Latar belakang, bahasa naskah mudah dipahami',
                'i7_6b' => 'b. Manfaat penelitian yang dipahami subjek',
                'i7_6c' => 'c. Perlakuan yang diterima subjek jelas',
                'i7_6d' => 'd. Lama keikutsertaan subjek jelas durasinya',
                'i7_6e' => 'e. Jenis dan lama wawancara yang direkam audio/video',
                'i7_6f' => 'f. Karakteristik subjek penelitian cocok',
                'i7_6g' => 'g. Jumlah subjek dan risiko jika mengundurkan diri',
                'i7_6h' => 'h. Kemungkinan risiko terhadap kesehatan',
                'i7_6i' => 'i. Jaminan kerahasiaan data',
                'i7_6j' => 'j. Kompensasi yang diberikan',
                'i7_6k' => 'k. Unsur paksaan dijelaskan transparan',
                'i7_6l' => 'l. Penjelasan pengobatan medis dan ganti rugi',
                'i7_6m' => 'm. Nama dan nomor telepon penanggung jawab penelitian',
                'i7_6n' => 'n. Nama dan nomor telepon penanggung jawab medis',
                'i7_6o' => 'o. Hak mengundurkan diri sewaktu-waktu',
                'i7_6p' => 'p. Kesediaan subjek atau wali bertanda tangan',
                'i7_6q' => 'q. Tanda tangan saksi',
                'i7_6r' => 'r. Pilihan pengobatan/perlakuan selain dalam penelitian',
                'i7_7' => '7.7. Prosedur melindungi kerahasiaan data',
            ]
        ];
    @endphp

    @foreach($formSections as $sectionTitle => $questions)
        <div class="card card-modern p-0 mb-4 border-top border-info border-4 shadow-sm overflow-hidden">
            <div class="bg-light p-3 border-bottom">
                <h5 class="fw-bold text-primary m-0">{{ $sectionTitle }}</h5>
            </div>
            <div class="p-4">
                @foreach($questions as $key => $label)
                    <div class="mb-4 {{ !$loop->last ? 'border-bottom pb-4' : '' }}">
                        <label class="fw-medium text-dark mb-3 d-block" style="line-height: 1.6;">
                            <span class="badge bg-secondary me-2 p-2">{{ strtoupper(str_replace('_', '.', $key)) }}</span> 
                            {!! nl2br(e($label)) !!} 
                        </label>
                        <div class="d-flex flex-wrap gap-4 mt-2 ms-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="{{ $key }}" value="Ya" id="{{ $key }}_ya" {{ (isset($savedAnswers[$key]) && $savedAnswers[$key] == 'Ya') ? 'checked' : '' }} {{ $readOnly ? 'disabled' : '' }}>
                                <label class="form-check-label text-success fw-bold" for="{{ $key }}_ya">Ya</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="{{ $key }}" value="Tidak" id="{{ $key }}_tidak" {{ (isset($savedAnswers[$key]) && $savedAnswers[$key] == 'Tidak') ? 'checked' : '' }} {{ $readOnly ? 'disabled' : '' }}>
                                <label class="form-check-label text-danger fw-bold" for="{{ $key }}_tidak">Tidak</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="{{ $key }}" value="N/A" id="{{ $key }}_na" {{ (isset($savedAnswers[$key]) && $savedAnswers[$key] == 'N/A') ? 'checked' : '' }} {{ $readOnly ? 'disabled' : '' }}>
                                <label class="form-check-label text-muted fw-bold" for="{{ $key }}_na">N/A* <small class="fw-normal">(Tidak Dapat Diterapkan)</small></label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <!-- TOMBOL AKSI -->
    <div class="d-flex justify-content-end gap-2 mb-5">
        @if(!$readOnly)
            <button type="submit" class="btn btn-primary btn-modern px-5 py-3 fs-5" style="background: #0d9488; border: none;">
                <i class="bi bi-save me-2"></i> {{ $isReviewSelesai ? 'Update Penilaian Formulir 7 Standar' : 'Simpan Penilaian Formulir 7 Standar' }}
            </button>
        @else
            @if($isViewMode)
                <a href="{{ route('sekretaris.detail-telaah', $penugasan->id) }}" class="btn btn-secondary btn-modern px-5 py-3 fs-5">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Detail Telaah
                </a>
            @else
                <a href="{{ route('sekretaris.telaah', $penugasan->id) }}" class="btn btn-primary btn-modern px-5 py-3 fs-5" style="background: #0d9488; border: none;">
                    Kembali ke Lembar Telaah <i class="bi bi-arrow-right ms-2"></i>
                </a>
            @endif
        @endif
    </div>
</form>
@endsection