<?php
class Kategori {
    private $conn;
    private $table_name = "kategori";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($nama_kategori, $deskripsi) {
        $query = "INSERT INTO " . $this->table_name . " (nama_kategori, deskripsi) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$nama_kategori, $deskripsi]);
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function update($id, $nama_kategori, $deskripsi) {
        $query = "UPDATE " . $this->table_name . " SET nama_kategori = ?, deskripsi = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$nama_kategori, $deskripsi, $id]);
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>