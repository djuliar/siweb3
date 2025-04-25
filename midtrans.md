## Apa itu Midtrans?

[Midtrans](https://midtrans.com/) adalah layanan payment gateway Indonesia yang memfasilitasi berbagai metode pembayaran seperti:
- Kartu kredit/debit
- Transfer bank (Virtual Account)
- E-wallet (GoPay, ShopeePay, dll)
- Gerai retail (Indomaret, Alfamart)

Integrasi dengan Midtrans memungkinkan Laravel menangani transaksi secara otomatis dan aman.

---

## Konsep Integrasi Laravel dengan Midtrans

Integrasi ini biasanya mencakup:
1. **Frontend** – Form checkout atau tombol bayar.
2. **Backend** – Mengirim data ke Midtrans dan menerima callback (notifikasi pembayaran).
3. **Security** – Validasi signature dan token.
4. **Status update** – Update status pesanan berdasarkan callback dari Midtrans.

---

## Persiapan

### 1. Buat akun di [Midtrans Sandbox](https://dashboard.midtrans.com/register)
- Gunakan sandbox untuk testing
- Catat `Server Key` dan `Client Key` dari menu **Settings > Access Keys**

### 2. Instalasi Laravel dan Midtrans SDK
```bash
composer require midtrans/midtrans-php
```

---

## Konfigurasi di Laravel

### 1. Tambahkan konfigurasi Midtrans di `.env`

```env
MIDTRANS_SERVER_KEY=SB-Mid-server-XXXXXX
MIDTRANS_CLIENT_KEY=SB-Mid-client-XXXXXX
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_SNAP_REDIRECT=true
MIDTRANS_SANITIZE=true
MIDTRANS_3DS=true
```

### 2. Buat file config: `config/midtrans.php`

```php
return [
    'serverKey' => env('MIDTRANS_SERVER_KEY'),
    'clientKey' => env('MIDTRANS_CLIENT_KEY'),
    'isProduction' => env('MIDTRANS_IS_PRODUCTION', false),
    'isSanitized' => env('MIDTRANS_SANITIZE', true),
    'is3ds' => env('MIDTRANS_3DS', true),
];
```

### 3. Inisialisasi Midtrans saat dibutuhkan
```php
use Midtrans\Config;

Config::$serverKey = config('midtrans.serverKey');
Config::$isProduction = config('midtrans.isProduction');
Config::$isSanitized = config('midtrans.isSanitized');
Config::$is3ds = config('midtrans.is3ds');
```

---

## Contoh Penerapan

### Studi Kasus: Sistem Pembelian Produk Digital

#### 1. Buat Route di `routes/web.php`

```php
use App\Http\Controllers\MidtransController;

Route::post('/checkout', [MidtransController::class, 'checkout']);
Route::post('/midtrans/notification', [MidtransController::class, 'notification']);
```

---

### 2. Controller: `app/Http/Controllers/MidtransController.php`

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');
        Config::$isSanitized = config('midtrans.isSanitized');
        Config::$is3ds = config('midtrans.is3ds');
    }

    public function checkout(Request $request)
    {
        $orderId = 'ORDER-' . time();
        $price = 50000;

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $price,
            ],
            'customer_details' => [
                'first_name' => 'John',
                'email' => 'john@example.com',
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json([
            'snap_token' => $snapToken,
            'order_id' => $orderId
        ]);
    }

    public function notification(Request $request)
    {
        $notif = new \Midtrans\Notification();
        $transaction = $notif->transaction_status;
        $order_id = $notif->order_id;

        if ($transaction == 'settlement') {
            // Update status pesanan di database jadi "PAID"
        } elseif ($transaction == 'pending') {
            // Status pending
        } elseif ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
            // Gagal bayar
        }

        return response()->json(['message' => 'Notification handled']);
    }
}
```

---

### 3. Frontend Checkout (Vue / Blade / JS)

Contoh dengan JavaScript:

```html
<button id="pay-button">Bayar Sekarang</button>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script>
document.getElementById('pay-button').addEventListener('click', function () {
    fetch('/checkout', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => res.json())
    .then(data => {
        window.snap.pay(data.snap_token, {
            onSuccess: function(result) {
                alert("Pembayaran sukses!");
            },
            onPending: function(result) {
                alert("Menunggu pembayaran...");
            },
            onError: function(result) {
                alert("Pembayaran gagal!");
            }
        });
    });
});
</script>
```

---

## Callback / Webhook

Midtrans akan mengirim notifikasi ke endpoint Laravel setelah pembayaran:
- Pastikan URL `/midtrans/notification` **bisa diakses publik**
- Gunakan **route `POST`**
- Pastikan app Laravel kamu **tidak memblokir IP Midtrans**

---

## Validasi Keamanan (Opsional Tapi Disarankan)

Tambahkan verifikasi signature jika perlu validasi tambahan agar callback tidak bisa dipalsukan. Midtrans menyediakan signature key untuk itu.

---

## Simpan Status Transaksi

Buat tabel `orders`:
```bash
php artisan make:migration create_orders_table
```

```php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->string('order_id');
    $table->string('status')->default('pending');
    $table->timestamps();
});
```

Saat menerima notifikasi:
```php
$order = Order::where('order_id', $order_id)->first();
$order->status = $transaction;
$order->save();
```

---

## 🎯 Kesimpulan

Integrasi Midtrans di Laravel terdiri dari:
- Setup konfigurasi SDK
- Membuat token Snap
- Menangani callback dari Midtrans
- Update status pesanan
- Frontend interaksi Snap.js