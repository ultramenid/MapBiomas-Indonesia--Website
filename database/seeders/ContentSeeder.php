<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Initiative;
use App\Models\NewsItem;
use App\Models\Page;
use App\Models\Partner;
use App\Models\TeamMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPages();
        $this->seedFaqs();
        $this->seedNews();
        $this->seedInitiatives();
        $this->seedPartners();
        $this->seedTeam();
    }

    private function seedPages(): void
    {
        // Only create if missing — never clobber live content on re-seed.
        Page::firstOrCreate(
            ['name' => 'about'],
            [
                'contentID' => '<p>MapBiomas Indonesia adalah bagian dari gerakan global MapBiomas Network untuk menghasilkan peta tutupan lahan dan penggunaan lahan tahunan Indonesia.</p>',
                'contentEN' => '<p>MapBiomas Indonesia is part of the global MapBiomas Network movement to produce annual land cover and land use maps of Indonesia.</p>',
            ]
        );
    }

    private function seedFaqs(): void
    {
        if (Faq::count() > 0) {
            return;
        }

        $faqs = [
            [
                'questionID' => 'Apa itu MapBiomas Indonesia?',
                'questionEN' => 'What is MapBiomas Indonesia?',
                'answerID' => '<p>MapBiomas Indonesia adalah inisiatif multi-lembaga yang menghasilkan peta tutupan lahan dan penggunaan lahan tahunan Indonesia berbasis citra satelit publik.</p>',
                'answerEN' => '<p>MapBiomas Indonesia is a multi-institution initiative producing annual land cover and land use maps of Indonesia based on public satellite imagery.</p>',
            ],
            [
                'questionID' => 'Apa saja platform yang dikelola MapBiomas Indonesia?',
                'questionEN' => 'Which platforms are managed by MapBiomas Indonesia?',
                'answerID' => '<p>Terdapat tiga platform: Landy (dinamika tutupan lahan), Fire (area terbakar), dan Alerta (alert deforestasi).</p>',
                'answerEN' => '<p>There are three platforms: Landy (land cover dynamics), Fire (burned areas), and Alerta (deforestation alerts).</p>',
            ],
            [
                'questionID' => 'Apakah data MapBiomas Indonesia gratis?',
                'questionEN' => 'Is MapBiomas Indonesia data free?',
                'answerID' => '<p>Ya, seluruh data dan peta dapat diakses secara terbuka dan gratis melalui platform masing-masing.</p>',
                'answerEN' => '<p>Yes, all data and maps are openly and freely accessible through each platform.</p>',
            ],
            [
                'questionID' => 'Bagaimana cara berkontribusi atau berkolaborasi?',
                'questionEN' => 'How can I contribute or collaborate?',
                'answerID' => '<p>Hubungi kami melalui mitra ko-kreator yang tercantum pada bagian bawah situs ini.</p>',
                'answerEN' => '<p>Reach out to us through any of the co-creator partners listed in the footer of this website.</p>',
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }

    private function seedNews(): void
    {
        if (NewsItem::count() > 0) {
            return;
        }

        $news = [
            [
                'type' => 'internal',
                'title_id' => 'Presentasi MapBiomas Indonesia kepada BRGM',
                'title_en' => 'MapBiomas Indonesia presentation to BRGM',
                'slug' => 'presentasi-mapbiomas-indonesia-kepada-brgm',
                'excerpt_id' => 'MapBiomas Indonesia menyampaikan bahwa land cover dynamics 3.0 akan memuat data dan peta berbagai kelas yang relevan dengan Badan Restorasi Gambut dan Mangrove.',
                'excerpt_en' => 'MapBiomas Indonesia conveyed that the land cover dynamics shown in Collection 3.0 will strengthen data and maps of land cover classes relevant to the Peatland and Mangrove Restoration Agency (BRGM).',
                'image_path' => 'assets/landy.jpeg',
                'content_id' => '<p>MapBiomas Indonesia menyampaikan bahwa land cover dynamics 3.0 akan memuat data dan peta berbagai kelas yang relevan dengan Badan Restorasi Gambut dan Mangrove (BRGM).</p><p>Koleksi terbaru ini memperluas cakupan kelas tutupan lahan agar semakin bermanfaat bagi agenda restorasi gambut dan mangrove di Indonesia.</p>',
                'content_en' => '<p>MapBiomas Indonesia conveyed that the land cover dynamics shown in Collection 3.0 will strengthen data and maps of land cover classes relevant to the Peatland and Mangrove Restoration Agency (BRGM).</p><p>This newest collection broadens the coverage of land cover classes to better support the peatland and mangrove restoration agenda in Indonesia.</p>',
                'published_at' => '2024-08-01',
            ],
            [
                'type' => 'external',
                'title_id' => 'Pelatihan Lanjutan Pemetaan Lahan Terbakar di IPAM Brasilia',
                'title_en' => 'Advanced Training in Burnt Land Mapping with IPAM in Brasilia',
                'slug' => 'pelatihan-lanjutan-pemetaan-lahan-terbakar-di-ipam-brasilia',
                'excerpt_id' => 'Pengetahuan yang diperoleh melalui pelatihan ini akan dikembangkan MapBiomas Indonesia membangun MapBiomas Indonesia | FIRE yang direncanakan rilis pada kuartal ketiga atau keempat 2024.',
                'excerpt_en' => 'MapBiomas Indonesia will apply knowledge secured through this training to develop MapBiomas Indonesia | FIRE, which is planned for release in the third or fourth quarter of 2024.',
                'image_path' => 'https://fire.mapbiomas.id/storage/files/photos/8AwhOdHFHoi2me7mkeDxtHTcXnUzQauMhh4EAVlT.jpg',
                'external_url' => 'https://fire.mapbiomas.id/id/news/2/pelatihan-lanjutan-pemetaan-lahan-terbakar-di-ipam-brasilia',
                'published_at' => '2024-04-15',
            ],
            [
                'type' => 'external',
                'title_id' => 'Belajar MapBiomas Alerta di Brasil',
                'title_en' => "Learning Brazil's MapBiomas Alerta",
                'slug' => 'belajar-mapbiomas-alerta-di-brasil',
                'excerpt_id' => 'Kunjungan ke São Paulo, Brazil, ini untuk mempelajari MapBiomas Alerta, sebuah platform pemantauan deforestasi di Brazil, sebagai referensi mengembangkan platform sejenis di Indonesia.',
                'excerpt_en' => 'The São Paulo, Brazil, trip was designed to learn MapBiomas Alerta, a deforestation monitoring platform in Brazil, as an effort to develop a similar platform in Indonesia.',
                'image_path' => 'https://landy.mapbiomas.id/storage/files/photos/pLrDqTjBhcfRxvPPhc5dF0jfPNUGUiPqgeCFXrpJ.jpg',
                'external_url' => 'https://landy.mapbiomas.id/id/news/8/belajar-mapbiomas-alerta-di-brasil',
                'published_at' => '2024-04-02',
            ],
            [
                'type' => 'internal',
                'title_id' => 'Peluncuran Platform MapBiomas Fire',
                'title_en' => 'Launch of the MapBiomas Fire Platform',
                'slug' => 'peluncuran-platform-mapbiomas-fire',
                'excerpt_id' => 'MapBiomas Fire resmi diluncurkan, menyajikan data dan peta area terbakar Indonesia berbasis Google Earth Engine dan deep learning.',
                'excerpt_en' => 'MapBiomas Fire is officially launched, presenting burned-area data and maps of Indonesia built on Google Earth Engine and deep learning.',
                'image_path' => 'assets/fire-launching.jpeg',
                'content_id' => '<p>MapBiomas Fire resmi diluncurkan sebagai platform pemetaan area terbakar di Indonesia. Platform ini mengoptimalkan Google Earth Engine dan pendekatan deep learning terhadap citra satelit terbuka.</p><p>Seluruh data dapat diakses gratis melalui fire.mapbiomas.id.</p>',
                'content_en' => '<p>MapBiomas Fire is officially launched as the burned-area mapping platform for Indonesia. It optimizes Google Earth Engine and deep learning approaches on open satellite imagery.</p><p>All data is freely accessible at fire.mapbiomas.id.</p>',
                'published_at' => '2024-06-20',
            ],
            [
                'type' => 'internal',
                'title_id' => 'Koleksi Baru Peta Tutupan Lahan Segera Rilis',
                'title_en' => 'New Land Cover Map Collection Coming Soon',
                'slug' => 'koleksi-baru-peta-tutupan-lahan-segera-rilis',
                'excerpt_id' => 'Koleksi terbaru MapBiomas Indonesia menghadirkan peta tutupan lahan dengan akurasi lebih tinggi dan cakupan kelas yang lebih lengkap.',
                'excerpt_en' => 'The newest MapBiomas Indonesia collection delivers land cover maps with higher accuracy and more complete class coverage.',
                'image_path' => 'assets/landy-hero.jpeg',
                'content_id' => '<p>Koleksi terbaru MapBiomas Indonesia sedang dalam tahap akhir persiapan. Koleksi ini menghadirkan peta tutupan lahan tahunan dengan akurasi lebih tinggi.</p>',
                'content_en' => '<p>The newest MapBiomas Indonesia collection is in its final preparation stage, bringing annual land cover maps with higher accuracy.</p>',
                'published_at' => '2025-01-10',
            ],
        ];

        foreach ($news as $item) {
            NewsItem::create($item);
        }
    }

    private function seedInitiatives(): void
    {
        if (Initiative::count() > 0) {
            return;
        }

        $initiatives = [
            [
                'name' => 'Landy',
                'slug' => 'landy',
                'logo_path' => 'assets/logo landy.png',
                'description_id' => 'Menampilkan dinamika tutupan lahan Indonesia. Statistik, peta, hingga transisi tutupan, pun penampalannya dengan tematik penguasaan lahan tersedia di platform ini.',
                'description_en' => 'Displaying land cover dynamics in Indonesia, this platform provides land cover maps, statistics and transitions, as well as overlays with land uses and concessions.',
                'platform_url' => 'https://landy.mapbiomas.id',
                'accent_color' => '#9EC4AB',
            ],
            [
                'name' => 'Fire',
                'slug' => 'fire',
                'logo_path' => 'assets/logo-fire.png',
                'description_id' => 'Menyajikan data dan peta area terbakar di Indonesia melalui pemaksimalkan Google Earth Engine dan pendekatan deep learning terhadap citra satelit yang terbuka bagi publik.',
                'description_en' => 'FIRE presents data and maps of burned areas in Indonesia by optimizing the Google Earth Engine and a deep learning approach to publicly available satellite imagery.',
                'platform_url' => 'https://fire.mapbiomas.id/id',
                'accent_color' => '#E17367',
            ],
            [
                'name' => 'Alerta',
                'slug' => 'alerta',
                'logo_path' => 'assets/logo-alerta.png',
                'description_id' => 'Setiap alert deforestasi diverifikasi, lalu divalidasi citra satelit resolusi tinggi terkini, sehingga tersaji data dan peta deforestasi aktual.',
                'description_en' => 'Every deforestation alert is verified, then validated through the latest high-resolution satellite imagery, to present authentic deforestation data and maps.',
                'platform_url' => 'https://plataforma.alerta.mapbiomas.id/',
                'accent_color' => '#772E20',
            ],
        ];

        foreach ($initiatives as $i => $initiative) {
            Initiative::create($initiative + ['sort' => $i]);
        }
    }

    private function seedPartners(): void
    {
        if (Partner::count() > 0) {
            return;
        }

        $coCreators = [
            ['Auriga', 'https://auriga.or.id/', 'assets/logos/AurigaPNG.png'],
            ['JERAT Papua', 'https://www.jeratpapua.org/', 'assets/logos/logo2_color.png'],
            ['Save Our Borneo', 'https://saveourborneo.org/', 'assets/logos/logo3_color.png'],
            ['Green of Borneo', 'http://greenofborneo.id/', 'assets/logos/logo4_color.png'],
            ['HAKA', 'https://www.haka.or.id/', 'assets/logos/logo5-x.png'],
            ['Mnuwar Papua', 'http://mnukwarpapua.id/', 'assets/logos/logo6-x.png'],
            ['Hutan Institute', 'https://hutaninstitute.or.id/', 'assets/logos/logo7-x.png'],
            ['Yayasan Genesis Bengkulu', 'http://yayasangenesisbengkulu.or.id/', 'assets/logos/logo8-x.png'],
            ['KOMIU', 'https://komiu.id/', 'assets/logos/logo9-x.png'],
            ['Sampan Kalimantan', 'http://sampankalimantan.id/', 'assets/logos/logo10-x.png'],
        ];

        foreach ($coCreators as $i => [$name, $url, $logo]) {
            Partner::create([
                'name' => $name,
                'url' => $url,
                'logo_path' => $logo,
                'category' => Partner::CATEGORY_COCREATOR,
                'sort' => $i,
            ]);
        }

        Partner::create([
            'name' => 'Woods Wayside',
            'url' => 'https://woods-wayside.org/',
            'logo_path' => 'assets/logos/logo12-x.png',
            'category' => Partner::CATEGORY_SUPPORTED,
            'sort' => 0,
        ]);
    }

    private function seedTeam(): void
    {
        if (TeamMember::count() > 0) {
            return;
        }

        $t = fn (string $name) => 'assets/tim/' . $name;

        $technical = [
            // Koordinator
            ['Timer Manurung', 'Timer.png', 'koordinator', null, 'Koordinator Umum', 'General Coordinator', ['landy' => [1, 2, 3, 4], 'fire' => [1]]],
            ['Dedy Sukmara', 'dd.png', 'koordinator', null, 'Koordinator Teknis', 'Technical Coordinator', ['landy' => [1, 2, 3, 4], 'fire' => [1]]],
            ['Age Kridalaksana', 'EBR07447_1.png', 'koordinator', null, 'Koordinator Teknis', 'Technical Coordinator', ['landy' => [1, 2, 3, 4], 'fire' => [1]]],
            // Tim Inti
            ['Reza Widyananto', 'rz.png', 'inti', null, null, null, ['landy' => [1, 2, 3], 'fire' => [1]]],
            ['Andhika Jounastya', 'dika.png', 'inti', null, null, null, ['landy' => [1, 2, 3, 4], 'fire' => [1]]],
            ['Adhitya Adhyaksa', 'adhit.png', 'inti', null, null, null, ['landy' => [1, 2]]],
            ['Willy Pratama', 'Willy 2.png', 'inti', null, null, null, ['landy' => [1]]],
            ['Yustinus Seno', 'EBR07454_1.png', 'inti', null, null, null, ['landy' => [1, 2, 3, 4], 'fire' => [1]]],
            ['M. Alichamdan', 'EBR07464_1.png', 'inti', null, null, null, ['landy' => [1, 2, 3, 4], 'fire' => [1]]],
            ['Cecilinia Tika Laura', 'EBR07456_1.png', 'inti', null, null, null, ['landy' => [2, 3]]],
            ['Bagus Sugiarto', 'bagus.png', 'inti', null, null, null, ['landy' => [2, 3, 4], 'fire' => [1]]],
            ['Sesilia Maharani Putri', 'sesil.png', 'inti', null, null, null, ['fire' => [1]]],
            ['M Dendi Alfitrah', 'EBR07460_1.png', 'inti', null, null, null, ['landy' => [3, 4], 'fire' => [1]]],
            ['Wahyu Ananta Nugraha', 'EBR07467_1.png', 'inti', null, null, null, ['landy' => [3, 4]]],
            ['Anggun D. Napitupulu', 'anggun.png', 'inti', null, null, null, ['landy' => [3, 4]]],
            ['Jumrio Nakul', 'EBR07451_1.png', 'inti', null, null, null, ['landy' => [1, 2, 3], 'fire' => [1]]],
            ['Robby', 'robby.png', 'inti', null, null, null, ['landy' => [1, 2, 3, 4], 'fire' => [1]]],
            ['Akhmad Kamaluddin', 'kamal.png', 'inti', null, null, null, ['landy' => [1]]],
            // Tim Regio
            ['Lukmanul Hakim', 'Lukman.png', 'regio', 'Sumatera', null, null, ['landy' => [2, 3, 4], 'fire' => [1]]],
            ['Khairul Amri', 'amri.png', 'regio', 'Sumatera', null, null, ['landy' => [1, 2, 3, 4], 'fire' => [1]]],
            ['Egi Ade Saputra', 'EBR07438_1.png', 'regio', 'Sumatera', null, null, ['landy' => [1, 2, 3, 4], 'fire' => [1]]],
            ['Berlian Pratama', 'Berlian 2.png', 'regio', 'Sumatera', null, null, ['landy' => [1]]],
            ['Bayu Prima', 'EBR07433_1.png', 'regio', 'Sumatera', null, null, ['landy' => [2, 3, 4], 'fire' => [1]]],
            ['Ivannalis Saputra', 'IvanNalis 2.png', 'regio', 'Kalimantan', null, null, ['landy' => [1]]],
            ['Arie Ferdian', 'Arie Fardian.png', 'regio', 'Kalimantan', null, null, ['landy' => [2, 3], 'fire' => [1]]],
            ['Muhammad Habibi', 'habibi.png', 'regio', 'Kalimantan', null, null, ['landy' => [1]]],
            ['Jahrul Husin', 'EBR07432_1.png', 'regio', 'Kalimantan', null, null, ['landy' => [2, 3, 4], 'fire' => [1]]],
            ['Roni Sutiono', 'EBR07444_1.png', 'regio', 'Kalimantan', null, null, ['landy' => [2, 3, 4], 'fire' => [1]]],
            ['Nelwan Krisna W.', 'EBR07446_1.png', 'regio', 'Kalimantan', null, null, ['landy' => [1, 2, 3, 4], 'fire' => [1]]],
            ['Ocsanto', 'EBR07439_1.png', 'regio', 'Sulawesi', null, null, ['landy' => [2, 3, 4], 'fire' => [1]]],
            ['Ari Dwi', 'Hari.png', 'regio', 'Papua', null, null, ['landy' => [1]]],
            ['Adrianus Anto R.', 'EBR07436_1.png', 'regio', 'Papua', null, null, ['landy' => [1, 2, 3, 4], 'fire' => [1]]],
            ['Yoram Dwaa', 'Yoram.png', 'regio', 'Papua', null, null, ['landy' => [1]]],
            ['Hendrikus Randongkir', 'hendrik 2.png', 'regio', 'Papua', null, null, ['landy' => [2, 3], 'fire' => [1]]],
            ['Sabatha Rumadas', 'Efren.png', 'regio', 'Papua', null, null, ['landy' => [2, 3], 'fire' => [1]]],
        ];

        foreach ($technical as $i => [$name, $photo, $group, $region, $posId, $posEn, $collections]) {
            TeamMember::create([
                'category' => TeamMember::CATEGORY_TECHNICAL,
                'team_group' => $group,
                'region' => $region,
                'name' => $name,
                'position_id' => $posId,
                'position_en' => $posEn,
                'photo_path' => $t($photo),
                'collections' => $collections,
                'sort' => $i,
            ]);
        }

        $scientific = [
            [
                'name' => 'Prof. Projo Danoedoro',
                'photo_path' => $t('Prof. Projo Danoedoro 1.png'),
                'bio_id' => 'Guru besar penginderaan jauh Fakultas Geografi Universitas Gadjah Mada ini aktif melakukan riset dan publikasi tulisan akademik mengenai pemetaan tutupan lahan Indonesia. Dapat dikontak melalui surat elektronik projo.danoedoro@geo.ugm.ac.id dan pdanoedoro@ugm.ac.id.',
                'bio_en' => 'Professor of remote sensing from Gadjah Mada University’s Faculty of Geography is active in conducting research and publishing academic papers on land cover mapping in Indonesia. He can be contacted via email at projo.danoedoro@geo.ugm.ac.id or pdanoedoro@ugm.ac.id.',
            ],
            [
                'name' => 'Dr. Arief Darmawan',
                'photo_path' => $t('Arif Dermawan 1.png'),
                'bio_id' => 'Dosen Jurusan Kehutanan Fakultas Pertanian Universitas Lampung ini beroleh gelar doktor dari The University of Tokyo. Dapat dikontak melalui surat elektronik arief.darmawan@gmail.com.',
                'bio_en' => 'Lecturer in forestry from the University of Lampung’s Faculty of Agriculture earned his doctorate from the University of Tokyo. He can be contacted via email at arief.darmawan@gmail.com.',
            ],
            [
                'name' => 'Dr. Nur Hygiawati Rahayu',
                'photo_path' => $t('Dr. Nur Hygiawati Rahayu.png'),
                'bio_id' => 'Pejabat struktural di BAPPENAS/Kementerian Perencanaan Pembangunan Nasional. Memperoleh gelar doktor dari University of Queensland. Dapat dikontak melalui surat elektronik nur.hrahayu@bappenas.go.id.',
                'bio_en' => 'Official with BAPPENAS/Ministry of National Development Planning earned her doctorate from the University of Queensland. She can be contacted via email at nur.hrahayu@bappenas.go.id.',
            ],
            [
                'name' => 'Dr. Hasriani Muis',
                'photo_path' => $t('Dr. Hasriani.png'),
                'bio_id' => 'Ahli sistem informasi geospasial ini merupakan dosen Fakultas Kehutanan Universitas Tadulako. Dapat dikontak melalui surat elektronik hasrianimuis@gmail.com.',
                'bio_en' => 'Geospatial information systems expert and lecturer with the Tadulako University Faculty of Forestry. She can be contacted via email at hasrianimuis@gmail.com.',
            ],
            [
                'name' => 'Francina Frenshegty Kesaulija',
                'photo_path' => $t('Francina Frenshegty Kesaulija.png'),
                'bio_id' => 'Dosen Fakultas Kehutanan Universitas Papua ini memperoleh gelar master dari Australian National University. Dapat dikontak melalui surat elektronik f.kesaulija@unipa.ac.id.',
                'bio_en' => 'Lecturer from the Papua University Faculty of Forestry earned a master’s degree from the Australian National University. She can be contacted via email at f.kesaulija@unipa.ac.id.',
            ],
        ];

        foreach ($scientific as $i => $member) {
            TeamMember::create($member + [
                'category' => TeamMember::CATEGORY_SCIENTIFIC,
                'sort' => $i,
            ]);
        }
    }
}
