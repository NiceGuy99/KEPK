@extends('layouts.app')

@section('title', 'Formulir 7 Standar Kelaikan Etik')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <a href="{{ route('penelaah.tugas') }}" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="bi bi-arrow-left me-1"></i> Kembali ke Tugas</a>
        <h3 class="fw-bold text-dark mb-1">Self Assessment: 7 Standar Kelaikan Etik</h3>
        <p class="text-muted mb-0">Proposal: <strong>{{ $penugasan->proposal->judul_penelitian }}</strong></p>
    </div>
    @if($isSelesai)
        <span class="badge bg-success px-3 py-2 fs-6"><i class="bi bi-check-circle me-1"></i> Telaah Selesai</span>
    @endif
</div>

@if($isSelesai)
    <div class="alert alert-warning border-0 shadow-sm mb-4">
        <i class="bi bi-lock-fill me-2"></i> Formulir ini sudah dikunci karena Anda telah menyelesaikan dan mengirimkan hasil telaah.
    </div>
@endif

<div class="card card-modern p-4 mb-4 bg-light border-0">
    <h6 class="fw-bold text-dark mb-2">Informasi & Petunjuk Pengisian[cite: 1]</h6>
    <p class="small text-muted mb-0">
        Daftar Tilik ini merupakan Catatan Telaah Protokol Penelitian untuk memperoleh Persetujuan Etik (7 Butir Standar Universal WHO 2011, 25 Pedoman CIOMS 2016, serta Pedoman KEPPKN 2018)[cite: 1]. Tim penelaah memberikan keputusan Setuju jika standar terpenuhi, atau Tidak Setuju jika belum terpenuhi[cite: 1].
    </p>
</div>

