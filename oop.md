## Konsep Dasar Object Oriented Programming (OOP) dalam PHP

Object Oriented Programming (OOP) adalah paradigma pemrograman yang menggunakan **objek** dan **kelas** sebagai struktur dasar. OOP digunakan untuk membuat aplikasi yang lebih modular, terstruktur, dan mudah dikelola, khususnya pada proyek-proyek besar. PHP mendukung sepenuhnya konsep OOP sejak versi 5.

### 1. **Kelas dan Objek**
- **Kelas** adalah cetak biru atau blueprint untuk membuat objek. Kelas berisi definisi atribut (properti) dan metode (fungsi) yang dapat dimiliki oleh objek.
- **Objek** adalah instansiasi dari kelas. Objek ini adalah entitas yang dibuat berdasarkan kelas.

#### Contoh:
```php
<?php
class Mobil {
    public $merk;
    public $warna;

    public function __construct($merk, $warna) {
        $this->merk = $merk;
        $this->warna = $warna;
    }

    public function infoMobil() {
        return "Mobil ini bermerk " . $this->merk . " dan berwarna " . $this->warna;
    }
}

// Membuat objek dari kelas Mobil
$mobil1 = new Mobil("Toyota", "Merah");
echo $mobil1->infoMobil();
?>
```
Pada contoh di atas:
- `class Mobil` adalah sebuah kelas dengan dua properti (`$merk` dan `$warna`) dan satu metode (`infoMobil()`).
- `new Mobil("Toyota", "Merah")` adalah proses instansiasi yang menghasilkan objek `mobil1`.

### 2. **Encapsulation (Enkapsulasi)**
Encapsulation adalah konsep menyembunyikan detail internal dari objek dan hanya menyediakan antarmuka yang diperlukan untuk berinteraksi dengan objek. Hal ini dapat dilakukan dengan mengatur visibilitas properti dan metode menggunakan **access modifiers** seperti `public`, `private`, dan `protected`.

- `public`: dapat diakses dari mana saja.
- `private`: hanya dapat diakses dari dalam kelas itu sendiri.
- `protected`: hanya dapat diakses dari dalam kelas itu sendiri dan kelas turunannya.

#### Contoh:
```php
<?php
class User {
    private $username;
    private $password;

    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    public function getUsername() {
        return $this->username;
    }

    private function hashPassword() {
        return md5($this->password);
    }
}

$user = new User("admin", "1234");
echo $user->getUsername(); // Mengembalikan 'admin'
// echo $user->password; // Error: properti private tidak bisa diakses langsung
?>
```

### 3. **Inheritance (Pewarisan)**
Inheritance adalah kemampuan untuk mewariskan properti dan metode dari satu kelas ke kelas lainnya. Kelas yang mewarisi disebut **kelas turunan** (child class), dan kelas yang diwarisi disebut **kelas induk** (parent class).

#### Contoh:
```php
<?php
class Hewan {
    public $nama;

    public function __construct($nama) {
        $this->nama = $nama;
    }

    public function suara() {
        return "Hewan ini mengeluarkan suara";
    }
}

class Kucing extends Hewan {
    public function suara() {
        return $this->nama . " mengeong";
    }
}

$kucing = new Kucing("Mimi");
echo $kucing->suara(); // Mengembalikan 'Mimi mengeong'
?>
```
Dalam contoh ini, kelas `Kucing` mewarisi kelas `Hewan` dan menimpa (override) metode `suara()` untuk mengubah perilakunya.

### 4. **Polymorphism**
Polymorphism adalah kemampuan untuk menggunakan metode dengan nama yang sama di kelas yang berbeda, tetapi dengan perilaku yang berbeda. Ini biasanya digunakan dalam pewarisan dengan konsep **method overriding**.

#### Contoh:
```php
<?php
class Burung {
    public function terbang() {
        return "Burung ini bisa terbang.";
    }
}

class Ayam extends Burung {
    public function terbang() {
        return "Ayam tidak bisa terbang tinggi.";
    }
}

$burung = new Burung();
$ayam = new Ayam();

echo $burung->terbang(); // Mengembalikan 'Burung ini bisa terbang.'
echo $ayam->terbang();   // Mengembalikan 'Ayam tidak bisa terbang tinggi.'
?>
```

