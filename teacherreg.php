<?php
require_once __DIR__ . '/db.php';

$conn = db();
$conn->set_charset("utf8mb4");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

$fname = trim($_POST["fname"] ?? "");
$lname = trim($_POST["lname"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";
$confirm  = $_POST["confirm_password"] ?? "";
$invite   = $_POST["invite_code"] ?? "";

// Регистрация учителя только по коду приглашения из config.php
if (is_file(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
}
$inviteCode = defined('TEACHER_INVITE_CODE') ? TEACHER_INVITE_CODE : '';
if ($inviteCode === '') {
    http_response_code(403);
    die("Регистрация учителей закрыта.");
}
if (!is_string($invite) || !hash_equals($inviteCode, $invite)) {
    http_response_code(403);
    die("Неверный код приглашения.");
}

if ($fname === "" || $lname === "" || $phone === "" || $email === "" || $password === "" || $confirm === "") {
    die("All fields are required.");
}

if ($password !== $confirm) {
    die("Passwords do not match.");
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    "INSERT INTO teachers (FirstName, LastName, Phone_Number, Email, PasswordHash)
     VALUES (?, ?, ?, ?, ?)"
);

$stmt->bind_param("sssss", $fname, $lname, $phone, $email, $hash);

try {
    $stmt->execute();
    echo "Teacher registered successfully. TeacherID: " . $stmt->insert_id;
} catch (mysqli_sql_exception $e) {
    // чаще всего: UNIQUE Email
    error_log("DB error: " . $e->getMessage());
    echo "Произошла ошибка. Попробуйте позже.";
}

$stmt->close();
$conn->close();
?>
