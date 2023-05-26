<?php

return [
    "event_categories" => [
        "Technology", "Education", "Career Fair", "Health & Lifestyle", "Sport",
        "Conference", "Workshop", "Exhibition", "Music", "Festivals"
    ],
    "event_types" => [
        "Seminar", "Summit", "Conference", "Live Music & Concert", "Show & Festival", "Exhibition",
        "Anniversary", "Symposium", "Workshop", "Talkshow", "Competition", "Attraction"
    ],
    'event_topics' => [
        "Anak, Keluarga", "Bisnis", "Desain, Foto, Video",
        "Fashion, Kecantikan", "Film, Sinema", "Game, E-Sports", "Hobi, Kerajinan Tangan", "Investasi, Saham",
        "Karir, Pengembangan Diri", "Kesehatan, Kebugaran", "Keuangan, Finansial", "Lingkungan Hidup",
        "Lifestyle", "Makanan, Minuman", "Marketing", "Musik", "Olahraga", "Otomotif", "Sains, Teknologi", "Seni, Budaya",
        "Sosial, Hukum, Politik", "Standup Comedy", "Pendidikan, Beasiswa", "Teknologi, Startup",
        "Wisata, Liburan", "Lainnya"
    ],
    "organization_types" => [
        "Event Organizer", "Corporate", "Travel Agent", "University", "Wedding organizer",
        "Other"
    ],
    "organization_interests" => [
        "Seminar", "Exhibiition", "Conference", "Live Music",
        "Other"
    ],
    "sponsor_types" => [
        "Bronze Sponsor", "Silver Sponsor", "Gold Sponsor", "Platinum Sponsor"
    ],
    "media_partner_types" => [
        "Media Partner", "Social Media Partner", "Online Media Partner",
        "Hospitality Partner"
    ],
    // ================= SANDBOX / TEST MODE ======================
    "midtrans_config" => [
        "CLIENT_KEY" => "SB-Mid-client-ZGX5Z9E7g6GfWdNR",
        "SERVER_KEY" => "SB-Mid-server-d4Dv5k-5_6XU-NFnj33fgYhd",
        'main_url' => 'https://api.sandbox.midtrans.com/',
        'CURLOPT_HTTPHEADER' => array(
            'Accept: application/json',
            'Content-Type: application/json',
            // Autorization didapat dari "Basic" + base64encode(SERVER_KEY + ":")
            'Authorization: Basic U0ItTWlkLXNlcnZlci1kNER2NWstNV82WFUtTkZuajMzZmdZaGQ6'
        ),
        'isProduction' => false,
        'isSanitized' => true,
    ],

    // ================ PRODUCTION MODE ===========================
    // "midtrans_config" => [
    //     "CLIENT_KEY" => "Mid-client-fZYbNxPBE3JN9aBe",
    //     "SERVER_KEY" => "Mid-server-3ZcoeRx5krbRfle5jtzt7pQP",
    //     'main_url' => 'https://api.midtrans.com/',
    //     'CURLOPT_HTTPHEADER' => array(
    //         'Accept: application/json',
    //         'Content-Type: application/json',
    //         // Autorization didapat dari "Basic" + base64encode(SERVER_KEY + ":")
    //         'Authorization: Basic TWlkLXNlcnZlci0zWmNvZVJ4NWtyYlJmbGU1anR6dDdwUVA6'
    //     ),
    //     'isProduction' => true,
    //     'isSanitized' => true,
    // ],
    "min_transfer" => 10000,
    "email_operator" => 'halo@agendakota.id',
    "profit" => 0.025,
    "profit+" => 2500,
    "bank_list" => [
        'Bank Mandiri',
        'Bank Mandiri Syariah',
        'Bank Rakyat Indonesia (BRI)',
        'Bank BRI Syariah',
        'Bank Central Asia (BCA)',
        'Bank BCA Syariah',
        'Bank Negara Indonesia (BNI)',
        'Bank BNI Syariah',
        'Bank Tabungan Negara (BTN)',
        'Bank CIMB Niaga',
        'Bank OCBC NISP',
        'Panin Bank',
        'Bank Danamon',
        'Bank BTPN',
        'Bank BTPN Syariah',
        'Bank Bukopin',
        'Bank Bukopin Syariah',
        'Bank BJB Syariah',
        'MNC Bank'

    ],
];
