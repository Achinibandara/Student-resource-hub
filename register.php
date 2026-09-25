<?php

include "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../register.html");
    exit();
}

$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";
$confirm_password = $_POST["confirm_password"] ?? "";

if ($name === "" || $email === "" || $password === "" || $confirm_password === "") {
    echo "<script>
            alert('Please fill in all fields.');
            window.location.href = '../register.html';
          </script>";
    exit();
}

if ($password !== $confirm_password) {
    echo "<script>
            alert('Passwords do not match.');
            window.location.href = '../register.html';
          </script>";
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>
            alert('Please enter a valid email address.');
            window.location.href = '../register.html';
          </script>";
    exit();
}


/* Check whether email already exists */

$check_sql = "SELECT id FROM users WHERE email = ?";

$check_stmt = $conn->prepare($check_sql);

if (!$check_stmt) {
    die("Database error: " . $conn->error);
}

$check_stmt->bind_param("s", $email);
$check_stmt->execute();

$result = $check_stmt->get_result();

if ($result->num_rows > 0) {

    echo "<script>
            alert('This email is already registered.');
            window.location.href = '../register.html';
          </script>";

    $check_stmt->close();
    $conn->close();
    exit();
}

$check_stmt->close();


/* Hash password */

$hashed_password = password_hash($password, PASSWORD_DEFAULT);


/* Insert new user */

$sql = "INSERT INTO users (name, email, password)
        VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Database error: " . $conn->error);
}

$stmt->bind_param("sss", $name, $email, $hashed_password);

if ($stmt->execute()) {

    echo "<script>
            alert('Registration successful! Please login.');
            window.location.href = '../login.html';
          </script>";

} else {

    echo "<script>
            alert('Registration failed. Please try again.');
            window.location.href = '../register.html';
          </script>";
}

$stmt->close();
$conn->close();

?>