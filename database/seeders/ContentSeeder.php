<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\Package;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@jejaknusa.test'],
            ['name' => 'Admin Jejak Nusa', 'password' => Hash::make('nusa2026'), 'role' => 'superadmin', 'status' => 'active'],
        );

        $packages = [
            ['Sailing Komodo Premium 3D2N (Phinisi AC)', 'sailing-komodo-premium-3d2n', 'Labuan Bajo, NTT', 'ntt', '3 Hari 2 Malam', 'open', 2850000, 'Best Seller', 'Sisa 3 kursi!', 4.9, 142, '/images/img-04.jpg', 1],
            ['Raja Ampat Wayag & Pianemo 4D3N', 'raja-ampat-wayag-pianemo-4d3n', 'Raja Ampat, Papua Barat', 'papua', '4 Hari 3 Malam', 'private', 6450000, 'Eksklusif', null, 5.0, 89, '/images/img-05.jpg', 1],
            ['Bromo Midnight & Kawah Ijen 2D1N', 'bromo-midnight-kawah-ijen-2d1n', 'Probolinggo & Banyuwangi, Jatim', 'jatim', '2 Hari 1 Malam', 'open', 950000, 'Hemat', null, 4.8, 310, '/images/img-06.jpg', 1],
            ['Jejak Jalur Rempah Banda 5D4N', 'jejak-jalur-rempah-banda-5d4n', 'Banda Neira, Maluku Tengah', 'maluku', '5 Hari 4 Malam', 'open', 4200000, 'Jelajah Budaya', null, 4.9, 76, '/images/img-57.jpg', 0],
            ['Diving Surga Karang Wakatobi 4D3N', 'diving-surga-karang-wakatobi-4d3n', 'Wakatobi, Sulawesi Tenggara', 'sulawesi', '4 Hari 3 Malam', 'private', 5150000, null, 'Sisa 5 kursi!', 4.9, 64, '/images/img-58.jpg', 0],
            ['Harmoni Samosir & Kaldera Toba 3D2N', 'harmoni-samosir-kaldera-toba-3d2n', 'Danau Toba, Sumatera Utara', 'sumatera', '3 Hari 2 Malam', 'private', 1950000, 'Warisan Budaya', null, 4.8, 142, '/images/img-61.jpg', 0],
        ];
        foreach ($packages as $i => [$title, $slug, $location, $region, $duration, $type, $price, $badge, $seat, $rating, $reviews, $image, $featured]) {
            Package::updateOrCreate(['slug' => $slug], [
                'title' => $title, 'location' => $location, 'region' => $region,
                'duration' => $duration, 'trip_type' => $type, 'price' => $price,
                'badge' => $badge, 'seat_badge' => $seat, 'rating' => $rating,
                'reviews_count' => $reviews, 'image' => $image,
                'excerpt' => substr($title, 0, 80), 'status' => 'published',
                'featured' => (bool) $featured, 'sort_order' => $i,
            ]);
        }

        $testimonials = [
            ['Dian Rahmawati', 'Solo Traveler • Jakarta Selatan', 'Ikut sailing Labuan Bajo sendirian awalnya agak ragu. Tapi tim Jejak Nusa ramah sekali, pemandu sangat perhatian dan jago foto!', 5],
            ['Bambang Wijaya', 'HR Director PT Sintesa Mega', 'Outing tahunan kantor 42 peserta ke Bromo & Malang. Eksekusi logistik sangat rapi tanpa hambatan jadwal.', 5],
            ['Arya & Priscillia', 'Honeymoon Trip • Surabaya', 'Honeymoon di Raja Ampat jadi kenangan seumur hidup. Spot karang sepi yang luar biasa cantik.', 5],
        ];
        foreach ($testimonials as $i => [$name, $meta, $quote, $stars]) {
            Testimonial::firstOrCreate(['name' => $name, 'quote' => $quote], [
                'meta' => $meta, 'stars' => $stars, 'status' => 'approved', 'sort_order' => $i,
            ]);
        }

        $faqs = [
            ['pemesanan', 'Bagaimana cara memesan paket wisata di Jejak Nusa?', 'Pilih destinasi dan tanggal, isi data peserta, pilih opsi bayar DP 30% atau lunas, lalu terima e-voucher otomatis via email.'],
            ['pembayaran', 'Metode pembayaran apa saja yang tersedia?', 'Virtual Account semua bank besar, QRIS dan dompet digital, kartu kredit/debit 3D Secure, serta transfer manual ke rekening korporat.'],
            ['pembayaran', 'Apakah pembayaran bisa dicicil dengan DP?', 'Ya, DP 30% saat booking dan pelunasan maksimal H-7 reguler atau H-14 charter phinisi privat.'],
            ['pembatalan', 'Bagaimana kebijakan pembatalan dan refund?', '>30 hari: 100% (minus admin bank). 15-29 hari: 50%. 8-14 hari: 25%. <7 hari: hangus namun bisa dialihkan H-3.'],
            ['perjalanan', 'Apakah ada jaminan pasti berangkat untuk Open Trip?', 'Paket reguler berlabel garansi berangkat tanpa minimal kuota. Rute perintis butuh minimal 6 orang, kepastian H-10.'],
            ['akun', 'Apakah harus membuat akun sebelum memesan?', 'Tidak wajib (guest checkout tersedia), namun akun memudahkan lacak riwayat dan unduh e-voucher.'],
        ];
        foreach ($faqs as $i => [$cat, $q, $a]) {
            Faq::firstOrCreate(['question' => $q], [
                'category' => $cat, 'answer' => $a, 'status' => 'published', 'sort_order' => $i,
            ]);
        }

        $articles = [
            ['7 Tips Wisata ke Labuan Bajo untuk Pemula', 'Tips Perjalanan', 'Satria Dewantara', '/images/img-13.jpg', 'Panduan penting sebelum sailing phinisi di Flores.'],
            ['Waktu Terbaik Menyaksikan Blue Fire Kawah Ijen', 'Destinasi', 'Arya Wibowo', '/images/img-14.jpg', 'Musim paling jernih dan jam pendakian ideal.'],
            ['Panduan Snorkeling Bertanggung Jawab di Raja Ampat', 'Panduan', 'Bima Sakti', '/images/img-16.jpg', 'Prinsip ekowisata: reef-safe sunscreen dan jaga jarak.'],
        ];
        foreach ($articles as [$title, $cat, $author, $image, $excerpt]) {
            Article::firstOrCreate(['title' => $title], [
                'slug' => str()->slug($title), 'category' => $cat, 'author' => $author,
                'read_time' => '5 Menit Baca', 'image' => $image, 'excerpt' => $excerpt,
                'body' => $excerpt, 'status' => 'published',
            ]);
        }

        $gallery = [
            ['Labuan Bajo Phinisi', '/images/img-37.jpg', 'NTT', 'Bahari'],
            ['Puncak Pulau Padar', '/images/img-33.jpg', 'NTT', 'Bahari'],
            ['Pink Beach', '/images/img-34.jpg', 'NTT', 'Bahari'],
        ];
        foreach ($gallery as $i => [$title, $image, $region, $tag]) {
            GalleryItem::firstOrCreate(['title' => $title], [
                'image' => $image, 'region' => $region, 'tag' => $tag,
                'photographer' => '@jejaknusa', 'status' => 'published', 'sort_order' => $i,
            ]);
        }

        $team = [
            ['Satria Dewantara', 'Founder & Chief Expedition Officer', '/images/img-63.jpg'],
            ['Aulia Kartika', 'Head of Tour Operations & Safety', '/images/img-64.jpg'],
            ['Bagus Pratama', 'Lead Community & Local Partner', '/images/img-65.jpg'],
            ['Nadia Saraswati', 'Experience & Hospitality Director', '/images/img-66.jpg'],
        ];
        foreach ($team as $i => [$name, $role, $photo]) {
            TeamMember::firstOrCreate(['name' => $name], [
                'role' => $role, 'photo' => $photo, 'status' => 'active', 'sort_order' => $i,
            ]);
        }

        $settings = [
            ['site_name', 'Jejak Nusa', 'general'],
            ['whatsapp', '6281234567890', 'contact'],
            ['contact_email', 'info@jejaknusa.id', 'contact'],
            ['contact_phone', '+62 812-3456-7890', 'contact'],
            ['address', 'Jl. Danau Tamblingan No. 88, Sanur, Denpasar, Bali', 'contact'],
            ['hours', '08.00 - 20.00 WIB', 'contact'],
            ['meta_title', 'Jejak Nusa Tour & Travel — Satu jejak, seribu cerita Nusantara', 'seo'],
            ['meta_description', 'Platform kurasi tur dan ekspedisi autentik kepulauan Indonesia.', 'seo'],
        ];
        foreach ($settings as [$key, $value, $group]) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value, 'group' => $group]);
        }
    }
}