### 5. **Abstraction (Abstraksi)**
Abstraction adalah konsep menyembunyikan kompleksitas dengan hanya menunjukkan antarmuka yang penting. Di PHP, abstraksi dapat dilakukan dengan menggunakan **kelas abstrak** dan **interface**.

- **Kelas Abstrak**: Tidak dapat diinstansiasi langsung dan hanya digunakan sebagai dasar untuk kelas lain.
- **Interface**: Menyediakan deklarasi metode tanpa implementasi. Kelas yang mengimplementasikan interface harus mengimplementasikan semua metode yang dideklarasikan di interface.

#### Contoh Kelas Abstrak:
```php
<?php
abstract class Kendaraan {
    abstract public function bergerak();
}

class Sepeda extends Kendaraan {
    public function bergerak() {
        return "Sepeda bergerak dengan cara dikayuh.";
    }
}

$sepeda = new Sepeda();
echo $sepeda->bergerak(); // Mengembalikan 'Sepeda bergerak dengan cara dikayuh.'
?>
```

#### Contoh Interface:
```php
<?php
interface Mengemudi {
    public function setir();
}

class Mobil implements Mengemudi {
    public function setir() {
        return "Mobil dikemudikan dengan setir.";
    }
}

$mobil = new Mobil();
echo $mobil->setir(); // Mengembalikan 'Mobil dikemudikan dengan setir.'
?>
```

## Studi Kasus Penggunaan OOP dalam PHP

**Studi Kasus**: Membuat sistem sederhana untuk toko online yang memiliki dua jenis produk: elektronik dan pakaian. Setiap produk memiliki nama, harga, dan kategori. Produk elektronik memiliki informasi tambahan seperti garansi, sedangkan produk pakaian memiliki ukuran.

### Implementasi:

```php
<?php
// Kelas Induk Produk
class Produk {
    public $nama;
    public $harga;

    public function __construct($nama, $harga) {
        $this->nama = $nama;
        $this->harga = $harga;
    }

    public function infoProduk() {
        return "Nama Produk: $this->nama, Harga: $this->harga";
    }
}

// Kelas Turunan Elektronik
class Elektronik extends Produk {
    public $garansi;

    public function __construct($nama, $harga, $garansi) {
        parent::__construct($nama, $harga);
        $this->garansi = $garansi;
    }

    public function infoProduk() {
        return parent::infoProduk() . ", Garansi: $this->garansi tahun";
    }
}

// Kelas Turunan Pakaian
class Pakaian extends Produk {
    public $ukuran;

    public function __construct($nama, $harga, $ukuran) {
        parent::__construct($nama, $harga);
        $this->ukuran = $ukuran;
    }

    public function infoProduk() {
        return parent::infoProduk() . ", Ukuran: $this->ukuran";
    }
}

// Membuat Objek Elektronik
$tv = new Elektronik("TV Samsung", 5000000, 2);
echo $tv->infoProduk(); // Mengembalikan 'Nama Produk: TV Samsung, Harga: 5000000, Garansi: 2 tahun'

// Membuat Objek Pakaian
$kaos = new Pakaian("Kaos Polo", 150000, "L");
echo $kaos->infoProduk(); // Mengembalikan 'Nama Produk: Kaos Polo, Harga: 150000, Ukuran: L'
?>
```

### Penjelasan:
- Kelas `Produk` adalah kelas induk yang mendefinisikan properti dasar seperti `nama` dan `harga`.
- `Elektronik` dan `Pakaian` adalah kelas turunan yang mewarisi dari kelas `Produk`, tetapi memiliki properti dan metode tambahan yang spesifik.
- Dengan OOP, kita dapat membuat sistem yang fleksibel dan mudah diperluas jika kita ingin menambahkan jenis produk lain.

---

Itulah penjelasan dan studi kasus singkat tentang konsep dasar OOP di PHP. Dengan OOP, pengembangan aplikasi akan lebih mudah, terutama dalam hal pemeliharaan dan pengelolaan kode.
