<?php
require_once __DIR__ . '/db.php';

session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
  header("Location: login.html");
  exit;
}

$role = $_SESSION["role"] ?? "student";
$userId = (int)($_SESSION["user_id"] ?? 0);

if ($userId <= 0) {
  die("Session user_id missing.");
}

$conn = db();
$conn->set_charset("utf8mb4");

$table = ($role === "teacher") ? "teachers" : "students";
$idCol = ($role === "teacher") ? "TeacherID" : "StudentID";

$err = "";
$ok = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $current = $_POST["current_password"] ?? "";
  $newpw   = $_POST["new_password"] ?? "";
  $confirm = $_POST["confirm_password"] ?? "";

  if ($current === "" || $newpw === "" || $confirm === "") {
    $err = "Заполни все поля.";
  } elseif ($newpw !== $confirm) {
    $err = "Новые пароли не совпадают.";
  } elseif (strlen($newpw) < 8 || !preg_match('/[A-Z]/', $newpw)) {
    $err = "Новый пароль: минимум 8 символов и 1 заглавная буква.";
  } else {

    $stmt = $conn->prepare("SELECT PasswordHash FROM $table WHERE $idCol = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();

    if (!$row) {
      $err = "Пользователь не найден.";
    } elseif (!password_verify($current, $row["PasswordHash"])) {
      $err = "Текущий пароль неверный.";
    } else {

      $newHash = password_hash($newpw, PASSWORD_DEFAULT);
      $stmt2 = $conn->prepare("UPDATE $table SET PasswordHash = ? WHERE $idCol = ?");
      $stmt2->bind_param("si", $newHash, $userId);
      $stmt2->execute();
      $stmt2->close();

      $ok = "Пароль успешно изменён.";
    }
  }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Смена пароля | Hercules</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    *{box-sizing:border-box;margin:0;padding:0;font-family:Arial,sans-serif}
    body{background:#f4f6f8;color:#111}
    .container{width:90%;max-width:520px;margin:auto}
    .topbar{background:rgba(17,17,17,.95);border-bottom:1px solid rgba(255,255,255,.08)}
    .topbar__inner{display:flex;justify-content:space-between;align-items:center;padding:14px 0}
    .brand{display:flex;gap:10px;color:#fff;text-decoration:none;align-items:center}
    .brand__mark{width:34px;height:34px;background:#fff;color:#111;border-radius:10px;display:grid;place-items:center;font-weight:700}
    .nav{display:flex;gap:16px;flex-wrap:wrap}
    .nav a{color:#fff;text-decoration:none;font-size:14px;opacity:.85}
    .nav a:hover{opacity:1}
    .page{padding:40px 0}
    .card{background:#fff;border-radius:16px;padding:22px;box-shadow:0 12px 30px rgba(0,0,0,.08)}
    h1{font-size:22px;margin-bottom:8px}
    .muted{color:#666;font-size:14px;margin-bottom:14px}
    form{display:grid;gap:12px}
    label{display:grid;gap:6px;font-size:14px}
    input{padding:10px 12px;border-radius:10px;border:1px solid #d7dde6;font-size:14px}
    input:focus{outline:none;border-color:#111}
    .btn{padding:12px;border-radius:12px;border:1px solid #111;background:#111;color:#fff;cursor:pointer}
    .msg{margin-top:12px;padding:10px 12px;border-radius:12px;border:1px solid #e6e9ee;background:#f7f8fb;font-size:14px}
    .msg.err{background:#ffecec;border-color:#ffc6c6}
    .msg.ok{background:#eaf7ef;border-color:#bfe7cb}
  </style>
</head>
<body>

<header class="topbar">
  <div class="container topbar__inner">
    <a class="brand" href="main.php">
      <span class="brand__mark">H</span>
      <span class="brand__name">Hercules</span>
    </a>
    <nav class="nav">
      <a href="main.php">Главная</a>
      <a href="profile.php">Профиль</a>
      <a href="schedule.php">Расписание</a>
      <?php if (($_SESSION["role"] ?? "") === "teacher"): ?>
        <a href="teacher.php">Панель учителя</a>
      <?php endif; ?>
      <a href="logout.php">Выйти</a>
    </nav>
  </div>
</header>

<main class="page">
  <div class="container">
    <section class="card">
      <h1>Смена пароля</h1>
      <p class="muted">Роль: <?php echo htmlspecialchars($role); ?></p>

      <form method="POST" action="change_password.php">
        <label>
          Текущий пароль
          <input type="password" name="current_password" required>
        </label>

        <label>
          Новый пароль
          <input type="password" name="new_password" required minlength="8">
        </label>

        <label>
          Повторите новый пароль
          <input type="password" name="confirm_password" required minlength="8">
        </label>

        <button class="btn" type="submit">Сохранить</button>
      </form>

      <?php if ($err !== ""): ?>
        <div class="msg err"><?php echo htmlspecialchars($err); ?></div>
      <?php endif; ?>

      <?php if ($ok !== ""): ?>
        <div class="msg ok"><?php echo htmlspecialchars($ok); ?></div>
      <?php endif; ?>
    </section>
  </div>
</main>

</body>
</html>