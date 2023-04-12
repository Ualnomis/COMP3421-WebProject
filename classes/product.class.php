<?php
class Product
{
    private $conn;
    private $table = "products";
    const DEFAULT_IMAGE_URL = "../assets/images/dummy_product_icon.png";

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function select_all()
    {
        return $this->query("SELECT * FROM {$this->table} WHERE status != 'deleted' ORDER BY id DESC");
    }

    public function select_one($id)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE id = ? AND status != 'deleted'", "i", [$id]);
    }

    public function insert($seller_id, $name, $description, $price, $quantity, $image_url, $status = 'show')
    {
        $image_url = empty($image_url) ? self::DEFAULT_IMAGE_URL : $image_url;
        return $this->query("INSERT INTO {$this->table} (seller_id, name, description, price, quantity, image_url, status) VALUES (?, ?, ?, ?, ?, ?, ?)", "issdiss", [$seller_id, $name, $description, $price, $quantity, $image_url, $status], true);
    }

    public function update($id, $seller_id, $name, $description, $price, $quantity, $image_url, $status)
    {
        $image_url = empty($image_url) ? self::DEFAULT_IMAGE_URL : $image_url;
        return $this->query("UPDATE {$this->table} SET seller_id = ?, name = ?, description = ?, price = ?, quantity = ?, image_url = ?, status = ? WHERE id = ?", "sssdissi", [$seller_id, $name, $description, $price, $quantity, $image_url, $status, $id], true);
    }

    public function delete($id)
    {
        return $this->query("UPDATE {$this->table} SET status = 'deleted' WHERE id = ?", "i", [$id]);
    }

    public function decrease_product_quantity($product_id, $quantity_to_decrease)
    {
        return $this->query("UPDATE {$this->table} SET quantity = quantity - ? WHERE id = ? AND status != 'deleted'", "ii", [$quantity_to_decrease, $product_id], true);
    }

    private function query($query, $bind_types = "", $params = [], $return_affected_rows = false)
    {
        $stmt = $this->conn->prepare($query);

        if (!empty($bind_types) && !empty($params)) {
            $stmt->bind_param($bind_types, ...$params);
        }

        $stmt->execute();

        if ($return_affected_rows) {
            $affected_rows = $stmt->affected_rows;
            $stmt->close();
            return $affected_rows;
        }

        return $stmt->get_result();
    }
}
?>
