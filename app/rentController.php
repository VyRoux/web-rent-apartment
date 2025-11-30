<?php
require_once 'database.php';

class rentController {
    private $connection;

    // Konstruktor: otomatis membuat koneksi saat objek dibuat
    public function __construct() {
        $db = Database::getInstance();
        $this->connection = $db->getConnection();
    }

    // --- FUNGSI BANTUAN GAMBAR ---
    /**
     * Fungsi untuk meng-upload dan me-resize gambar
     * @param array $file File dari $_FILES
     * @param string $targetDir Direktori tujuan
     * @param int $maxWidth Lebar maksimal
     * @param int $maxHeight Tinggi maksimal
     * @return string|false Nama file jika berhasil, false jika gagal
     */
    private function uploadAndResizeImage($file, $targetDir, $maxWidth = 800, $maxHeight = 600) {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return false;
        }

        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileError = $file['error'];
        
        if ($fileError !== UPLOAD_ERR_OK) {
            return false;
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($fileTmpName);
        if (!in_array($fileType, $allowedTypes)) {
            return false;
        }

        $newFileName = uniqid('apt_', true) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
        $targetPath = $targetDir . $newFileName;

        list($origWidth, $origHeight) = getimagesize($fileTmpName);
        $ratio = $origWidth / $origHeight;

        if ($maxWidth / $maxHeight > $ratio) {
            $newWidth = $maxHeight * $ratio;
            $newHeight = $maxHeight;
        } else {
            $newWidth = $maxWidth;
            $newHeight = $maxWidth / $ratio;
        }

        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        switch ($fileType) {
            case 'image/jpeg':
                $source = imagecreatefromjpeg($fileTmpName);
                imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
                imagejpeg($newImage, $targetPath, 90);
                break;
            case 'image/png':
                $source = imagecreatefrompng($fileTmpName);
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
                imagepng($newImage, $targetPath);
                break;
            case 'image/gif':
                $source = imagecreatefromgif($fileTmpName);
                imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
                imagegif($newImage, $targetPath);
                break;
        }

        imagedestroy($newImage);
        imagedestroy($source);

        return $newFileName;
    }

    // --- FUNGSI UNTUK APARTEMEN ---

    public function getAllApartments() {
        $query = "SELECT * FROM apartments ORDER BY id DESC";
        $result = $this->connection->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getApartmentDetails($id) {
        $query = "SELECT * FROM apartments WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function addApartment($name, $description, $address, $price, $status, $imageFile, $imageUrl) {
        $imagePath = null;
        if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->uploadAndResizeImage($imageFile, 'uploads/apartments/');
        } elseif (!empty($imageUrl)) {
            $imagePath = $imageUrl;
        }

        $query = "INSERT INTO apartments (name, description, address, price_per_month, status, image) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ssssss", $name, $description, $address, $price, $status, $imagePath);
        return $stmt->execute();
    }

    public function updateApartment($id, $name, $description, $address, $price, $status, $imageFile, $imageUrl) {
        $imagePath = null;
        if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->uploadAndResizeImage($imageFile, 'uploads/apartments/');
        } elseif (!empty($imageUrl)) {
            $imagePath = $imageUrl;
        }

        if ($imagePath !== null) {
            $query = "UPDATE apartments SET name = ?, description = ?, address = ?, price_per_month = ?, status = ?, image = ? WHERE id = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("ssssssi", $name, $description, $address, $price, $status, $imagePath, $id);
        } else {
            $query = "UPDATE apartments SET name = ?, description = ?, address = ?, price_per_month = ?, status = ? WHERE id = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("sssssi", $name, $description, $address, $price, $status, $id);
        }
        return $stmt->execute();
    }

    public function deleteApartmentAndTransactions($id) {
        $this->connection->begin_transaction();
        try {
            $stmt1 = $this->connection->prepare("DELETE FROM transactions WHERE apartment_id = ?");
            $stmt1->bind_param("i", $id);
            $stmt1->execute();

            $stmt2 = $this->connection->prepare("DELETE FROM apartments WHERE id = ?");
            $stmt2->bind_param("i", $id);
            $stmt2->execute();

            $this->connection->commit();
            return true;
        } catch (Exception $e) {
            $this->connection->rollback();
            return false;
        }
    }
    
    // --- FUNGSI UNTUK USER ---

    public function getAllUsers() {
        $query = "SELECT id, username, full_name, email, role, created_at FROM users ORDER BY id DESC";
        $result = $this->connection->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserDetails($id) {
        $query = "SELECT id, username, email, full_name, role, created_at FROM users WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateUser($id, $username, $fullName, $email, $role) {
    $query = "UPDATE users SET username = ?, full_name = ?, email = ?, role = ? WHERE id = ?";
    $stmt = $this->connection->prepare($query);
    $stmt->bind_param("ssssi", $username, $fullName, $email, $role, $id);
    return $stmt->execute();
}


    public function deleteUserAndTransactions($id) {
        $this->connection->begin_transaction();
        try {
            $stmt1 = $this->connection->prepare("DELETE FROM transactions WHERE user_id = ?");
            $stmt1->bind_param("i", $id);
            $stmt1->execute();

            $stmt2 = $this->connection->prepare("DELETE FROM users WHERE id = ?");
            $stmt2->bind_param("i", $id);
            $stmt2->execute();

            $this->connection->commit();
            return true;
        } catch (Exception $e) {
            $this->connection->rollback();
            return false;
        }
    }

    // --- FUNGSI UNTUK TRANSAKSI ---

    public function getAllTransactions() {
        $query = "SELECT tr.*, u.username, a.name as apartment_name FROM transactions tr JOIN users u ON tr.user_id = u.id JOIN apartments a ON tr.apartment_id = a.id ORDER BY tr.id DESC";
        $result = $this->connection->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTransactionDetails($id) {
        $query = "SELECT tr.*, u.username, a.name as apartment_name FROM transactions tr JOIN users u ON tr.user_id = u.id JOIN apartments a ON tr.apartment_id = a.id WHERE tr.id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateTransactionStatus($id, $status) {
        $query = "UPDATE transactions SET status = ? WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    public function getMyTransactions($userId) {
        $query = "SELECT tr.*, a.name as apartment_name FROM transactions tr JOIN apartments a ON tr.apartment_id = a.id WHERE tr.user_id = ? ORDER BY tr.transaction_date DESC";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAvailableApartments() {
        $query = "SELECT * FROM apartments WHERE status = 'available' ORDER BY name ASC";
        $result = $this->connection->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function createTransaction($userId, $apartmentId, $startDate, $endDate, $totalPrice) {
        $query = "INSERT INTO transactions (user_id, apartment_id, start_date, end_date, total_price, status) VALUES (?, ?, ?, ?, ?, 'pending')";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("iisss", $userId, $apartmentId, $startDate, $endDate, $totalPrice);
        return $stmt->execute();
    }
}