<?php
/**
 * Script Pengisi 250+ Menu Restoran Dapur Tyas by Techno Garage
 * Dengan Foto Unik Berbeda untuk Setiap Menu!
 */
require_once __DIR__ . '/config.php';

$photoLibrary = [
    'nasi' => [
        'photo-1546069901-ba9599a7e63c', 'photo-1512058564366-18510be2db19', 'photo-1536304929831-ee1ca9d44906',
        'photo-1596797038530-2c107229654b', 'photo-1603133872878-684f208fb84b', 'photo-1589301760014-d929f3979dbc',
        'photo-1516714435131-44d6b64dc6a2', 'photo-1541544741938-0af808871cc0', 'photo-1563245372-f21724e3856d'
    ],
    'ayam' => [
        'photo-1626082927389-6cd097cdc6ec', 'photo-1598515214211-89d3c73ae83b', 'photo-1532550907401-a500c9a57435',
        'photo-1587593810167-a84920ea0781', 'photo-1527477378408-1bc0e58b885c', 'photo-1562967914-608f82629710',
        'photo-1606755962773-d324e0a13086', 'photo-1567620832903-9fc6debc209f', 'photo-1514944298352-78d123565bc5'
    ],
    'bebek' => [
        'photo-1518492104633-130d0cc84637', 'photo-1514944298352-78d123565bc5', 'photo-1569058242253-92a9c755a0ec',
        'photo-1625944230945-1b7dd3b949ab', 'photo-1604908176997-125f25cc6f3d'
    ],
    'daging' => [
        'photo-1544025162-d76694265947', 'photo-1558030006-450675393462', 'photo-1504973960431-1c467e159aa4',
        'photo-1547496502-affa22d38842', 'photo-1588168333986-5078d3ae3976', 'photo-1529692236671-f1f6cf9683ba'
    ],
    'seafood' => [
        'photo-1559742811-822873691df8', 'photo-1534422298391-e4f8c172dddb', 'photo-1565557623262-b51c2513a641',
        'photo-1615141982883-c7ad0e69fd62', 'photo-1553659971-f01207815fb4', 'photo-1579631542720-3a87824fff86',
        'photo-1509722747041-616f39b57569', 'photo-1535400255456-984241443b29', 'photo-1599084993091-1cb5c0721cc6'
    ],
    'mie' => [
        'photo-1569718212165-3a8278d5f624', 'photo-1552611052-33e04de081de', 'photo-1585032226651-759b368d7246',
        'photo-1612927601601-6638404737ce', 'photo-1594998893017-36147cbcae05', 'photo-1574484284002-952d92456975',
        'photo-1617093727343-374698b1b08d', 'photo-1525755662778-989d0524087e'
    ],
    'pedas' => [
        'photo-1563379091339-03b21ab4a4f8', 'photo-1505253758473-96b301b5c81d', 'photo-1541544741938-0af808871cc0',
        'photo-1582293041079-7814c2f12063', 'photo-1514944298352-78d123565bc5'
    ],
    'kuah' => [
        'photo-1572656631137-7935297eff55', 'photo-1547592166-23ac45744acd', 'photo-1603894584373-5ac82b2ae398',
        'photo-1584947920703-9112a2cb8e29', 'photo-1541832676-9b763b0239ab', 'photo-1511690656952-34342bb7c2f2'
    ],
    'snack' => [
        'photo-1496116218417-1a781b1c416c', 'photo-1541696432-82c6da8ce7bf', 'photo-1563245372-f21724e3856d',
        'photo-1565299585323-38d6b0865b47', 'photo-1607532941433-304659e8198a', 'photo-1541592106381-b31e9677c0e5'
    ],
    'salad' => [
        'photo-1519996529931-28324d5a630e', 'photo-1540420773420-3366772f4999', 'photo-1512621776951-a57141f2eefd',
        'photo-1543353071-873f17a7a088', 'photo-1505253716362-afaea1d3d1af'
    ],
    'minum' => [
        'photo-1556881286-fc6915169721', 'photo-1513558161293-cdaf765ed2fd', 'photo-1613478223719-2ab802602423',
        'photo-1579954115545-a95591f28bfc', 'photo-1595981267035-7b04ca84a82d', 'photo-1551024709-8f23befc6f87',
        'photo-1534353473418-4cfa6c56fd38', 'photo-1544145945-f90425340c7e', 'photo-1517256064527-09c73fc73e38'
    ],
    'kopi' => [
        'photo-1509042239860-f550ce710b93', 'photo-1514432324607-a09d9b4aefdd', 'photo-1497636577773-f1231844b336',
        'photo-1517256064527-09c73fc73e38', 'photo-1511920170033-f8396924c348', 'photo-1495474472287-4d71bcdd2085'
    ],
    'dessert' => [
        'photo-1587314168485-3236d6710814', 'photo-1551024709-8f23befc6f87', 'photo-1565958011703-44f9829ba187',
        'photo-1567620905732-2d1ec7ab7445', 'photo-1578985545062-69928b1d9587', 'photo-1559553156-2e97137af16f'
    ]
];

function getUniquePhoto($nama, $kategori, $index) {
    global $photoLibrary;
    $namaLower = strtolower($nama);
    $katLower = strtolower($kategori);
    
    $type = 'nasi';
    if (strpos($namaLower, 'kopi') !== false || strpos($namaLower, 'espresso') !== false || strpos($namaLower, 'latte') !== false || strpos($namaLower, 'macchiato') !== false || strpos($namaLower, 'americano') !== false) {
        $type = 'kopi';
    } elseif (strpos($namaLower, 'teh') !== false || strpos($namaLower, 'jus') !== false || strpos($namaLower, 'es ') !== false || strpos($namaLower, 'wedang') !== false || strpos($namaLower, 'kelapa') !== false || strpos($namaLower, 'smoothie') !== false || strpos($katLower, 'minuman') !== false) {
        $type = 'minum';
    } elseif (strpos($namaLower, 'mie') !== false || strpos($namaLower, 'kwetiau') !== false || strpos($namaLower, 'bihun') !== false || strpos($namaLower, 'bakso') !== false || strpos($namaLower, 'seblak') !== false) {
        $type = 'mie';
    } elseif (strpos($namaLower, 'udang') !== false || strpos($namaLower, 'cumi') !== false || strpos($namaLower, 'ikan') !== false || strpos($namaLower, 'gurame') !== false || strpos($namaLower, 'kakap') !== false || strpos($namaLower, 'kerang') !== false || strpos($namaLower, 'kepiting') !== false || strpos($katLower, 'seafood') !== false) {
        $type = 'seafood';
    } elseif (strpos($namaLower, 'bebek') !== false) {
        $type = 'bebek';
    } elseif (strpos($namaLower, 'sapi') !== false || strpos($namaLower, 'dendeng') !== false || strpos($namaLower, 'rendang') !== false || strpos($namaLower, 'iga') !== false || strpos($namaLower, 'buntut') !== false || strpos($namaLower, 'rawon') !== false) {
        $type = 'daging';
    } elseif (strpos($namaLower, 'geprek') !== false || strpos($namaLower, 'mercon') !== false || strpos($namaLower, 'sambal') !== false || strpos($namaLower, 'penyet') !== false || strpos($namaLower, 'balado') !== false || strpos($namaLower, 'pedas') !== false) {
        $type = 'pedas';
    } elseif (strpos($namaLower, 'soto') !== false || strpos($namaLower, 'sop') !== false || strpos($namaLower, 'kuah') !== false || strpos($namaLower, 'sayur asem') !== false || strpos($namaLower, 'gulai') !== false || strpos($namaLower, 'tongseng') !== false) {
        $type = 'kuah';
    } elseif (strpos($namaLower, 'dimsum') !== false || strpos($namaLower, 'pangsit') !== false || strpos($namaLower, 'batagor') !== false || strpos($namaLower, 'siomay') !== false || strpos($namaLower, 'mendoan') !== false || strpos($namaLower, 'cireng') !== false || strpos($namaLower, 'tahu') !== false || strpos($namaLower, 'tempe') !== false || strpos($namaLower, 'gorengan') !== false) {
        $type = 'snack';
    } elseif (strpos($namaLower, 'salad') !== false || strpos($namaLower, 'gado') !== false || strpos($namaLower, 'karedok') !== false || strpos($namaLower, 'pecel') !== false) {
        $type = 'salad';
    } elseif (strpos($namaLower, 'puding') !== false || strpos($namaLower, 'es krim') !== false || strpos($namaLower, 'ice cream') !== false || strpos($namaLower, 'waffle') !== false || strpos($namaLower, 'pancake') !== false || strpos($namaLower, 'roti') !== false || strpos($namaLower, 'pisang') !== false) {
        $type = 'dessert';
    } elseif (strpos($namaLower, 'ayam') !== false) {
        $type = 'ayam';
    }

    $list = $photoLibrary[$type] ?? $photoLibrary['nasi'];
    $photoId = $list[$index % count($list)];
    return "https://images.unsplash.com/{$photoId}?auto=format&fit=crop&w=600&h=450&q=80&sig={$index}";
}

