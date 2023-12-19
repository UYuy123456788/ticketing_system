<?php

function registerUser($email, $password, $name) {
  $servername = 'localhost';
  $username = 'root';
  $dbpassword = '';
  $dbname = "helpdesk_system";

  $conn = new mysqli($servername, $username, $dbpassword, $dbname);

  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $email = $conn->real_escape_string($email);
  $password = password_hash($conn->real_escape_string($password), PASSWORD_DEFAULT);
  $name = $conn->real_escape_string($name);

  $createDate = date("Y-m-d H:i:s");
  $userType = 'user'; 
  $status = 1;  

  $stmt = $conn->prepare("INSERT INTO hd_users (email, password, create_date, name, user_type, status) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssssi", $email, $password, $createDate, $name, $userType, $status);

  if ($stmt->execute()) {
      return "Registration successful!";
  } else {
      return "Error: " . $stmt->error;
  }

  $stmt->close();
  $conn->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register_email']) && isset($_POST['register_password']) && isset($_POST['register_name'])) {
  // Retrieve form data
  $email = $_POST['register_email'];
  $password = $_POST['register_password'];
  $name = $_POST['register_name'];

  // Call the registerUser function
  $registrationResult = registerUser($email, $password, $name);
  echo $registrationResult; // This will be handled in the HTML page
}
?>
