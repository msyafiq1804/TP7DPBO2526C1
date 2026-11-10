<?php
class Makanan {
    private $conn;
    private $table_name = "makanan";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($nama_makanan, $kategori_id, $restoran_id, $kalori, $harga) {
        $query = "INSERT INTO " . $this->table_name . " (nama_makanan, kategori_id, restoran_id, kalori, harga) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$nama_makanan, $kategori_id, $restoran_id, $kalori, $harga]);
    }

    public function read() {
        $query = "SELECT m.id, m.nama_makanan, m.kalori, m.harga, m.kategori_id, m.restoran_id,
                         k.nama_kategori, r.nama_restoran 
                  FROM " . $this->table_name . " m
                  JOIN kategori k ON m.kategori_id = k.id
                  JOIN restoran r ON m.restoran_id = r.id
                  ORDER BY m.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function update($id, $nama_makanan, $kategori_id, $restoran_id, $kalori, $harga) {
        $query = "UPDATE " . $this->table_name . " SET nama_makanan = ?, kategori_id = ?, restoran_id = ?, kalori = ?, harga = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$nama_makanan, $kategori_id, $restoran_id, $kalori, $harga, $id]);
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>