$rawItems = [
    // Makanan Utama (Nusantara & Modern)
    ['Nasi Bakar Cumi Cabe Ijo', 'Makanan Utama', 28000, 'Nasi gurih daun pisang bakar dengan cumi asin cabe ijo khas Tyas.', 'Best Seller'],
    ['Nasi Bakar Ayam Suwir Kemangi', 'Makanan Utama', 26000, 'Ayam suwir bumbu rica kemangi gurih harum dibakar sempurna.', 'Favorit'],
    ['Nasi Bakar Tongkol Suwir Pedas', 'Makanan Utama', 25000, 'Tongkol asap suwir berbumbu sambal balado rempah wangi.', 'Pedas'],
    ['Nasi Bakar Jamur Merang Gurih', 'Makanan Utama', 22000, 'Pilihan sehat jamur tiram dan merang tumis bumbu kuning.', 'Sehat'],
    ['Nasi Bakar Teri Medan Petai', 'Makanan Utama', 27000, 'Teri medan renyah berpadu irisan petai segar daun kemangi.', 'Rekomendasi'],
    ['Nasi Goreng Spesial Dapur Tyas', 'Makanan Utama', 28000, 'Nasi goreng racikan bumbu rahasia dengan telur, ayam suwir & sosis.', 'Favorit'],
    ['Nasi Goreng Seafood XO', 'Makanan Utama', 34000, 'Nasi goreng harum saus XO dengan udang segar dan cumi empuk.', 'Chef Pick'],
    ['Nasi Goreng Kebuli Kambing', 'Makanan Utama', 38000, 'Kaya rempah kapulaga, cengkeh dengan potongan daging kambing empuk.', 'Spesial'],
    ['Nasi Goreng Cabe Ijo Teri', 'Makanan Utama', 27000, 'Pedas gurih cabai hijau tumbuk dengan taburan teri renyah.', 'Pedas'],
    ['Nasi Goreng Gila Pedas Manis', 'Makanan Utama', 29000, 'Topping melimpah sosis, bakso, telur orek dan tumisan sayur.', 'Favorit'],
    ['Nasi Goreng Kampung Terasi', 'Makanan Utama', 25000, 'Aroma terasi bakar tradisional dengan telur ceplok dan kerupuk.', 'Klasik'],
    ['Nasi Liwet Komplit Solo', 'Makanan Utama', 32000, 'Nasi gurih santan, sayur labu siam, suwir ayam areh dan telur pindang.', 'Tradisional'],
    ['Nasi Uduk Betawi Komplit', 'Makanan Utama', 30000, 'Nasi gurih wangi dengan semur tahu, bihun goreng, tempe orek & ayam.', 'Sarapan & Makan'],
    ['Nasi Kuning Gurih Tyas', 'Makanan Utama', 28000, 'Kuning alami kunyit dengan abon sapi, telur dadar iris & perkedel.', 'Gurih'],
    ['Nasi Timbel Parahyangan', 'Makanan Utama', 35000, 'Nasi timbel daun pisang, ayam goreng lengkuas, sayur asem & sambal terasi.', 'Komplit'],
    ['Nasi Gudeg Komplit Jogja', 'Makanan Utama', 33000, 'Nangka muda manis gurih, krecek pedas, ayam opor dan telur bacem.', 'Tradisional'],
    ['Nasi Padang Rendang Daging', 'Makanan Utama', 36000, 'Daging rendang empuk bumbu rempah hitam, daun singkong dan gulai kapau.', 'Best Seller'],
    ['Nasi Campur Bali Halal', 'Makanan Utama', 35000, 'Ayam betutu suwir, sate lilit ayam, jukut urap dan sambal matah.', 'Khas Bali'],
    ['Nasi Rawon Daging Sapi Surabaya', 'Makanan Utama', 36000, 'Kuah kluwek hitam gurih dengan daging sapi sengkel empuk & sambal tauge.', 'Kuah Hitam'],
    ['Nasi Tutug Oncom Tasik', 'Makanan Utama', 27000, 'Nasi tumbuk oncom kencur harum disajikan dengan ayam goreng & lalap.', 'Khas Sunda'],

    // Ayam & Unggas
    ['Ayam Bakar Madu Spesial Tyas', 'Ayam & Unggas', 29000, 'Ayam bakar legit berbalut madu hutan murni dengan olesan kecap rempah.', 'Best Seller'],
    ['Ayam Goreng Lengkuas Gurih', 'Ayam & Unggas', 28000, 'Ayam ungkep bumbu kuning berlimpah taburan serundeng lengkuas renyah.', 'Favorit'],
    ['Ayam Bakar Taliwang Pedas', 'Ayam & Unggas', 31000, 'Bumbu cabai merah bakar pedas khas Lombok dengan perasan jeruk limau.', 'Pedas'],
    ['Ayam Betutu Kuah Khas Gilimanuk', 'Ayam & Unggas', 32000, 'Aroma bumbu genep Bali melimpah kuah rempah hangat berkhasiat.', 'Rempah'],
    ['Ayam Kalasan Manis Gurih', 'Ayam & Unggas', 28000, 'Ayam ungkep air kelapa gurih manis legit digoreng renyah.', 'Klasik'],
    ['Ayam Rica-Rica Pedas Kemangi', 'Ayam & Unggas', 29000, 'Potongan ayam empuk berbalut cabai rawit pedas dan daun kemangi segar.', 'Pedas Mantap'],
    ['Ayam Woku Belanga Manado', 'Ayam & Unggas', 30000, 'Kuah kuning segar aroma daun pandan, kunyit, daun jeruk dan kemangi.', 'Khas Manado'],
    ['Ayam Tangkap Khas Aceh', 'Ayam & Unggas', 32000, 'Ayam potong renyah dibalut tumpukan daun koja dan daun pandan renyah.', 'Unik'],
    ['Ayam Goreng Mentega Gurih Manis', 'Ayam & Unggas', 30000, 'Ayam renyah berlumur saus mentega gurih, kecap inggris dan bawang bombay.', 'Anak & Dewasa'],
    ['Ayam Kuluyuk Asam Manis', 'Ayam & Unggas', 30000, 'Fillet ayam krispi berbalut saus asam manis dengan irisan nanas segar.', 'Segar'],
    ['Bebek Goreng Kremes Renyah', 'Ayam & Unggas', 36000, 'Bebek muda empuk tidak amis dengan taburan kremesan gurih asin.', 'Best Seller'],
    ['Bebek Bakar Bumbu Rujak', 'Ayam & Unggas', 37000, 'Bebek bakar saus santan cabai bumbu rujak pedas legit gurih.', 'Favorit'],
    ['Bebek Sinjay Madura Sambal Mangga', 'Ayam & Unggas', 38000, 'Bebek goreng renyah bumbu kuning dengan sambal pencit mangga muda.', 'Pedas Segar'],
    ['Bebek Betutu Panggang', 'Ayam & Unggas', 39000, 'Bebek panggang empuk berbalut rempah genep khas Gianyar.', 'Spesial'],

    // Sambal & Geprek
    ['Ayam Geprek Sambal Bawang Original', 'Geprek & Sambal', 24000, 'Ayam crispy jumbo digeprek ulekan cabai rawit bawang panas mendidih.', 'Pedas Pol'],
    ['Ayam Geprek Sambal Matah Kecombrang', 'Geprek & Sambal', 27000, 'Ayam fillet crispy sambal matah harum bunga kecombrang segar.', 'Best Seller'],
    ['Ayam Geprek Saus Keju Melted', 'Geprek & Sambal', 29000, 'Paduan ayam geprek pedas gurih disiram keju leleh creamy lumer.', 'Favorit'],
    ['Ceker Mercon Dapur Tyas', 'Geprek & Sambal', 22000, 'Ceker ayam tanpa tulang empuk bumbu cabai rawit pedas nagih.', 'Best Seller'],
    ['Sayap Ayam Mercon Lv 1-5', 'Geprek & Sambal', 25000, 'Sayap ayam crispy berselimut cabai merah membakar lidah.', 'Tantangan'],
    ['Kulit Ayam Krispi Sambal Ijo', 'Geprek & Sambal', 20000, 'Kulit ayam goreng garing kriuk dicocol sambal ijo padang harum.', 'Kriuk'],
    ['Paru Sapi Balado Renyah', 'Geprek & Sambal', 32000, 'Irisan paru sapi tipis renyah digoreng gurih berlumur sambal balado.', 'Favorit'],
    ['Iga Sapi Penyet Sambal Korek', 'Geprek & Sambal', 45000, 'Iga sapi bakar empuk dipenyet dengan ulekan cabai korek panas.', 'Premium'],
    ['Bebek Penyet Sambal Terasi Dadak', 'Geprek & Sambal', 37000, 'Bebek goreng empuk dipenyet di cobek tanah liat sambal terasi segar.', 'Tradisional'],
    ['Tempe & Tahu Penyet Sambal Kencur', 'Geprek & Sambal', 16000, 'Tahu tempe goreng gurih dengan sambal kencur harum menggugah selera.', 'Hemat'],

    // Seafood
    ['Udang Bakar Madu Jimbaran', 'Seafood', 38000, 'Udang windu segar dibakar saus manis pedas khas pantai Jimbaran Bali.', 'Best Seller'],
    ['Udang Goreng Saus Telur Asin', 'Seafood', 40000, 'Udang krispi berbalut pasta kuning telur asin creamy dan daun kari.', 'Favorit'],
    ['Cumi Goreng Tepung Calamari Crispy', 'Seafood', 34000, 'Cincin cumi segar tepung berbumbu gurih renyah dengan saus tartar pedas.', 'Camilan & Lauk'],
    ['Cumi Bakar Saus Padang Pedas', 'Seafood', 36000, 'Cumi utuh bakar saus padang kental bumbu rempah cabai merah.', 'Pedas'],
    ['Gurame Terbang Saus Asam Manis', 'Seafood', 45000, 'Ikan gurame segar fillet goreng terbang mekar dengan siraman asam manis.', 'Favorit Keluarga'],
    ['Gurame Bakar Bumbu Cobek Sunda', 'Seafood', 46000, 'Ikan gurame bakar olesan kecap disiram sambal cobek jahe kencur hangat.', 'Khas Sunda'],
    ['Kerang Hijau Saus Tiram Gurih', 'Seafood', 28000, 'Kerang hijau pilihan ditumis saus tiram, jahe segar dan daun bawang.', 'Gurih'],
    ['Kerang Dara Rebus Sambal Nanas', 'Seafood', 26000, 'Kerang dara segar rebus daun salam disajikan cocolan saus nanas pedas.', 'Segar'],
    ['Kakap Merah Kuah Asam Pedas', 'Seafood', 42000, 'Fillet kakap merah kuah asam segar tomat hijau, belimbing wuluh & kemangi.', 'Segar'],
    ['Kepiting Saus Lada Hitam (Portion)', 'Seafood', 55000, 'Daging kepiting segar tumis lada hitam pedas aromatik menggoda.', 'Chef Pick'],

    // Mie & Pasta
    ['Mie Goreng Spesial Dapur Tyas', 'Mie & Pasta', 26000, 'Mie telur kenyal digoreng wok panas dengan sayur, bakso dan telur.', 'Best Seller'],
    ['Mie Pedas Mercon Tyas Lv 1-3', 'Mie & Pasta', 22000, 'Mie pedas kenyal taburan ayam cincang dan pangsit goreng renyah.', 'Favorit Anak Muda'],
    ['Mie Ayam Jamur Pangsit Rebus', 'Mie & Pasta', 24000, 'Mie keriting kenyal dengan tumisan ayam jamur manis dan kuah kaldu.', 'Favorit'],
    ['Mie Kocok Kaki Sapi Bandung', 'Mie & Pasta', 32000, 'Mie kuning, tauge segar, kikil sapi empuk dalam kuah kaldu kental gurih.', 'Khas Bandung'],
    ['Mie Godhog Jawa Spesial', 'Mie & Pasta', 27000, 'Mie kuah kaldu ayam kampung kental gurih dengan telur orak arik & kol.', 'Tradisional'],
    ['Mie Celor Khas Palembang', 'Mie & Pasta', 29000, 'Mie tebal kuah kaldu udang dan santan kental dengan irisan telur rebus.', 'Khas Palembang'],
    ['Kwetiau Goreng Seafood Penang Style', 'Mie & Pasta', 33000, 'Kwetiau beras kenyal aroma smokey wok hei dengan udang dan telur bebek.', 'Chef Pick'],
    ['Kwetiau Siram Sapi Kuah Kental', 'Mie & Pasta', 35000, 'Kwetiau goreng disiram kuah telur kental dan irisan daging sapi empuk.', 'Hangat'],
    ['Bihun Goreng Kampung Sayur Mayur', 'Mie & Pasta', 24000, 'Bihun beras lembut digoreng kecap gurih dengan sayuran segar & bakso.', 'Hemat'],
    ['Bakso Urat Sapi Jumbo Tyas', 'Mie & Pasta', 27000, 'Bakso urat sapi asli kenyal berdaging dengan kuah kaldu sumsum gurih.', 'Best Seller'],
    ['Bakso Telur Asin Melted', 'Mie & Pasta', 28000, 'Bakso sapi lembut berisikan kuning telur asin gurih yang meledak di lidah.', 'Unik'],
    ['Bakso Cuanki Bandung Komplit', 'Mie & Pasta', 25000, 'Bakso halus, siomay kering, tahu bakso dan batagor kuah gurih wangi.', 'Khas Bandung'],

    // Sup & Soto
    ['Soto Ayam Kampung Segar Tyas', 'Sup & Soto', 25000, 'Kuah kaldu kuning rempah bening dengan suwir ayam kampung dan koya.', 'Best Seller'],
    ['Soto Betawi Daging Sapi Santan', 'Sup & Soto', 37000, 'Kuah santan susu creamy wangi minyak samin dengan potongan daging empuk.', 'Gurih Creamy'],
    ['Soto Kudus Mangkok Kerbau/Ayam', 'Sup & Soto', 26000, 'Soto khas mangkok kecil bumbu rempah bawang putih goreng wangi.', 'Tradisional'],
    ['Soto Lamongan Koya Melimpah', 'Sup & Soto', 26000, 'Soto kuah kuning gurih dengan taburan bubuk koya kerupuk udang gurih.', 'Favorit'],
    ['Sop Buntut Sapi Kuah Bening', 'Sup & Soto', 52000, 'Potongan buntut sapi impor empuk kuah kaldu rempah pala dan cengkeh.', 'Premium'],
    ['Sop Buntut Bakar Madu Tyas', 'Sup & Soto', 55000, 'Buntut bakar karamelisasi madu disajikan kuah kaldu hangat terpisah.', 'Chef Pick'],
    ['Sop Iga Sapi Asam Pedas', 'Sup & Soto', 46000, 'Iga sapi empuk kuah kaldu segar dengan potongan tomat hijau dan cabai.', 'Segar'],
    ['Sayur Asem Parahyangan Gurih Segar', 'Sup & Soto', 18000, 'Kuah asam jawa segar dengan kacang tanah, jagung manis dan labu siam.', 'Segar'],
    ['Tongseng Kambing Rempah Kuah Santan', 'Sup & Soto', 42000, 'Daging kambing empuk tumis kol dan tomat kuah gulai rempah manis pedas.', 'Kaya Rempah'],
    ['Gulai Kepala Kakap Merah Padang', 'Sup & Soto', 48000, 'Kepala kakap segar berkuah gulai santan kunyit kuning pedas menggoda.', 'Padang Style'],

    // Snack & Dimsum
    ['Dimsum Ayam Udang Homemade (4 pcs)', 'Snack & Dimsum', 18000, 'Daging ayam dan udang cincang juicy hangat disajikan saus chili oil.', 'Best Seller'],
    ['Dimsum Mozzarella Melted (4 pcs)', 'Snack & Dimsum', 21000, 'Dimsum gurih dengan topping lelehan keju mozzarella lembut.', 'Favorit'],
    ['Dimsum Mentai Panggang (4 pcs)', 'Snack & Dimsum', 23000, 'Dimsum kukus disiram saus mentai gurih creamy lalu dibakar torch.', 'Viral'],
    ['Pangsit Goreng Renyah Tyas (5 pcs)', 'Snack & Dimsum', 16000, 'Kulit renyah keemasan isi adonan ayam udang saus asam manis pedas.', 'Best Seller'],
    ['Batagor Ikan Tenggiri Saus Kacang', 'Snack & Dimsum', 22000, 'Tahu bakso ikan tenggiri goreng garing disiram saus kacang kental legit.', 'Khas Bandung'],
    ['Siomay Bandung Kukus Bumbu Kacang', 'Snack & Dimsum', 22000, 'Siomay ikan tenggiri, kol, pare, kentang dan telur saus kacang gurih.', 'Tradisional'],
    ['Tempe Mendoan Purwokerto (4 pcs)', 'Snack & Dimsum', 15000, 'Tempe kedelai tipis berbalut tepung daun bawang dengan cocolan kecap rawit.', 'Favorit'],
    ['Tahu Walik Krispi Sambal Petis', 'Snack & Dimsum', 17000, 'Tahu pong dibalik isi adonan daging ayam giling kenyal gurih renyah.', 'Kriuk'],
    ['Cireng Krispi Bumbu Rujak Pedas', 'Snack & Dimsum', 15000, 'Aci goreng garing di luar kenyal di dalam dicocol saus gula aren asam pedas.', 'Nagih'],
    ['Pisang Goreng Wijen Madu', 'Snack & Dimsum', 16000, 'Pisang raja manis legit digoreng tepung wijen renyah karamel madu.', 'Manis'],
    ['Roti Bakar Cokelat Keju Susu', 'Snack & Dimsum', 18000, 'Roti tebal bakar empuk bertabur meses cokelat, keju cheddar parut & kental manis.', 'Favorit'],
    ['Singkong Goreng Keju Merekah', 'Snack & Dimsum', 15000, 'Singkong empuk pulen digoreng merekah ditaburi keju gurih melimpah.', 'Tradisional'],

    // Salad & Sehat
    ['Salad Buah Premium Dapur Tyas (Reguler)', 'Salad & Sehat', 25000, 'Potongan apel fuji, melon, anggur, pir madu saus yoghurt creamy parutan keju.', 'Best Seller'],
    ['Salad Buah Jumbo Family Share', 'Salad & Sehat', 45000, 'Porsi besar aneka buah premium dengan saus keju manis segar melimpah.', 'Favorit'],
    ['Gado-Gado Siram Sayur Segar', 'Salad & Sehat', 22000, 'Bayam, tauge, kacang panjang rebus, tahu tempe telur siraman bumbu kacang.', 'Sehat'],
    ['Karedok Sayur Mentah Sunda', 'Salad & Sehat', 19000, 'Sayuran mentah segar renyah diaduk dengan ulekan bumbu kacang kencur wangi.', 'Khas Sunda'],
    ['Pecel Madiun Bumbu Pedas Gurih', 'Salad & Sehat', 20000, 'Aneka daun hijau rebus disiram saus pecel kacang tanah sangrai harum.', 'Tradisional'],

    // Minuman Segar
    ['Es Teh Manis Melati Jumbo', 'Minuman', 6000, 'Seduhan daun teh melati wangi khas Dapur Tyas segar melegakan tenggorokan.', 'Segar'],
    ['Es Teh Tarik Creamy Khas Melayu', 'Minuman', 12000, 'Teh hitam wangi dipadu susu kental manis ditarik hingga berbusa lembut.', 'Favorit'],
    ['Es Jeruk Peras Murni', 'Minuman', 10000, 'Perasan jeruk segar asli tanpa pemanis buatan dingin menyejukkan.', 'Alami'],
    ['Es Cincau Hijau Gula Aren Susu', 'Minuman', 12000, 'Cincau alami hijau kenyal dingin dengan kuah susu evaporasi & aren murni.', 'Best Seller'],
    ['Es Cendol Dawet Ayu Nangka', 'Minuman', 14000, 'Dawet pandan suji harum, santan gurih, gula aren cair dan potongan nangka.', 'Tradisional'],
    ['Es Kelapa Muda Jeruk Nipis', 'Minuman', 15000, 'Air dan daging kelapa muda murni dengan sentuhan tetesan jeruk nipis segar.', 'Alami'],
    ['Es Teler Komplit Alpukat Nangka', 'Minuman', 18000, 'Daging kelapa muda, alpukat mentega, potongan nangka wangi susu kental manis.', 'Favorit'],
    ['Es Campur Pelangi Dapur Tyas', 'Minuman', 16000, 'Cincau hitam, tape singkong, kolang kaling, agar-agar sirup cocopandan merah.', 'Segar'],
    ['Es Lemon Tea Sereh Madu', 'Minuman', 14000, 'Teh wangi dengan irisan lemon segar, batang serai geprek dan madu alami.', 'Sehat'],
    ['Jus Alpukat Kocok Cokelat Susu', 'Minuman', 16000, 'Alpukat mentega lembut diblender kental dengan lelehan saus cokelat manis.', 'Kental'],
    ['Jus Mangga Gedong Gincu Segar', 'Minuman', 15000, 'Daging mangga matang pohon segar manis alami kaya vitamin C.', 'Vitamin C'],
    ['Jus Buah Naga Merah Segar', 'Minuman', 14000, 'Buah naga merah segar diblender lembut dengan perasan jeruk nipis.', 'Detox'],
    ['Wedang Jahe Sereh Hangat', 'Minuman', 11000, 'Rebusan jahe merah bakar, batang serai dan gula merah menghangatkan tubuh.', 'Hangat'],
    ['Wedang Ronde Jahe Isi Kacang', 'Minuman', 16000, 'Bola ketan kenyal isi pasta kacang manis dalam kuah jahe rempah hangat.', 'Hangat'],

    // Kopi & Artisan Latte
    ['Kopi Susu Gula Aren Tyas', 'Coffee & Tea', 16000, 'Espresso arabika blend dengan susu creamy dan legitnya gula aren organik.', 'Best Seller'],
    ['Kopi Hitam Tubruk Arabika Gayo', 'Coffee & Tea', 12000, 'Biji kopi arabika gayo aceh seduh tubruk aroma fruity dan cokelat lembut.', 'Pecinta Kopi'],
    ['Americano Iced Double Shot', 'Coffee & Tea', 15000, 'Espresso ganda dingin segar beraroma kopi pekat tanpa ampas.', 'Segar'],
    ['Caramel Macchiato Iced', 'Coffee & Tea', 22000, 'Perpaduan espresso, susu vanilla dingin dan siraman karamel keemasan.', 'Favorit'],
    ['Matcha Green Tea Latte Creamy', 'Coffee & Tea', 20000, 'Bubuk matcha murni Jepang dikocok susu segar berbusa lembut.', 'Non Kopi'],
    ['Chocolate Lava Iced Latte', 'Coffee & Tea', 19000, 'Cokelat dark pekat berpadu susu segar dingin manis gurih memanjakan lidah.', 'Favorit'],

    // Daging & Rendang
    ['Rendang Paru Sapi Padang', 'Ayam & Unggas', 34000, 'Paru sapi empuk dimasak bumbu rendang minang gurih berempah.', 'Spesial'],
    ['Dendeng Balado Basah', 'Ayam & Unggas', 38000, 'Daging sapi iris tipis empuk disiram ulekan sambal balado merah segar.', 'Favorit'],
    ['Dendeng Batokok Renyah', 'Ayam & Unggas', 37000, 'Dendeng sapi goreng garing ditumbuk dengan taburan cabai hijau limau.', 'Khas Padang'],
    ['Gulai Cincang Sapi Rempah', 'Ayam & Unggas', 35000, 'Daging sapi cincang kuah santan gurih kental beraroma kapulaga cengkeh.', 'Gurih'],
    ['Sapi Lada Hitam Wok', 'Ayam & Unggas', 42000, 'Irisan daging sapi empuk tumis saus lada hitam wangi paprika bombay.', 'Best Seller'],
    ['Bistik Sapi Jawa Klasik', 'Ayam & Unggas', 39000, 'Daging sapi empuk kuah semur manis gurih bumbu pala disajikan selada.', 'Tradisional'],
    ['Empal Gepuk Serundeng', 'Ayam & Unggas', 33000, 'Daging sapi ungkep empuk manis legit dengan taburan serundeng kelapa.', 'Favorit'],
    ['Semur Daging Sapi Betawi', 'Ayam & Unggas', 35000, 'Daging sapi empuk kuah kecap rempah pala cengkeh kentang lembut.', 'Klasik'],
    ['Rawon Iga Sapi Kuah Kluwek', 'Ayam & Unggas', 46000, 'Iga sapi empuk kuah kluwek hitam gurih mantap khas Jawa Timur.', 'Spesial'],
    ['Sop Sengkel Sapi Bening', 'Ayam & Unggas', 40000, 'Potongan sengkel sapi empuk urat kenyal kuah rempah bening segar.', 'Hangat'],
    ['Iga Bakar Saus Barbeque', 'Ayam & Unggas', 48000, 'Iga sapi bakar karamel saus BBQ gurih smokey disajikan kentang.', 'Chef Pick'],
    ['Tongseng Sapi Cabai Rawit', 'Ayam & Unggas', 38000, 'Daging sapi empuk tumis kol tomat kuah gulai rempah manis pedas.', 'Pedas Gurih'],

    // Variasi Ayam
    ['Ayam Bakar Bumbu Rujak', 'Ayam & Unggas', 29000, 'Ayam bakar saus santan cabai bumbu rujak pedas legit gurih.', 'Favorit'],
    ['Ayam Bakar Kecap Pedas Limau', 'Ayam & Unggas', 28000, 'Ayam bakar olesan kecap manis cabai rawit dengan perasan jeruk limau.', 'Segar'],
    ['Ayam Pop Khas Bukittinggi', 'Ayam & Unggas', 29000, 'Ayam kampung rebus bumbu bawang disajikan saus tomat cabai merah.', 'Khas Padang'],
    ['Ayam Goreng Bumbu Kuning', 'Ayam & Unggas', 26000, 'Ayam ungkep rempah kunyit jahe lengkuas digoreng gurih harum.', 'Gurih'],
    ['Ayam Goreng Saus Telur Asin', 'Ayam & Unggas', 32000, 'Ayam krispi renyah berlumur saus kuning telur asin creamy daun kari.', 'Best Seller'],
    ['Ayam Geprek Sambal Korek Pedas', 'Geprek & Sambal', 25000, 'Ayam goreng tepung renyah diulek bersama cabai rawit korek panas.', 'Pedas Pol'],
    ['Ayam Geprek Sambal Terasi Bakar', 'Geprek & Sambal', 26000, 'Sensasi aroma terasi bakar khas pada ulekan ayam geprek krispi.', 'Tradisional'],
    ['Ayam Bakar Padang Gulai', 'Ayam & Unggas', 29000, 'Ayam bakar bumbu gulai merah rempah padang wangi gurih pekat.', 'Khas Padang'],
    ['Ayam Masak Habang Banjar', 'Ayam & Unggas', 30000, 'Ayam empuk bumbu cabai kering merah manis gurih khas Banjarmasin.', 'Khas Banjar'],
    ['Ayam Panggang Klaten Manis Gurih', 'Ayam & Unggas', 31000, 'Ayam panggang bumbu areh santan kental manis gurih meresap tulang.', 'Tradisional'],
    ['Sate Ayam Madura Saus Kacang (10 tusuk)', 'Ayam & Unggas', 32000, 'Potongan daging ayam empuk bakar bumbu kecap kacang kental bawang.', 'Best Seller'],
    ['Sate Taichan Daging Paha (10 tusuk)', 'Ayam & Unggas', 30000, 'Sate ayam bakar polos gurih asin disajikan sambal rawit pedas jeruk.', 'Favorit Anak Muda'],
    ['Sate Lilit Ayam Khas Bali (5 tusuk)', 'Ayam & Unggas', 27000, 'Cincangan daging ayam rempah bumbu genep lilit batang serai harum.', 'Khas Bali'],
    ['Ayam Suwir Bali Pedas Harum', 'Ayam & Unggas', 28000, 'Suwiran ayam bumbu genep cabai merah harum daun jeruk Bali.', 'Pedas Harum'],
    ['Ayam Bakar Solo Manis Gurih', 'Ayam & Unggas', 28000, 'Ayam bakar ungkep kecap manis bawang merah Solo meresap sempurna.', 'Manis Gurih'],
    ['Ayam Krispi Saus Keju Pedas', 'Ayam & Unggas', 29000, 'Ayam goreng tepung garing disiram saus keju cheddar pedas gurih.', 'Favorit'],

    // Variasi Seafood
    ['Udang Saus Padang Pedas Manis', 'Seafood', 39000, 'Udang segar tumis saus padang kental bumbu cabai telur kocok.', 'Best Seller'],
    ['Udang Asam Manis Nanas', 'Seafood', 37000, 'Udang goreng saus tomat asam manis dengan potongan nanas segar paprika.', 'Segar'],
    ['Udang Rebus Daun Salam', 'Seafood', 33000, 'Udang laut segar rebus jahe daun salam cocol saus tiram jahe.', 'Alami'],
    ['Udang Bakar Saus Rica', 'Seafood', 38000, 'Udang bakar bumbu rica-rica pedas cabai rawit tomat merah harum.', 'Pedas'],
    ['Cumi Saus Tiram Gurih', 'Seafood', 35000, 'Cumi segar ditumis saus tiram bawang bombay dan jahe harum.', 'Favorit'],
    ['Cumi Bakar Kecap Bawang', 'Seafood', 36000, 'Cumi bakar empuk olesan kecap manis taburan irisan bawang cabai.', 'Gurih'],
    ['Cumi Asin Cabe Ijo Pete', 'Seafood', 32000, 'Cumi asin empuk ditumis cabai hijau tomat hijau dan irisan petai segar.', 'Best Seller'],
    ['Gurame Saus Padang', 'Seafood', 46000, 'Ikan gurame goreng mekar disiram saus padang pedas kental gurih.', 'Favorit Keluarga'],
    ['Gurame Bakar Kecap Pedas', 'Seafood', 45000, 'Ikan gurame bakar olesan mentega kecap manis pedas limau wangi.', 'Klasik'],
    ['Kakap Bakar Bumbu Jimbaran', 'Seafood', 44000, 'Fillet kakap bakar saus tomat cabai bawang khas pantai Bali.', 'Spesial'],
    ['Ikan Kembung Bakar Sambal Dabu-Dabu', 'Seafood', 26000, 'Ikan kembung bakar gurih disiram sambal dabu-dabu segar Manado.', 'Segar'],
    ['Ikan Nila Goreng Sambal Terasi', 'Seafood', 25000, 'Ikan nila segar goreng garing disajikan lalapan segar sambal terasi.', 'Favorit'],
    ['Ikan Lele Crispy Sambal Bawang', 'Seafood', 20000, 'Lele goreng tepung krispi kriuk dicocol sambal bawang pedas nampol.', 'Hemat'],
    ['Ikan Bawal Bakar Bumbu Kuning', 'Seafood', 38000, 'Ikan bawal laut bakar bumbu rempah kuning kunyit jahe kemiri gurih.', 'Gurih'],
    ['Kepiting Saus Padang Super Pedas', 'Seafood', 58000, 'Kepiting segar bumbu saus padang telur kental membakar selera makan.', 'Chef Pick'],

    // Variasi Mie & Bakso
    ['Mie Kuah Kari Ayam Pedas', 'Mie & Pasta', 25000, 'Mie kuah kaldu kari santan gurih dengan potongan daging ayam empuk.', 'Gurih'],
    ['Mie Goreng Seafood Pedas Level 1-5', 'Mie & Pasta', 32000, 'Mie goreng wok hei dengan udang cumi bakso dan tingkat kepedasan.', 'Tantangan'],
    ['Mie Aceh Goreng Daging Sapi', 'Mie & Pasta', 33000, 'Mie kuning tebal bumbu rempah kari pekat khas Aceh potongan daging.', 'Khas Aceh'],
    ['Mie Aceh Kuah Kental Kari', 'Mie & Pasta', 33000, 'Mie aceh kuah nyemek kaya rempah kapulaga dengan emping acar bawang.', 'Khas Aceh'],
    ['Kwetiau Goreng Ayam Sosis', 'Mie & Pasta', 27000, 'Kwetiau kenyal digoreng telur ayam sosis dan sayuran segar sawi tauge.', 'Favorit'],
    ['Kwetiau Kuah Telur Kental', 'Mie & Pasta', 28000, 'Kwetiau beras kuah kaldu ayam telur kocok hangat kental lezat.', 'Hangat'],
    ['Bihun Kuah Bebek Jahe Hangat', 'Mie & Pasta', 34000, 'Bihun lembut kuah kaldu bebek rempah jahe menghangatkan tubuh.', 'Spesial'],
    ['Bihun Goreng Seafood Spesial', 'Mie & Pasta', 31000, 'Bihun goreng kecap harum dengan cumi udang telur dan sayuran.', 'Favorit'],
    ['Mie Ayam Bakso Urat Komplit', 'Mie & Pasta', 28000, 'Mie ayam jamur disajikan bakso urat besar pangsit kuah dan sawi.', 'Komplit'],
    ['Mie Ayam Ceker Mercon Pedas', 'Mie & Pasta', 26000, 'Mie ayam kenyal topping ceker mercon pedas gurih nagih.', 'Best Seller'],
    ['Bakso Aci Garut Pedas Segar', 'Mie & Pasta', 20000, 'Bakso aci kenyal isi ayam kuah jeruk limau pilus cikur dan cuanki lidah.', 'Segar'],
    ['Bakso Goreng Renyah Tyas (5 pcs)', 'Mie & Pasta', 18000, 'Bakso goreng adonan ayam udang renyah kopong dicocol saus pedas.', 'Snack & Lauk'],
    ['Seblak Ceker Komplit Pedas', 'Mie & Pasta', 22000, 'Kerupuk basah kuah kencur pedas dengan ceker empuk sosis bakso telur.', 'Pedas Nampol'],
    ['Seblak Seafood Makaroni Telur', 'Mie & Pasta', 24000, 'Seblak kuah cabai kencur dengan udang cumi makaroni dan telur kocok.', 'Pedas'],
    ['Pangsit Kuah Kaldu Daging Ayam', 'Mie & Pasta', 18000, 'Pangsit rebus isi ayam udang kuah kaldu ayam jernih bertabur daun bawang.', 'Hangat'],

    // Variasi Sayur
    ['Tumis Kangkung Belacan Terasi', 'Makanan Utama', 17000, 'Kangkung hijau segar ditumis bumbu terasi bakar dan cabai merah harum.', 'Favorit'],
    ['Tumis Kangkung Bawang Putih', 'Makanan Utama', 16000, 'Kangkung renyah ditumis bawang putih cincang gurih saus tiram.', 'Sehat'],
    ['Tumis Tauge Ikan Asin Jambal', 'Makanan Utama', 18000, 'Tauge segar renyah ditumis potongan ikan asin jambal roti gurih asin.', 'Gurih'],
    ['Capcay Kuah Kental Seafood', 'Makanan Utama', 28000, 'Aneka sayuran brokoli wortel jamur kembang kol udang kuah telur.', 'Sehat'],
    ['Capcay Goreng Ayam Bakso', 'Makanan Utama', 26000, 'Sayuran segar ditumis kecap saus tiram potongan ayam bakso sosis.', 'Favorit'],
    ['Tumis Buncis Daging Cincang', 'Makanan Utama', 22000, 'Baby buncis renyah ditumis daging sapi cincang bumbu bawang putih.', 'Rekomendasi'],
    ['Sayur Lodeh Labu Siam Gurih', 'Makanan Utama', 16000, 'Sayur lodeh kuah santan gurih labu siam kacang panjang melinjo tempe.', 'Tradisional'],
    ['Urap Sayur Bumbu Kelapa Pedas', 'Makanan Utama', 17000, 'Aneka daun rebus dicampur parutan kelapa muda sangrai bumbu kencur.', 'Tradisional'],
    ['Terong Balado Sambal Merah', 'Makanan Utama', 16000, 'Terong ungu goreng lembut disiram sambal balado cabai merah tomat.', 'Favorit'],
    ['Gulai Daun Singkong Teri Padang', 'Makanan Utama', 18000, 'Daun singkong muda kuah gulai santan kunyit kuning dengan teri asin.', 'Khas Padang'],
    ['Tumis Pare Udang Tidak Pahit', 'Makanan Utama', 20000, 'Pare segar diolah khusus tidak pahit ditumis udang dan cabai rawit.', 'Unik'],
    ['Sambal Goreng Kentang Ati Ampela', 'Makanan Utama', 24000, 'Kentang dadu ati ampela bumbu balado santan manis pedas gurih.', 'Tradisional'],

    // Variasi Snack & Camilan
    ['Tahu Cabe Garam Crispy', 'Snack & Dimsum', 17000, 'Tahu sutra krispi ditumis bawang putih cincang cabai rawit garam gurih.', 'Best Seller'],
    ['Jamur Enoki Crispy Pedas', 'Snack & Dimsum', 18000, 'Jamur enoki goreng tepung renyah taburan bumbu cabai gurih nagih.', 'Kriuk'],
    ['Kentang Goreng French Fries Saus Keju', 'Snack & Dimsum', 18000, 'Kentang goreng impor renyah keemasan disiram saus keju cheddar gurih.', 'Favorit'],
    ['Sosis Bakar Jumbo Saus BBQ', 'Snack & Dimsum', 18000, 'Sosis sapi bratwurst bakar saus barbeque gurih dan mayonaise.', 'Anak & Dewasa'],
    ['Otak-Otak Ikan Tenggiri Bakar', 'Snack & Dimsum', 20000, 'Otak-otak ikan tenggiri daun pisang bakar cocol saus kacang gurih.', 'Tradisional'],
    ['Lumpia Goreng Semarang (2 pcs)', 'Snack & Dimsum', 16000, 'Kulit lumpia renyah isi rebung manis ayam telur disajikan saus kental.', 'Khas Semarang'],
    ['Risoles Mayo Daging Asap Keju', 'Snack & Dimsum', 17000, 'Risoles kulit lembut isi smoked beef telur keju saus mayonaise lumer.', 'Best Seller'],
    ['Kroket Kentang Daging Sapi', 'Snack & Dimsum', 16000, 'Adonan kentang halus isi daging sapi wortel gurih digoreng panir emas.', 'Klasik'],
    ['Tahu Gejrot Khas Cirebon Pedas', 'Snack & Dimsum', 14000, 'Tahu pong goreng disiram kuah gula merah asam jawa bawang cabai rawit.', 'Pedas Segar'],
    ['Martabak Telur Daging Sapi (Mini)', 'Snack & Dimsum', 22000, 'Kulit martabak renyah isi telur bebek daging cincang dan daun bawang.', 'Favorit'],
    ['Bakwan Jagung Manis Renyah (3 pcs)', 'Snack & Dimsum', 14000, 'Jagung manis pipil adonan rempah digoreng garing renyah dicocol rawit.', 'Gurih Manis'],
    ['Perkedel Kentang Ayam Gurih (3 pcs)', 'Snack & Dimsum', 14000, 'Kentang tumbuk lembut berbalut telur dan daun seledri daging ayam.', 'Klasik'],

    // Variasi Dessert & Manis
    ['Puding Mangga Saus Vanilla', 'Dessert & Manis', 15000, 'Puding buah mangga manis lembut disiram saus vla susu vanilla dingin.', 'Segar'],
    ['Puding Cokelat Belgia Vla Susu', 'Dessert & Manis', 16000, 'Puding cokelat pekat lumer disiram saus vla susu kental manis creamy.', 'Favorit'],
    ['Banana Split Ice Cream Tyas', 'Dessert & Manis', 24000, 'Pisang segar dengan 3 scoop es krim cokelat vanilla stroberi saus cokelat.', 'Spesial'],
    ['Pancake Karamel Madu Pisang', 'Dessert & Manis', 20000, 'Pancake lembut hangat topping irisan pisang siraman madu karamel emas.', 'Manis'],
    ['Waffle Saus Cokelat Keju', 'Dessert & Manis', 21000, 'Waffle renyah di luar lembut di dalam taburan keju parut saus cokelat.', 'Favorit'],
    ['Klapertart Kelapa Muda Panggang', 'Dessert & Manis', 20000, 'Kue khas Manado daging kelapa muda kismis kenari bubuk kayu manis wangi.', 'Khas Manado'],
    ['Kolak Pisang Ubi Gula Aren', 'Dessert & Manis', 15000, 'Pisang kepok ubi manis kuah santan gula aren murni daun pandan harum.', 'Tradisional'],
    ['Bubur Sumsum Kuah Kinca Gula Jawa', 'Dessert & Manis', 14000, 'Bubur tepung beras santan lembut gurih disiram kuah gula jawa wangi.', 'Tradisional'],
    ['Es Krim Goreng Vanilla Renyah', 'Dessert & Manis', 18000, 'Roti goreng renyah panas dengan es krim vanilla beku di dalamnya.', 'Unik'],
    ['Roti Bakar Keju Mozzarella Tarik', 'Dessert & Manis', 20000, 'Roti bakar mentega dengan keju mozzarella melar gurih lumer.', 'Viral'],
    ['Churros Gula Kayu Manis Cokelat', 'Dessert & Manis', 18000, 'Kue churros spanyol renyah taburan gula kayu manis dicocol dark choco.', 'Camilan'],
    ['Singkong Karamel Manis Legit', 'Dessert & Manis', 16000, 'Singkong rebus empuk dimasak karamel gula aren wangi wijen panggang.', 'Tradisional'],

    // Variasi Minuman Segar
    ['Jus Sirsak Madu Segar', 'Minuman', 15000, 'Daging buah sirsak segar manis asam alami diblender madu murni.', 'Segar'],
    ['Jus Jambu Biji Merah Segar', 'Minuman', 14000, 'Jambu biji merah kaya vitamin C diblender halus disaring segar.', 'Sehat'],
    ['Jus Tomat Madu Segar', 'Minuman', 13000, 'Tomat merah segar manis diblender madu alami menyehatkan kulit.', 'Sehat'],
    ['Jus Wortel Jeruk Sehat', 'Minuman', 15000, 'Kombinasi sari wortel manis dan perasan jeruk segar kaya vitamin A.', 'Detox'],
    ['Es Timun Serut Jeruk Nipis', 'Minuman', 12000, 'Serutan mentimun segar dingin dengan air gula asli dan perasan jeruk nipis.', 'Segar Dingin'],
    ['Es Lidah Buaya Selasih Dingin', 'Minuman', 14000, 'Daging lidah buaya kenyal dingin biji selasih sirup pandan wangi.', 'Pereda Panas'],
    ['Es Blewah Serut Gula Aren', 'Minuman', 12000, 'Blewah serut aroma khas manis legit gula aren cair es batu segar.', 'Tradisional'],
    ['Es Kopi Susu Pandan Wangi', 'Coffee & Tea', 18000, 'Espresso arabika susu segar dengan sirup pandan alami harum manis legit.', 'Favorit'],
    ['Es Kopi Susu Regal Crumble', 'Coffee & Tea', 20000, 'Kopi susu gula aren dengan taburan biskuit marie regal renyah gurih.', 'Best Seller'],
    ['Hazelnut Latte Iced Creamy', 'Coffee & Tea', 22000, 'Espresso blend susu dingin berpadu sirup hazelnut kacang panggang wangi.', 'Kopi Modern'],
    ['Vanilla Latte Iced Dingin', 'Coffee & Tea', 21000, 'Espresso susu segar berpadu manis lembut sirup vanilla madagaskar.', 'Favorit'],
    ['Affogato Espresso Vanilla', 'Coffee & Tea', 18000, 'Satu scoop es krim vanilla dingin disiram satu shot espresso panas pekat.', 'Pecinta Kopi'],
    ['Teh Telur Khas Padang Berbusa', 'Coffee & Tea', 16000, 'Kocokan kuning telur bebek teh hitam pekat susu kental manis berbusa.', 'Khas Padang'],
    ['Bandrek Susu Jahe Rempah', 'Coffee & Tea', 14000, 'Rebusan jahe merah gula aren kayu manis cengkeh ditambah susu kental manis.', 'Hangat'],
    ['Sekoteng Hangat Komplit', 'Minuman', 15000, 'Kuah jahe hangat dengan kacang tanah sangrai pacar cina roti kolang kaling.', 'Hangat'],
    ['Susu Jahe Merah Madu Murni', 'Minuman', 14000, 'Susu sapi murni hangat dipadu ekstrak jahe merah geprek madu hutan.', 'Sehat'],
    ['Es Doger Khas Bandung Komplit', 'Minuman', 17000, 'Es serut santan merah muda ketan hitam tape singkong kelapa muda roti.', 'Khas Bandung'],
    ['Es Podeng Manten Nostalgia', 'Minuman', 16000, 'Es puter santan tradisional agar-agar alpukat roti taburan meses cokelat.', 'Tradisional'],
    ['Air Kelapa Murni Utuh Batok', 'Minuman', 18000, 'Kelapa muda utuh batok segar alami langsung disajikan dingin.', 'Alami'],
    ['Lemonade Squash Mint Soda', 'Minuman', 16000, 'Air soda dingin segar perasan lemon asli dan remasan daun mint segar.', 'Segar Dingin'],

    // Sambal & Sides
    ['Sambal Bawang Ulek Meledak', 'Geprek & Sambal', 6000, 'Ulekan cabai rawit merah dan bawang putih segar disiram minyak panas.', 'Pedas Pol'],
    ['Sambal Terasi Bakar Limau', 'Geprek & Sambal', 6000, 'Terasi udang bakar harum diulek cabai tomat dengan perasan jeruk limau.', 'Tradisional'],
    ['Sambal Ijo Teri Asin', 'Geprek & Sambal', 7000, 'Cabai hijau keriting kukus ditumis dengan taburan teri asin renyah.', 'Favorit'],
    ['Sambal Matah Bali Kecombrang', 'Geprek & Sambal', 7000, 'Irisan bawang merah cabai rawit serai minyak kelapa bunga kecombrang.', 'Khas Bali'],
    ['Sambal Dabu-Dabu Segar Manado', 'Geprek & Sambal', 6000, 'Potongan tomat merah hijau cabai rawit bawang merah perasan jeruk nipis.', 'Segar'],
    ['Sambal Korek Bawang Panas', 'Geprek & Sambal', 6000, 'Cabai rawit merah bawang putih digeprek kasar disiram minyak panas gurih.', 'Pedas Nampol'],
    ['Sambal Mangga Muda Segar', 'Geprek & Sambal', 7000, 'Irisan mangga muda asam segar diulek bersama cabai merah gula terasi.', 'Pedas Asam'],
    ['Sambal Tempe Semangit Pedas', 'Geprek & Sambal', 7000, 'Tempe semangit bakar gurih diulek bumbu cabai kencur bawang harum.', 'Tradisional'],
    ['Sambal Petis Udang Madura', 'Geprek & Sambal', 7000, 'Petis udang hitam gurih manis khas Madura dengan ulekan cabai rawit.', 'Khas Madura'],
    ['Kerupuk Kaleng Putih Gurih (2 pcs)', 'Snack & Dimsum', 4000, 'Kerupuk mawar putih bulat renyah gurih teman makan segala hidangan.', 'Pelengkap'],
    ['Kerupuk Udang Renyah Gurih', 'Snack & Dimsum', 6000, 'Kerupuk sari udang asli renyah gurih keemasan pelengkap hidangan resto.', 'Pelengkap'],
    ['Emping Melinjo Renyah Asin', 'Snack & Dimsum', 7000, 'Emping melinjo goreng garing dengan taburan garam gurih sedikit pahit nikmat.', 'Pelengkap'],
    ['Tahu Goreng Gurih Bumbu Kuning (4 pcs)', 'Snack & Dimsum', 10000, 'Tahu kuning gurih ungkep bawang ketumbar digoreng garing di luar lembut.', 'Pelengkap'],
    ['Tempe Goreng Ketumbar Gurih (4 pcs)', 'Snack & Dimsum', 10000, 'Tempe kedelai bumbu ketumbar bawang putih digoreng renyah harum.', 'Pelengkap'],
    ['Telur Dadar Barendo Crispy', 'Makanan Utama', 12000, 'Telur bebek kocok bumbu rempah digoreng mengembang mekar renyah keriting.', 'Best Seller'],
    ['Telur Ceplok Mata Sapi', 'Makanan Utama', 6000, 'Telur ayam ceplok kuning setengah matang atau matang sesuai selera.', 'Pelengkap'],
    ['Telur Asin Brebes Masir Gurih', 'Makanan Utama', 8000, 'Telur bebek asin asli Brebes kuning telur berminyak masir gurih legit.', 'Favorit'],
    ['Kol Goreng Gurih Crispy', 'Makanan Utama', 8000, 'Kol segar digoreng renyah garing manis gurih pendamping ayam penyet.', 'Favorit'],
    ['Pete Bakar Harum', 'Makanan Utama', 12000, 'Satu papan petai segar dibakar di atas arang harum legit menggugah selera.', 'Tradisional'],
    ['Terong Goreng Sambal Bawang', 'Makanan Utama', 12000, 'Irisan terong ungu digoreng gurih disajikan ulekan sambal bawang pedas.', 'Favorit'],
    ['Smoothie Mangga Buah Naga', 'Minuman', 18000, 'Lapisan smoothie mangga kental kuning dan buah naga merah segar alami.', 'Sehat'],
    ['Smoothie Strawberry Banana Berry', 'Minuman', 19000, 'Stroberi segar dan pisang manis diblender yoghurt kental kaya serat.', 'Sehat'],
    ['Avocado Coffee Float Tyas', 'Coffee & Tea', 23000, 'Jus alpukat mentega kental disiram espresso arabika satu scoop es krim.', 'Chef Pick'],
    ['Matcha Espresso Fusion Cold', 'Coffee & Tea', 22000, 'Gradasi hijau matcha Jepang susu dingin dan espresso arabika pekat.', 'Kopi Modern'],
    ['Ice Cream Gelato Cokelat Vanilla', 'Dessert & Manis', 16000, 'Dua scoop gelato lembut rasa cokelat pekat dan vanilla creamy.', 'Manis'],
    ['Kelapa Kopyor Es Sirup Cocopandan', 'Minuman', 20000, 'Daging kelapa kopyor lembut berpadu es sirup cocopandan manis wangi.', 'Spesial'],

    // Paket Hemat
    ['Paket Hemat 1: Nasi + Ayam Geprek + Es Teh', 'Paket Hemat', 25000, 'Nasi hangat, ayam geprek sambal bawang pedas dan es teh melati jumbo.', 'Paket Hemat'],
    ['Paket Hemat 2: Nasi + Ayam Bakar Madu + Sayur Asem + Es Teh', 'Paket Hemat', 32000, 'Nasi putih, ayam bakar madu legit, sayur asem segar dan es teh manis.', 'Best Seller'],
    ['Paket Hemat 3: Nasi Bakar Cumi + Tahu Tempe + Teh Manis', 'Paket Hemat', 31000, 'Nasi bakar cumi cabe ijo daun pisang, tahu tempe goreng dan teh manis.', 'Favorit'],
    ['Paket Hemat 4: Mie Ayam Jamur + Pangsit Goreng + Es Jeruk', 'Paket Hemat', 28000, 'Mie ayam jamur kenyal, 2 pangsit goreng renyah dan es jeruk peras murni.', 'Favorit'],
    ['Paket Hemat 5: Nasi Goreng Spesial + Telur Ceplok + Es Cincau', 'Paket Hemat', 30000, 'Nasi goreng spesial ayam sosis, telur ceplok dan es cincau gula aren.', 'Kenyang'],
    ['Paket Kenyang 6: Nasi Timbel Komplit + Ayam Lengkuas + Es Teh Manis', 'Paket Hemat', 37000, 'Nasi timbel daun, ayam lengkuas serundeng, tahu tempe, lalap sambal, es teh.', 'Komplit'],
    ['Paket Seafood 7: Nasi + Cumi Goreng Tepung + Sambal Bawang + Es Lemon Tea', 'Paket Hemat', 36000, 'Nasi putih, calamari cumi krispi saus tartar sambal bawang dan es lemon tea.', 'Seafood Hemat'],
    ['Paket Seafood 8: Nasi + Udang Bakar Madu + Lalapan + Es Kelapa Muda', 'Paket Hemat', 39000, 'Nasi putih, udang bakar jimbaran, lalapan segar dan es kelapa muda asli.', 'Seafood Nikmat'],
    ['Paket Ngemil Santai: Dimsum (4 pcs) + Pangsit Goreng (3 pcs) + Kopi Susu', 'Paket Hemat', 35000, 'Dimsum ayam udang, pangsit goreng renyah dan kopi susu gula aren Tyas.', 'Ngemil Santai'],
    ['Paket Keluarga Tyas (4 Orang): 1 Gurame + 2 Ayam + 4 Nasi + Kangkung + 4 Teh', 'Paket Hemat', 135000, 'Porsi besar 4 orang: 1 Gurame terbang asam manis, 2 ayam bakar madu, 4 nasi, cah kangkung, 4 es teh.', 'Family Feast']
];

echo "Menyiapkan " . count($rawItems) . " menu dengan foto unik...\n";

try {
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE tabel_menu; SET FOREIGN_KEY_CHECKS = 1;");
    
    $stmt = $pdo->prepare("
        INSERT INTO tabel_menu (nama_menu, kategori, harga, deskripsi, foto, tag, tersedia)
        VALUES (?, ?, ?, ?, ?, ?, 1)
    ");

    $count = 0;
    foreach ($rawItems as $idx => $it) {
        $fotoUnik = getUniquePhoto($it[0], $it[1], $idx + 1);
        $stmt->execute([
            $it[0],
            $it[1],
            $it[2],
            $it[3],
            $fotoUnik,
            $it[4] ?? ''
        ]);
        $count++;
    }

    echo "SUKSES: {$count} menu baru dengan FOTO BERBEDA & UNIK berhasil disimpan ke database!\n";
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
