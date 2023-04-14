<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");

include 'DbConnection.php';

$objDb = new DbConnect;
$conn = $objDb->connect();
$method = $_SERVER['REQUEST_METHOD'];

class Book {
  public $id;
  public $sku;
  public $name;
  public $price;
  public $weight;
  
  public function __construct($sku, $name, $price, $weight) {
    $this->sku = $sku;
    $this->name = $name;
    $this->price = $price;
    $this->weight = $weight;
  }
  
  public function getId() {
    return $this->id;
  }
  
  public function getSku() {
    return $this->sku;
  }
  
  public function getName() {
    return $this->name;
  }
  
  public function getPrice() {
    return $this->price;
  }
  
  public function getWeight() {
    return $this->weight;
  }
  
    public function save($conn, $products) {
      $sql = 'INSERT INTO `book`(id, sku, name, price, weight) VALUES (null, :sku, :name, :price, :weight)';
      $statm = $conn->prepare($sql);
      $statm->bindParam(':sku', $products->sku);
      $statm->bindParam(':name', $products->name);
      $statm->bindParam(':price', $products->price);
      $statm->bindParam(':weight', $products->weight);
      
      $success = $statm->execute();
      return $success;
  }
  
    public function delete($conn, $id) {
      $sql = "DELETE FROM book WHERE id = :id";
      $statm = $conn->prepare($sql);
      $statm->bindParam(':id', $id);
      $success = $statm->execute();
      return $success;
  }


}
class DVD {
  public $id;
  public $sku;
  public $name;
  public $price;
  public $size;
  
  public function __construct($sku, $name, $price, $size) {
    $this->sku = $sku;
    $this->name = $name;
    $this->price = $price;
    $this->size = $size;
  }
  
  public function getId() {
    return $this->id;
  }
  
  public function getSku() {
    return $this->sku;
  }
  
  public function getName() {
    return $this->name;
  }
  
  public function getPrice() {
    return $this->price;
  }
  
  public function getSize() {
    return $this->size;
  }
  
  public function save($conn, $product) {
    $sql = 'INSERT INTO `dvd`(id, sku, name, price, size) VALUES (null, :sku, :name, :price, :size)';
    $statm = $conn->prepare($sql);
    $statm->bindParam(':sku', $product->sku);
    $statm->bindParam(':name', $product->name);
    $statm->bindParam(':price', $product->price);
    $statm->bindParam(':size', $product->size);
    
    $success = $statm->execute();
    return $success;
  }
  
  public function delete($conn, $id) {
    $sql = "DELETE FROM dvd WHERE id = :id";
    $statm = $conn->prepare($sql);
    $statm->bindParam(':id', $id);
    $success = $statm->execute();
    return $success;
  }

  
}
class Furniture {
  public $id;
  public $sku;
  public $name;
  public $price;
  public $width;
  public $height;
  public $length;
  
  public function __construct($sku, $name, $price, $width, $height, $length) {
    $this->sku = $sku;
    $this->name = $name;
    $this->price = $price;
    $this->width = $width;
    $this->height = $height;
    $this->length = $length;
  }
  
  public function getId() {
    return $this->id;
  }
  
  public function getSku() {
    return $this->sku;
  }
  
  public function getName() {
    return $this->name;
  }
  
  public function getPrice() {
    return $this->price;
  }
  
  public function getWidth() {
    return $this->width;
  }
  public function getHeight() {
    return $this->height;
  }
  public function getLength() {
    return $this->length;
  }
  
  public function save($conn, $product) {
    $sql = 'INSERT INTO `furniture`(id, sku, name, price, width, height, length) VALUES (null, :sku, :name, :price, :width, :height, :length)';
    $statm = $conn->prepare($sql);
    $statm->bindParam(':sku', $product->sku);
    $statm->bindParam(':name', $product->name);
    $statm->bindParam(':price', $product->price);
    $statm->bindParam(':width', $product->width);
    $statm->bindParam(':height', $product->height);
    $statm->bindParam(':length', $product->length);
    
    $success = $statm->execute();
    return $success;
  }
  
  public function delete($conn, $id) {
    $sql = "DELETE FROM furniture WHERE id = :id";
    $statm = $conn->prepare($sql);
    $statm->bindParam(':id', $id);
    $success = $statm->execute();
    return $success;
  }

  
}

switch($method) {
  case 'GET':
    $sql = "SELECT 'book' AS type, id, sku, name, price, weight, NULL AS size, NULL AS height, NULL AS width, NULL AS length 
            FROM book
            UNION
            SELECT 'dvd' AS type, id, sku, name, price, NULL AS weight, size, NULL AS height, NULL AS width, NULL AS length 
            FROM dvd
            UNION
            SELECT 'furniture' AS type, id, sku, name, price, NULL AS weight, NULL AS size, height, width, length 
            FROM furniture";
    $statm = $conn->prepare($sql);
    $statm->execute();
    $products = $statm->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($products);
    break;

  case 'POST': 
    $product = json_decode(file_get_contents('php://input'));

    switch($product->type) {
      case 'book':
        $book = new Book($product->sku, $product->name, $product->price, $product->weight);
        $book->save($conn, $product);      
        break;
      case 'dvd':
        $dvd = new DVD($product->sku, $product->name, $product->price, $product->size);
        $dvd->save($conn, $product);
        break;
      case 'furniture':
        $furniture = new Furniture($product->sku, $product->name, $product->price, $product->width, $product->height, $product->length);
        $furniture->save($conn, $product);
        break;
    }
    echo json_encode($response);
    break;
    
    case 'DELETE':
      $products = json_decode( file_get_contents('php://input') );
      foreach($products as $product) {
          switch($product->type) {
              case 'book':
                $book = new Book($product->id, $product->sku, $product->name, $product->price, $product->weight);
                $book->delete($conn, $product->id);
                  break;
              case 'dvd':
                  $dvd = new DVD($product->id, $product->sku, $product->name, $product->price, $product->size);
                  $dvd->delete($conn, $product->id);
                  break;
              case 'furniture':
                  $furniture = new Furniture($product->id, $product->sku, $product->name, $product->price, $product->width, $product->height, $product->length);
                  $furniture->delete($conn, $product->id);
                  break;
          }
      }
      break;
}