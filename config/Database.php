<?php 
class Database {
  private $db_host;
  private $db_name;
  private $db_user;
  private $db_password;
  private $connection;

  public function init() {
    $this->db_host = $_ENV['DB_HOST'];
    $this->db_name = $_ENV["DB_NAME"];
    $this->db_user = $_ENV["DB_USER"];
    $this->db_password = $_ENV["DB_PASSWORD"];
  }

  public function getConnection() {
    $this->connection = null;

    try {
        $this->connection = new PDO("mysql:host=" . $this->db_host . ";dbname=" . $this->db_name, $this->db_user, $this->db_password);
        echo "Connection successful!";
    } catch(PDOException $exception){
        echo "Connection failed: " . $exception->getMessage();
    }

    return $this->connection; 
  }

}
?>