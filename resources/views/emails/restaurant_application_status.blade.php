@component('mail::message')
# Pemberitahuan Status Pengajuan Restoran

Halo **{{ $name }}**,

Kami ingin memberitahukan tentang status pengajuan restoran Anda.

@if ($status == 'approved')
Selamat! Pengajuan akun restoran Anda telah **disetujui**!

Anda sekarang dapat login ke aplikasi admin kami menggunakan detail berikut:
- **Email**: `{{ $email }}`
- **Password Default**: `password123`

Kami sangat menyarankan Anda untuk segera mengubah kata sandi Anda setelah login pertama kali untuk keamanan akun Anda.

Terima kasih telah bergabung dengan {{ $appName }}! Kami sangat antusias untuk berkolaborasi dengan Anda.

@else
Mohon maaf, pengajuan akun restoran Anda **ditolak**.

Kami tidak dapat memproses pengajuan Anda saat ini. Kami memahami bahwa ini mungkin mengecewakan.
Jika Anda memiliki pertanyaan lebih lanjut atau ingin mengajukan banding, jangan ragu untuk menghubungi tim dukungan kami.

@endif

Terima kasih,
Tim {{ config('app.name') }}
@endcomponent
