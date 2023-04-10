<?php
class Product {
    private $conn;
    private $table = "products";

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function selectAll() {
        $query = "SELECT * FROM " . $this->table;
        $result = $this->conn->query($query);
        return $result;
    }

    public function selectOne($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    public function insert($seller_id, $name, $description, $price, $image_url) {
        $query = "INSERT INTO " . $this->table . " (seller_id, name, description, price, image_url) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issds", $seller_id, $name, $description, $price, $image_url);
        return $stmt->execute();
    }

    public function update($id, $seller_id, $name, $description, $price, $image_url) {
        $query = "UPDATE " . $this->table . " SET seller_id = ?, name = ?, description = ?, price = ?, image_url = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issdsi", $seller_id, $name, $description, $price, $image_url, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
