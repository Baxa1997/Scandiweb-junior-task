
<?php
    class DbConnect {
        public $server ='localhost';
        public $dbname ='shoxdevu_scandiweb_products';
        public $user ='shoxdevu_baha';
        public $pass ='7116812Baxa';
        
        public function connect () {
            try {
                $conn = new PDO('mysql:host=' .$this->server .';dbname=' .$this->dbname .';user=' .$this->user .';password=' .$this->pass);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $conn;
            } catch(\Exception $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
    } 
     
        
?>