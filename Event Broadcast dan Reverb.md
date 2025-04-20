Laravel Reverb adalah server WebSocket resmi yang dirancang khusus untuk aplikasi Laravel, memungkinkan komunikasi dua arah secara real-time antara server dan klien. Dengan Reverb, Anda dapat mengimplementasikan fitur seperti notifikasi instan, chat real-time, dan pembaruan data langsung tanpa perlu menggunakan layanan pihak ketiga seperti Pusher.

Berikut adalah langkah-langkah untuk mengimplementasikan Laravel Broadcasting menggunakan Reverb:

### 1. Instalasi dan Konfigurasi Broadcasting

Laravel menyediakan perintah Artisan untuk menginstal dependensi broadcasting. Jalankan perintah berikut di terminal proyek Anda:

```bash
php artisan install:broadcasting
```


Perintah ini akan:

- Membuat file konfigurasi `broadcasting.php`.
- Membuat file `routes/channels.php`.
- Menawarkan instalasi Laravel Reverb. Pilih "Yes" saat diminta.
- Menawarkan instalasi dan build dependensi Node yang diperlukan untuk broadcasting. Pilih "Yes" saat diminta.

Setelah proses ini selesai, Laravel Reverb dan Laravel Echo akan terinstal dalam proyek Anda.

### 2. Konfigurasi Reverb

Setelah instalasi, periksa file `.env` Anda untuk memastikan bahwa konfigurasi Reverb telah ditambahkan. Biasanya, pengaturan berikut akan ditambahkan:

```env
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=your_app_id
REVERB_APP_KEY=your_app_key
REVERB_APP_SECRET=your_app_secret
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```


Pengaturan `VITE_` memungkinkan Vite untuk mengakses informasi server Reverb, yang diperlukan oleh `js/echo.js`. Pastikan nilai-nilai ini sesuai dengan konfigurasi server Anda.

### 3. Menjalankan Server Reverb

Untuk memulai server Reverb, jalankan perintah berikut di terminal:

```bash
php artisan reverb:start
```


Server akan berjalan pada host dan port yang telah Anda tentukan, misalnya `localhost:8080`. Biarkan terminal ini berjalan selama Anda mengembangkan atau menjalankan aplikasi.

### 4. Mendefinisikan Channel

Di dalam file `routes/channels.php`, Anda dapat mendefinisikan channel untuk broadcasting. Untuk membuat channel publik bernama `chat`, tambahkan kode berikut:

```php
Broadcast::channel('chat', function () {
    return true;
});
```


Dengan pengaturan ini, semua pengguna dapat mendengarkan channel `chat`. Jika Anda ingin membatasi akses, Anda dapat menambahkan logika otorisasi sesuai kebutuhan.

### 5. Membuat Event untuk Broadcasting

Buat event yang akan dibroadcast menggunakan perintah Artisan:

```bash
php artisan make:event MessageSent
```


Kemudian, di dalam file `app/Events/MessageSent.php`, implementasikan antarmuka `ShouldBroadcastNow` dan tentukan channel tempat event akan disiarkan:

```php
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Broadcasting\Channel;

class MessageSent implements ShouldBroadcastNow
{
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('chat');
    }
}
```


Dengan implementasi ini, setiap kali event `MessageSent` dipicu, pesan akan disiarkan ke channel `chat`.

### 6. Memicu Event

Untuk memicu event dan mengirim pesan, Anda dapat menambahkan rute atau logika di controller. Sebagai contoh, tambahkan rute berikut di `routes/web.php`:

```php
use App\Events\MessageSent;

Route::get('/send-message', function () {
    broadcast(new MessageSent('Hello, this is a test message!'));
    return 'Message sent!';
});
```


Mengakses URL `/send-message` akan memicu event `MessageSent` dan mengirim pesan ke semua klien yang mendengarkan channel `chat`.

### 7. Mendengarkan Event di Frontend

Di sisi frontend, Anda dapat menggunakan Laravel Echo untuk mendengarkan event yang disiarkan. Pastikan Anda telah menginstal dan mengonfigurasi Laravel Echo sesuai dokumentasi. Kemudian, tambahkan kode berikut ke file JavaScript Anda:

```javascript
import Echo from 'laravel-echo';

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

window.Echo.channel('chat')
    .listen('MessageSent', (event) => {
        console.log(event.message);
    });
```


Dengan konfigurasi ini, setiap pesan yang disiarkan ke channel `chat` akan diterima dan ditampilkan di konsol browser.

### Kesimpulan

Dengan mengikuti langkah-langkah di atas, Anda dapat mengimplementasikan Laravel Broadcasting menggunakan Reverb untuk menambahkan fitur komunikasi real-time ke dalam aplikasi Anda. Reverb menawarkan integrasi yang mulus dengan ekosistem Laravel, performa tinggi, dan skalabilitas yang mudah, menjadikannya pilihan 