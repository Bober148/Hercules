<?php
require_once __DIR__ . '/db.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$conn = db();

$id = $_SESSION["user_id"];

$stmt = $conn->prepare("SELECT FirstName, Phone, PR FROM students WHERE StudentID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$name = $user["FirstName"];
$phone = $user["Phone"];
$pr = $user["PR"];

$stmt->close();
$conn->close();
?>



<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Профиль | Hercules</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="profile.css">
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
  <a href="schedule.php">Расписание</a>
  <a href="change_password.php">Сменить пароль</a>
  <a href="logout.php">Выйти</a>

</nav>

  </div>
</header>

<main class="page">
  <div class="container profile">

    <!-- Левая колонка -->
    <section class="profile-card">
      <div class="avatar">👤</div>
      <h2 class="name"><?php echo htmlspecialchars($name); ?></h2>

      <p class="role">Участник клуба Hercules</p>

<div class="info">
  <div class="info-row">
    <span>Телефон</span>
    <strong> <?php echo htmlspecialchars($phone); ?> </strong>
  </div>

  <div class="info-row">
    <span>Личный рекорд (PR)</span>
    <strong> <?php echo htmlspecialchars($pr); ?> кг </strong>
</div>

    </section>

    <!-- Правая колонка -->
    <section class="profile-content">
      <div class="block">
        <h3>Личные рекорды (PR)</h3>
        <ul class="pr-list">
          <li><span>Жим лёжа</span><strong>80 кг</strong></li>
          <li><span>Присед</span><strong>100 кг</strong></li>
          <li><span>Становая тяга</span><strong>120 кг</strong></li>
        </ul>
      </div>

      <div class="block">
        <h3>Посещаемость</h3>
        <div class="attendance">
          <div class="att-box">
            <strong>12</strong>
            <span>Посещено</span>
          </div>
          <div class="att-box">
            <strong>2</strong>
            <span>Пропущено</span>
          </div>
        </div>
      </div>

      <div class="block">
        <h3>Статус</h3>
        <p class="status">
          Активный участник клуба. Регулярно посещает тренировки.
        </p>
      </div>
    </section>

  </div>
</main>

<footer class="footer">
  <div class="container">
    Hercules © 2026
  </div>
</footer>

<script>
(function(){
  const root = document.documentElement;
  const saved = localStorage.getItem("theme");
  if (saved === "dark") root.setAttribute("data-theme","dark");

  const btn = document.getElementById("themeToggle");
  if (!btn) return;

  btn.addEventListener("click", () => {
    const isDark = root.getAttribute("data-theme") === "dark";
    if (isDark) {
      root.removeAttribute("data-theme");
      localStorage.setItem("theme","light");
    } else {
      root.setAttribute("data-theme","dark");
      localStorage.setItem("theme","dark");
    }
  });
})();
</script>
</body>
</html>