<form action="{{ route('penelaah.form7standar.simpan', $penugasan->id) }}" method="POST">
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
                'i1_2' => '1.2. Sebagai upaya mendesiminasikan hasil[cite: 1]',
                'i1_3' => '1.3. Relevansinya bermanfaat dengan masalah kesehatan[cite: 1]',
                'i1_4' => '1.4. Memberikan kontribusi promosi kesehatan[cite: 1]',
                'i1_5' => '1.5. Menghasilkan alternatif cara mengatasi masalah[cite: 1]',
                'i1_6' => '1.6. Menghasilkan data & informasi yang dapat dimanfaatkan untuk pengambilan keputusan klinis/sosial[cite: 1]',
                'i1_7' => '1.7. Terdapat uraian tentang penelitian lanjutan yang dapat dilakukan dari hasil penelitian yang sekarang[cite: 1]',
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
                
                // TAMBAHAN BAGIAN BAHAN BIOLOGIS / SPESIMEN
                'i2_bio_a' => 'Jika merupakan bahan biologis/spesimen sebagai subyek: a. Uraian mengenai penggunaan sampel spesimen yang akan dimasukkan, baik dalam penelitian saat ini dan dalam jangka panjang',
                'i2_bio_b' => 'b. Penjelasan apabila spesimen akan dikirimkan ke luar negeri atau berpindah dan dimanfaatkan oleh peneliti/pihak lain',
                'i2_bio_c' => 'c. Penjelasan lama spesimen akan disimpan dan cara spesimen akan dihancurkan; termasuk ketentuan untuk subjek dalam memutuskan penggunaan sisa spesimen dalam penelitian masa depan yang bersifat terbatas atau tidak spesifik',
                'i2_bio_d' => 'd. Penjelasan mengenai pengujian genetik / analisis genom yang akan dilakukan pada bahan biologis manusia',
                'i2_bio_e' => 'e. Terdapat penjelasan mengenai prosedur untuk mendapatkan sampel, baik rutin atau intervensi. Jika rutin, terdapat penjelasan bila prosedur merupakan prosedur yang lebih invasif daripada biasanya',

                // 2.2 DAN DAFTAR UJI KLINIK A SAMPAI N
                'i2_2a' => "Jika Intervensi/Penelitian uji klinik, maka:\n2.2. Peneliti harus memahami sepenuhnya kewajiban dan tanggung jawab yang dipersyaratkan dengan:\na. Memiliki sertifikat Etik Dasar Lanjut dan GCP",
                'i2_2b' => 'b. Mengisi dan menyerahkan daftar tilik GCP E6.4.1-13 yang telah ditandatangani peneliti tentang ringkasan tanggung jawab peneliti yang berkaitan dengan uji klinik kepada KEPK (tersedia di web sim-epk.keppkn.kemkes.go.id)',
                
                'i2_Ktr_a' => "Kontribusinya terhadap penciptaan atau evaluasi intervensi, harus memenuhi: semua atau antara lain\na. Terdapat ringkasan hasil penelitian sebelumnya sesuai topik penelitian yang diusulkan, baik yang belum dipublikasi/diketahui peneliti dan sponsor, dan sudah dipublikasi, termasuk kajian-kajian pada hewan",
                'i2_Ktr_b' => 'b. Terdapat gambaran singkat tentang lokasi penelitian, informasi demografis dan epidemiologis yang relevan tentang daerah penelitian, termasuk informasi ketersediaan fasilitas yang laik untuk keamanan dan ketepatan penelitian.',
                'i2_Ktr_c' => 'c. Terdapat deskripsi dan penjelasan semua intervensi (metode perlakuan), termasuk rute pemberian, dosis, interval dosis, dan masa perlakuan produk yang digunakan (investigasi dan pembanding)',
                'i2_Ktr_d' => 'd. Terdapat rencana dan justifikasi untuk meneruskan atau menghentikan standar terapi selama penelitian',
                'i2_Ktr_e' => 'e. Terdapat uraian jenis perlakuan/pengobatan lain yang mungkin diberikan atau diperbolehkan, atau menjadi kontraindikasi, selama penelitian',
                'i2_Ktr_f' => 'f. Terdapat penjelasan tentang pemeriksaan klinis/ non klinis yang harus dilakukan;',
                'i2_Ktr_g' => 'g. Terdapat format laporan kasus yang sudah terstandar, metode pencatatan respon terapetik (deskripsi dan evaluasi metode dan frekuensi pengukuran), prosedur tindak lanjut, dan, bila mungkin, ukuran yang diusulkan untuk menentukan tingkat kepatuhan subjek yang menerima perlakuan.',
                'i2_Ktr_h' => 'h. Terdapat aturan atau kriteria kapan subjek bisa diberhentikan dari penelitian atau uji klinis, atau, dalam hal studi multi senter, kapan sebuah pusat/lembaga di non-aktifkan, dan kapan penelitian bisa dihentikan (tidak lagi dilanjutkan)',
                'i2_Ktr_i' => 'i. Terdapat uraian tentang metode pencatatan dan pelaporan Adverse Events atau reaksi, dan syarat penanganan (jika terjadi) komplikasi',
                'i2_Ktr_j' => 'j. Terdapat uraian tentang risiko yang diketahui dari Adverse Events, termasuk risiko yang terkait dengan masing masing rencana intervensi, dan terkait dengan obat, vaksin, atau terhadap prosedur yang akan diujicobakan',
                'i2_Ktr_k' => 'k. Terdapat deskripsi tentang rencana analisis statistik, termasuk rencana analisis interim bila diperlukan, dan kreteria bila atau dalam kondisi bagaimana akan terjadi penghentian prematur keseluruhan penelitian',
                'i2_Ktr_l' => 'l. Terdapat rincian sumber dan jumlah dana riset; lembaga penyandang dana, dan pernyataan komitmen finansial sponsor pada kelembagaan penelitian, para peneliti, para subjek riset, dan, bila ada, pada komunitas',
                'i2_Ktr_m' => 'm. Terdapat dokumen pengaturan (financial disclosure) untuk mengatasi konflik finansial atau yang lainnya yang bisa mempengaruhi keputusan para peneliti atau personil lainya; peluang adanya konflik kepentingan (conflict of interest); dan langkah langkah berikutnya yang harus dilakukan',
                'i2_Ktr_n' => 'n. Terdapat penjelasan jika hasil riset negatif dan memastikan bahwa hasilnya tersedia melalui publikasi atau dengan melaporkan ke otoritas pencatatan obat obatan (regulator)',
            ],
            '3. Indikator Standar-3: Pemerataan Beban dan Manfaat' => [
                'i3_1' => '3.1. Tercantum uraian bahwa manfaat dan beban didistribusikan secara merata',
                'i3_2' => "3.2. Rekrutmen subjek dilakukan berdasarkan pertimbangan ilmiah, dan tidak berdasarkan status sosial ekonomi...\n- Terdapat rincian kriteria subjek dan alasan penentuan yang tidak masuk kriteria dari kelompok berdasarkan umur, sex, faktor sosial atau ekonomi[cite: 1]",
                'i3_3' => '3.3. Informasi dalam “media” perekrutan peserta (misalnya iklan, pemberitahuan, artikel media) disediakan dalam bahasa Inggris atau bahasa lokal[cite: 1]',
                'i3_4' => '3.4. Dalam memilih atau tidak memilih subjek tertentu, pertimbangkan kekhususan subjek sehingga perlu perlindungan khusus[cite: 1]',
                'i3_5' => '3.5. Kelompok subjek yang tidak mungkin memperoleh manfaat dari penelitian ini, dapat dipisahkan dari subjek lain, agar terhindar dari risiko dan beban yang sama[cite: 1]',
                'i3_6' => '3.6. Kelompok yang kurang terwakili dalam penelitian medis harus diberikan akses yg tepat untuk berpartisipasi[cite: 1]',
                'i3_7' => '3.7. Pembedaan distribusi beban dan manfaat juga dapat dipertimbangkan untuk dilakukan jika berkait dengan lokasi populasi[cite: 1]',
                'i3_8' => '3.8. Jumlah/proporsi subjek terpinggirkan dalam penelitian ini terwakili secara seimbang dengan kelompok lain[cite: 1]',
                'i3_9' => '3.9. Subjek terpilih menerima beban keikutsertaan dalam penelitian lebih besar dibanding peluang menikmati manfaat pengetahuan[cite: 1]',
                'i3_10' => '3.10. Kelompok rentan tidak dikeluarkan dari partisipasi dalam penelitian, meski bermaksud melindunginya; tetap diikutsertakan agar memperoleh manfaat secara proporsional[cite: 1]',
                'i3_11' => '3.11. Penelitian tidak memanfaatkan subjek secara berlebihan karena kemudahan memperoleh subjek (tahanan, mahasiswa peneliti, bawahan, dll)[cite: 1]',
                
                // TAMBAHAN SUB-POIN 3.11 HURUF A SAMPAI K
                'i3_11a' => 'a. Terdapat pernyataan yang jelas tentang pentingnya penelitian, pentingnya untuk pembangunan dan untuk memenuhi kebutuhan bangsa, khususnya penduduk/komunitas di lokasi penelitian[cite: 1]',
                'i3_11b' => 'b. Kriteria subjek dan alasan penentuan yang tidak masuk kriteria dari kelompok berdasarkan umur, sex, faktor sosial atau ekonomi, atau alasan lainnya[cite: 1]',
                'i3_11c' => 'c. Terdapat alasan melibatkan anak atau orang dewasa yang tidak bisa mandiri, atau kelompok rentan, serta langkah bagaimana memaksimalkan manfaat penelitian bagi mereka[cite: 1]',
                'i3_11d' => 'd. Terdapat rencana dan alasan untuk meneruskan atau menghentikan standar terapi selama penelitian, jika diperlukan termasuk jika tidak memberi manfaat kepada subjek dan populasi[cite: 1]',
                'i3_11e' => 'e. Terdapat penjelasan tentang perlakuan lain yang mungkin diberikan atau diperbolehkan, atau menjadi kontraindikasi, selama penelitian, sekaligus memberi manfaat bagi subjek karena adanya pengetahuan dan pengalaman itu[cite: 1]',
                'i3_11f' => 'f. Terdapat penjelasan tentang rencana pemeriksaan klinis atau pemeriksaan laboratorium lain yang harus dilakukan untuk mencapai tujuan penelitian sekaligus memberikan manfaat karena subjek memperoleh informasi kemajuan penyakit/kesehatannya[cite: 1]',
                'i3_11g' => 'g. Disertakan format laporan kasus yang sudah distandarisasi, metode pencatatan respon terapetik (deskripsi dan evaluasi metode dan frekuensi pengukuran), prosedur tindak lanjut, lengkap dengan manfaat yg diperoleh subjek[cite: 1]',
                'i3_11h' => 'h. Terdapat uraian tentang potensi manfaat/keuntungan dengan keikutsertaan dalam penelitian secara pribadi bagi subjek dan bagi yang lainnya[cite: 1]',
                'i3_11i' => 'i. Terdapat uraian keuntungan yang dapat diharapkan dari penelitian ini bagi penduduk, termasuk pengetahuan baru yang dapat dihasilkan[cite: 1]',
                'i3_11j' => 'j. Terdapat uraian kemungkinan dapat diberikan kelanjutan akses bila hasil intervensi menghasilkan manfaat yang signifikan, modalitas, pihak yang bertanggung jawab, dan untuk berapa lama[cite: 1]',
                'i3_11k' => 'k. Ketika penelitian melibatkan ibu hamil, ada penjelasan tentang adanya rencana untuk memonitor kesehatan ibu dan kesehatan anak dalam jangka pendek maupun jangka panjang[cite: 1]',
            ],
            '4. Indikator Standar-4: Potensi Manfaat dan Risiko' => [
                'i4_1' => '4.1. Terdapat uraian potensi manfaat penelitian yang lebih besar bagi individu/subjek[cite: 1]',
                'i4_2' => '4.2. Terdapat uraian risiko bahwa risiko sangat minimal yang didukung bukti intervensi setidaknya menguntungkan[cite: 1]',
                'i4_3' => '4.3. Tersedia uraian intervensi efektif (sesuai dengan golden standard) yang harus diberikan kepada kelompok intervensi dan kontrol[cite: 1]',
                'i4_4' => '4.4. Terdapat uraian tentang kerugian yang dapat dialami oleh subjek, tetapi hanya sedikit di atas ambang risiko minimal[cite: 1]',
                'i4_5' => '4.5. Terdapat uraian tentang tinggi rendahnya potensi risiko penelitian terhadap peneliti[cite: 1]',
                'i4_6' => '4.6. Terdapat uraian tentang kerugian yang dapat dialami oleh subjek; fisik, sosial, emosional, stigmatisasi, kehilangan privasi, pelecehan gender[cite: 1]',
                'i4_7' => '4.7. Terdapat uraian tentang tinggi rendahnya risiko penelitian terhadap kelompok/ masyarakat[cite: 1]',
                'i4_8' => '4.8. Terdapat simpulan agregat risiko dan manfaat dari keseluruhan penelitian[cite: 1]',
                'i4_9' => '4.9. Terdapat uraian tentang potensi risiko terhadap subjek, mengalami kerugian fisik, psikis, dan sosial yang lebih besar diatas risiko minimal[cite: 1]',
                'i4_10' => '4.10. Terdapat penjelasan tentang keuntungan yang diperoleh secara sosial dan ilmiah... dibandingkan potensi kerugian/risiko[cite: 1]',
                'i4_11' => '4.11. Terdapat brosur peneliti (termasuk informasi keselamatan) saat melibatkan obat-obatan baru atau vaksin[cite: 1]',
                'i4_12' => '4.12. Protokol mendeskripsikan manfaat yang diterima oleh komunitas asal subyek, selama dan paska penelitian[cite: 1]',
                'i4_13' => '4.13. Pada penelitian intervensi, terdapat informasi mengenai perlunya Komite Pemantauan Keamanan Data (DSMB/DMC)[cite: 1]',
                'i4_14' => '4.14. Protokol menjelaskan mengenai kemungkinan adanya kejadian buruk serius (Serious Adverse Event/SAE) dan mekanisme pelaporan[cite: 1]',
                'i4_15' => '4.15. Deskripsi mengenai ketentuan untuk menangani reaksi negatif yang terkait dengan penelitian serta temuan kebetulan[cite: 1]',
            ],
            '5. Indikator Standar-5: Bujukan / Eksploitasi / Undue Inducement' => [
                'i5_1' => '5.1. Terdapat penjelasan tentang insentif bagi subjek, dapat berupa material (uang, hadiah, layanan gratis) maupun non-material (kompensasi waktu, perjalanan)[cite: 1]',
                'i5_2' => '5.2. Insentif pada penelitian yang berisiko luka fisik, atau lebih berat, diuraikan lebih detail termasuk asuransi dan kompensasi disabilitas/kematian[cite: 1]',
                'i5_3' => '5.3. Terdapat uraian yang mengindikasikan adanya bujukan yang tidak semestinya, dan atau eksploitasi terhadap subyek[cite: 1]',
            ],
            '6. Indikator Standar-6: Rahasia dan Privacy' => [
                'i6_1' => '6.1. Meminta persetujuan baru ketika ada indikasi munculnya kejadian yang tidak diinginkan selama penelitian[cite: 1]',
                'i6_2' => '6.2. Peneliti mengharuskan subjek agar melakukan konsultasi lanjutan ketika peneliti menemukan indikasi penyakit serius[cite: 1]',
                'i6_3' => '6.3. Peneliti harus netral terhadap temuan baru, tidak memberikan pendapat tentang temuannya itu dan menyerahkan kepada ahlinya[cite: 1]',
                'i6_4' => '6.4. Peneliti menjaga kerahasiaan temuan tersebut, jika terpaksa maka peneliti membuka rahasia setelah menjelaskan kepada subjek[cite: 1]',
                'i6_a' => 'a. Terdapat penjelasan bagaimana peneliti menjaga privacy dan kerahasiaan subjek sejak rekruitmen hingga penelitian selesai[cite: 1]',
                'i6_b' => 'b. Terdapat penjelasan bagaimana peneliti menjaga privacy subjek ketika harus menjelaskan prosedur dan keikutsertaan[cite: 1]',
                'i6_c' => 'c. Terdapat penjelasan bagaimana peneliti akan tetap menjaga kerahasiaan dan privacy subjek meski subjek diwakili (alasan usia/budaya)[cite: 1]',
                'i6_d' => 'd. Terdapat penjelasan yang menunjukkan bahwa peneliti memahami beberapa data/informasi dimana kerahasiaan merupakan hal mutlak (misal: tes genetik)[cite: 1]',
                'i6_e' => 'e. Terdapat uraian tentang bagaimana peneliti membuat kode identitas subjek, tempat penyimpanan, dan akses darurat[cite: 1]',
                'i6_f' => 'f. Terdapat penjelasan tentang kemungkinan penggunaan data personal atau material biologis untuk penelitian lanjutan[cite: 1]',
                'i6_g' => 'g. Terdapat penjelasan jika hasil riset negatif dan memastikan tersedia melalui publikasi atau dilaporkan ke regulator[cite: 1]',
            ],
            '7. Indikator Standar-7: Informed Consent (Persetujuan Setelah Penjelasan)' => [
                'i7_1' => '7.1. Terdapat Lembar IC dengan penjelasan (PSP) yang akan disampaikan kepada partisipan[cite: 1]',
                'i7_2' => '7.2. Terdapat penjelasan proses mendapatkan persetujuan, mempergunakan prosedur yang laik[cite: 1]',
                'i7_3' => '7.3. Terdapat uraian dan penjelasan bagaimana subjek yang tidak mampu membaca, menulis (buta huruf) dengan saksi[cite: 1]',
                'i7_4' => '7.4. Terdapat ketentuan untuk peserta yang tidak mampu memberikan persetujuan pribadi (faktor budaya, anak-anak, gangguan mental)[cite: 1]',
                'i7_5' => '7.5. Bila subyek adalah komunitas, terdapat informasi yang diberikan pada komunitas dan tatacara pengambilan persetujuan individu[cite: 1]',
                'i7_6a' => 'a. Latar belakang, bahasa naskah mudah dipahami subjek[cite: 1]',
                'i7_6b' => 'b. Manfaat penelitian, yang dipahami subjek[cite: 1]',
                'i7_6c' => 'c. Perlakuan yang diterima subjek penelitian, jelas, tidak ada yang disembunyikan[cite: 1]',
                'i7_6d' => 'd. Lama keikutsertaan subjek dalam penelitian; jelas durasinya (minggu, hari, jam)[cite: 1]',
                'i7_6e' => 'e. Jenis dan lama wawancara (mendalam/FGD) yang direkam audio atau video[cite: 1]',
                'i7_6f' => 'f. Karakteristik subjek penelitian; jelas bahwa karakter subjek cocok[cite: 1]',
                'i7_6g' => 'g. Jumlah subjek penelitian yang diperlukan serta risiko jika subjek mengundurkan diri[cite: 1]',
                'i7_6h' => 'h. Kemungkinan risiko penelitian terhadap kesehatan subjek[cite: 1]',
                'i7_6i' => 'i. Jaminan kerahasiaan data, subjek memahami bahwa data dijaga kerahasiaannya[cite: 1]',
                'i7_6j' => 'j. Kompensasi yang diberikan kepada subjek (undue inducement ada/tidak)[cite: 1]',
                'i7_6k' => 'k. Unsur paksaan (coercient) ada atau tidak, dijelaskan secara transparan[cite: 1]',
                'i7_6l' => 'l. Penjelasan pengobatan medis dan ganti rugi apabila terjadi risiko sakit/gangguan kesehatan[cite: 1]',
                'i7_6m' => 'm. Nama jelas, nomor telepon penanggung jawab penelitian beserta nomor cadangan[cite: 1]',
                'i7_6n' => 'n. Nama jelas, no telepon penanggung jawab medis beserta alamat[cite: 1]',
                'i7_6o' => 'o. Hak mengundurkan diri sewaktu-waktu tanpa ada sanksi[cite: 1]',
                'i7_6p' => 'p. Kesediaan subjek atau wali yang sah (tanda tangan pada lembar PSP)[cite: 1]',
                'i7_6q' => 'q. Tanda tangan saksi[cite: 1]',
                'i7_6r' => 'r. Pilihan pengobatan/perlakuan selain yang disebut dalam penelitian[cite: 1]',
                'i7_7' => '7.7. Terdapat uraian mengenai prosedur untuk melindungi kerahasiaan data, tata cara penyampaian pada pihak yang relevan[cite: 1]',
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
                                <input class="form-check-input" type="radio" name="{{ $key }}" value="Ya" id="{{ $key }}_ya" {{ (isset($savedAnswers[$key]) && $savedAnswers[$key] == 'Ya') ? 'checked' : '' }} {{ $isSelesai ? 'disabled' : '' }}>
                                <label class="form-check-label text-success fw-bold" for="{{ $key }}_ya">Ya</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="{{ $key }}" value="Tidak" id="{{ $key }}_tidak" {{ (isset($savedAnswers[$key]) && $savedAnswers[$key] == 'Tidak') ? 'checked' : '' }} {{ $isSelesai ? 'disabled' : '' }}>
                                <label class="form-check-label text-danger fw-bold" for="{{ $key }}_tidak">Tidak</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="{{ $key }}" value="N/A" id="{{ $key }}_na" {{ (isset($savedAnswers[$key]) && $savedAnswers[$key] == 'N/A') ? 'checked' : '' }} {{ $isSelesai ? 'disabled' : '' }}>
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
        @if(!$isSelesai)
            <button type="submit" class="btn btn-primary btn-modern px-5 py-3 fs-5" style="background: #0d9488; border: none;">
                <i class="bi bi-save me-2"></i> Simpan Penilaian Formulir 7 Standar
            </button>
        @else
            <a href="{{ route('penelaah.telaah', $penugasan->id) }}" class="btn btn-primary btn-modern px-5 py-3 fs-5" style="background: #0d9488; border: none;">
                Lanjut ke Rekomendasi Akhir <i class="bi bi-arrow-right ms-2"></i>
            </a>
        @endif
    </div>
</form>
@endsection