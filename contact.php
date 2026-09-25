<?php

include "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../contact.html");
    exit();
}

$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$message = trim($_POST["message"] ?? "");

if ($name === "" || $email === "" || $message === "") {
    die("Please fill in all fields.");
}

$sql = "INSERT INTO messages (name, email, message)
        VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("sss", $name, $email, $message);

if ($stmt->execute()) {

    echo "<script>
            alert('Message sent successfully!');
            window.location.href = '../contact.html';
          </script>";

} else {

    die("Database Error: " . $stmt->error);
}

$stmt->close();
$conn->close();

?>
