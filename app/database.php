<?php
    // Class Database untuk mengelola koneksi ke database SQL
    class Database{
        private static $instance = null;
        private $connection;
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

        private static function getInstance(){
            if (self::$instance == null){
                self::$instance = new Database();
            } 
            return self::$instance;
        }

        public function getConnection(){
            return $this->connection;
        }
    }
?>