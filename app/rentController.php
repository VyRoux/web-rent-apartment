<?php
    require_once 'database.php';

    class rentController{
        private $db;
        
        public function __construct(){
            $this->db = Database::getInstance()->getConnection();
        }
        
        // Mendapatkan semua apartemen
        public function getAllApartments() {
            $query = "SELECT * FROM apartments";
            $result = $this->db->query($query);
            
            $apartments = [];
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $apartments[] = $row;
                }
            }
            return $apartments;
        }
        
        // Mendapatkan apartemen berdasarkan ID
        public function getApartmentById($id) {
            $query = "SELECT * FROM apartments WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            }
            return null;
        }
        
        // Mendapatkan semua transaksi
        public function getAllTransactions() {
            $query = "SELECT t.*, u.username, a.name as apartment_name 
                     FROM transactions t 
                     JOIN users u ON t.user_id = u.id 
                     JOIN apartments a ON t.apartment_id = a.id";
            $result = $this->db->query($query);
            
            $transactions = [];
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $transactions[] = $row;
                }
            }
            return $transactions;
        }
        
        // Mendapatkan semua pengguna
        public function getAllUsers() {
            $query = "SELECT id, username, email, full_name, role, created_at FROM users";
            $result = $this->db->query($query);
            
            $users = [];
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $users[] = $row;
                }
            }
            return $users;
        }
        
        // Menambahkan apartemen baru
        public function addApartment($name, $description, $address, $price_per_month, $image_url = null) {
            $status = 'available';
            $query = "INSERT INTO apartments (name, description, address, price_per_month, image_url, status) 
                     VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("sssdds", $name, $description, $address, $price_per_month, $image_url, $status);
            
            return $stmt->execute();
        }
        
        // Memperbarui status apartemen
        public function updateApartmentStatus($id, $status) {
            $query = "UPDATE apartments SET status = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("si", $status, $id);
            
            return $stmt->execute();
        }
    }
?>