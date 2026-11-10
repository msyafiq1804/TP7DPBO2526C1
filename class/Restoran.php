<?php
class Restoran {
    private $conn;
    private $table_name = "restoran";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($nama_restoran, $alamat, $jam_operasional) {
        $query = "INSERT INTO " . $this->table_name . " (nama_restoran, alamat, jam_operasional) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$nama_restoran, $alamat, $jam_operasional]);
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function update($id, $nama_restoran, $alamat, $jam_operasional) {
        $query = "UPDATE " . $this->table_name . " SET nama_restoran = ?, alamat = ?, jam_operasional = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$nama_restoran, $alamat, $jam_operasional, $id]);
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>