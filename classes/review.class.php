<?php
class Review
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function selectByProductId($id, $limit = 3, $offset = 0)
    {
        $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE product_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
        $stmt->bind_param("iii", $id, $limit, $offset);
        $stmt->execute();
        return $stmt->get_result();
        // $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE product_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
        // if ($stmt === false) {
        //     die("Failed to prepare SQL statement: " . $this->conn->error);
        // }
        // $stmt->bind_param("iii", $id, $limit, $offset);
    }

    public function insert($user_id, $product_id, $rating, $review_text)
    {
        $query = "INSERT INTO reviews (user_id, product_id, rating, review_text) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiis", $user_id, $product_id, $rating, $review_text);
        $stmt->execute();
    }
}
?>