<?php
  require "vendor/autoload.php";
  include_once './config/database.php';

  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->safeLoad();

  $database = new Database();
  $database->init();
  $database->getConnection();

  
?>