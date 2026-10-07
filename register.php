<?php
require_once __DIR__ . '/db.php';

try {
    $conn = db();
    $conn->set_charset("utf8mb4");

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        die("Invalid request method. Use the registration form.");
    }

    $fname = trim($_POST["fname"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm  = $_POST["confirm_password"] ?? "";

    if ($fname === "" || $phone === "" || $email === "" || $password === "") {
        die("All fields are required.");
    }

    if ($password !== $confirm) {
    die("Passwords do not match.");
}

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "INSERT INTO students (FirstName, Phone, PR, Email, PasswordHash)
         VALUES (?, ?, '', ?, ?)"
    );

    $stmt->bind_param("ssss", $fname, $phone, $email, $hash);
    $stmt->execute();

    echo "Registration successful. StudentID: " . $stmt->insert_id;

    $stmt->close();
    $conn->close();

} catch (mysqli_sql_exception $e) {
    // Самая частая причина: UNIQUE по Email
    error_log("DB error: " . $e->getMessage());
    echo "Произошла ошибка. Попробуйте позже.";
}
header("Location: main.php");
            exit;
?>
