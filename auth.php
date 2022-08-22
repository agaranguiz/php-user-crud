<?php
  require "./vendor/autoload.php";
  include_once './config/database.php';

  header("Access-Control-Allow-Origin: * ");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->safeLoad();

  use \Firebase\JWT\JWT;

  $database = new Database();
  $database->init();
  $conn = $database->getConnection();

  $table_name = 'users';

  $data = json_decode(file_get_contents("php://input"));    

  if(isset($data->username) && isset($data->password)) {
    $username = $data->username;
    $password = $data->password;
    $query = "SELECT id, username, password FROM " . $table_name . " WHERE username = :username LIMIT 0, 1";
    $statement = $conn->prepare($query);
    $statement->bindParam(':username', $username);
    $statement->execute();
    $results = $statement->rowCount();
    if($results > 0) {
      $row = $statement->fetch(PDO::FETCH_ASSOC);
      $id = $row['id'];
      $username = $row['username'];
      $password2 = $row['password'];

      if(password_verify($password, $password2)) {
        $secret_key = $_ENV['AUTH_SECRET'];
        $issuer_claim = $_ENV['AUTH_ISSUER']; // this can be the servername
        $audience_claim = $_ENV['AUTH_AUDIENCE'];
        $issuedat_claim = time(); // issued at
        $notbefore_claim = $issuedat_claim + 10; //not before in seconds
        $expire_claim = $issuedat_claim + 600; // expire time in seconds
        $token = array(
          "iss" => $issuer_claim,
          "aud" => $audience_claim,
          "iat" => $issuedat_claim,
          "nbf" => $notbefore_claim,
          "exp" => $expire_claim,
          "data" => array(
            "id" => $id,
            "username" => $username
        ));

        http_response_code(200);

        $jwt = JWT::encode($token, $secret_key, 'HS256');
        echo json_encode(
          array(
            "message" => "Successful login.",
            "jwt" => $jwt,
            "username" => $username,
            "expireAt" => $expire_claim
          ));
      } else {
        http_response_code(401);
        echo json_encode(array("message" => "Login failed.", "password" => $password));
      }
    } else {
      http_response_code(401);
        echo json_encode(array("message" => "Login failed.", "password" => $password));
    }
  } 

?>