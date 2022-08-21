<?php
  require "./vendor/autoload.php";
  include_once './config/database.php';

  header("Access-Control-Allow-Origin: * ");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Methods: DELETE,GET,POST,PUT");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->safeLoad();

  $database = new Database();
  $database->init();
  $conn = $database->getConnection();

  $table_name = 'users';

  $method = $_SERVER['REQUEST_METHOD'];
  if($method === 'POST') {

    $data = json_decode(file_get_contents("php://input"));    

    if(isset($data->username) && isset($data->password)) {
      $username = $data->username;
      $password = $data->password;
      $query = "INSERT INTO " . $table_name . "
            SET username = :username,
                password = :password";

      $statement = $conn->prepare($query);
      $statement->bindParam(':username', $username);
      $password_hash = password_hash($password, PASSWORD_BCRYPT);
      $statement->bindParam(':password', $password_hash);

      if($statement->execute()){
      http_response_code(200);
      echo json_encode(array("message" => "User was successfully registered."));
      }
      else{
      http_response_code(400);
      echo json_encode(array("message" => "Unable to register the user."));
      }
    } else {
      http_response_code(400);
      echo json_encode(array("message" => "Missing username or password."));
    }    
  }
  if($method === 'GET') { 
    if(isset($_GET['id'])) {
      $id = $_GET['id'];
      $query = "SELECT * FROM " . $table_name . " WHERE id = :id";
      $statement = $conn->prepare($query);
      $statement->bindParam(':id', $id);

      if($statement->execute()){
        http_response_code(200);
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(array("rows" => $rows));
      }
      else{
        http_response_code(400);
        echo json_encode(array("message" => "Unable to retrieve data."));
      }
    } else {
      $query = "SELECT * FROM " . $table_name;
      $statement = $conn->prepare($query);

      if($statement->execute()){
        http_response_code(200);
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(array("rows" => $rows));
      }
      else{
        http_response_code(400);
        echo json_encode(array("message" => "Unable to retrieve data."));
      }
    }
  }
  if($method === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));    

    if(isset($data->username) && isset($data->password) && isset($_GET['id'])) {
      $id = $_GET['id'];
      $username = $data->username;
      $password = $data->password;
      $query = "UPDATE " . $table_name . " SET username = :username, password = :password WHERE id = :id";

      $statement = $conn->prepare($query);
      $statement->bindParam(':id', $id);
      $statement->bindParam(':username', $username);
      $password_hash = password_hash($password, PASSWORD_BCRYPT);
      $statement->bindParam(':password', $password_hash);

      if($statement->execute()){
      http_response_code(200);
      echo json_encode(array("message" => "User was successfully updated."));
      }
      else{
      http_response_code(400);
      echo json_encode(array("message" => "Unable to update the user."));
      }
    } else {
      http_response_code(400);
      echo json_encode(array("message" => "Missing id, username or password."));
    }
  }
  if($method === 'DELETE') { 
    if(isset($_GET['id'])) {
      $id = $_GET['id'];
      $query = "DELETE FROM " . $table_name . " WHERE id = :id";
      $statement = $conn->prepare($query);
      $statement->bindParam(':id', $id);

      if($statement->execute()){
        http_response_code(200);
        echo json_encode(array("message" => "User was successfully deleted."));
      }
      else{
        http_response_code(400);
        echo json_encode(array("message" => "Unable to delete data."));
      }
    } else {
      http_response_code(400);
      echo json_encode(array("message" => "Missing id."));
    }
  }

?>