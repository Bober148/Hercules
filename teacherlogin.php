<?php
require_once __DIR__ . '/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: teacherlogin.html');
    exit;
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    header('Location: teacherlogin.html?error=' . urlencode('Введите корректный email и пароль.'));
    exit;
}

try {
    $conn = db();
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    error_log('DB connection error: ' . $e->getMessage());
    header('Location: teacherlogin.html?error=' . urlencode('Ошибка соединения с базой данных.'));
    exit;
}

$stmt = $conn->prepare(
    'SELECT TeacherID, FirstName, LastName, PasswordHash FROM teachers WHERE Email = ? LIMIT 1'
);
$stmt->bind_param("s", $email);
$stmt->execute();
$teacher = $stmt->get_result()->fetch_assoc();

if (!$teacher || $teacher['PasswordHash'] === null
        || !password_verify($password, $teacher['PasswordHash'])) {
    header('Location: teacherlogin.html?error=' . urlencode('Неверный email или пароль.'));
    exit;
}

session_regenerate_id(true);
$_SESSION['logged_in'] = true;
$_SESSION['role']      = 'teacher';
$_SESSION['user_id']   = (int)$teacher['TeacherID'];
$_SESSION['user_name'] = $teacher['FirstName'];

header('Location: teacher.php');
exit;
