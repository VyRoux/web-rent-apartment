<?php
    // Class Database untuk mengelola koneksi ke database SQL
    class Database{

        // Atribut class Database
        private static $instance = null;
        
        // Menyimpan koneksi Database
        private $connection;

        // konfigurasi Database
        private $host = 'localhost';
        private $user = 'root';
        private $password = '';
        private $database = 'rent_apartment';

        private function __construct(){
            // throw new \Exception('Not implemented');

            // Membuat koneksi ke database dengan mysqli
            $this->connection = mysqli_connect($this->host, $this->user, $this->password, $this->database);

            // jika koneksi gagal, tampilkan pesan dan error
            if (!$this->connection) {
                die ("Connection Failed : " . mysqli_connect_error());
            }
        }

        /**  
        Metode statis publik untuk mendapatkan instance tunggal dari kelas Database.
        Metode ini adalah "pintu gerbang" global untuk mendapatkan koneksi.
        */
        public static function getInstance(){
            if (self::$instance == null){
                // Jika belum ada Instance, maka buat baru
                self::$instance = new Database();
            }
            // Mengembalikan Instance yang sudah ada
            return self::$instance;
        }

        // Metode publik untuk mendapatkan koneksi Database
        public function getConnection(){
            return $this->connection;
        }
    }
?>