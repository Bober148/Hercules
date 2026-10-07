<?php
require_once __DIR__ . '/db.php';
session_start();
if (($_SESSION['role'] ?? '') !== 'teacher') {
    header('Location: teacherlogin.html');
    exit;
}
$conn = db();
$conn->set_charset("utf8mb4");
$students = $conn->query("SELECT StudentID, FirstName FROM students ORDER BY FirstName");
$lessons  = $conn->query("SELECT LessonID, LessonName FROM lessons ORDER BY LessonName");
$notice = "";
if (isset($_GET['created'])) { $notice = 'Запись добавлена в расписание.'; }
elseif (isset($_GET['error'])) { $notice = htmlspecialchars($_GET['error']); }
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <title>Панель учителя | Hercules</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="teacher.css" />
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
  <a href="teacher.php">Панель учителя</a>
</nav>

  </div>
</header>

<main class="page">
  <div class="container">

    <div class="head">
      <div>
        <h1>Панель учителя</h1>
        <p class="muted">
          Пока демо без базы данных. В будущем доступ будет только для учителей.
        </p>
      </div>
      <div class="head__actions">
        <a class="btn btn--light" href="schedule.php">Открыть расписание (как ученик)</a>
      </div>
    </div>

    <section class="grid">
      <!-- Create lesson/schedule -->
      <article class="card">
        <h2 class="h2">Поставить ученика на урок</h2>
        <p class="muted small">Выбери ученика и урок — запись появится в расписании.</p>
        <?php if ($notice !== ""): ?>
          <div class="note"><?php echo $notice; ?></div>
        <?php endif; ?>
        <form class="form" method="post" action="create_schedule.php">
          <label>
            Ученик
            <select name="student_id" required>
              <option value="">Выбери ученика</option>
              <?php while ($s = $students->fetch_assoc()): ?>
                <option value="<?php echo (int)$s['StudentID']; ?>"><?php echo htmlspecialchars($s['FirstName']); ?></option>
              <?php endwhile; ?>
            </select>
          </label>
          <label>
            Урок
            <select name="lesson_id" required>
              <option value="">Выбери урок</option>
              <?php while ($l = $lessons->fetch_assoc()): ?>
                <option value="<?php echo (int)$l['LessonID']; ?>"><?php echo htmlspecialchars($l['LessonName']); ?></option>
              <?php endwhile; ?>
            </select>
          </label>
          <div class="row2">
            <label>
              Дата
              <input type="date" name="session_date" />
            </label>
            <label>
              Время
              <input type="time" name="session_time" />
            </label>
          </div>
          <label>
            Место
            <input type="text" name="location" placeholder="Зал / Кабинет" />
          </label>
          <button class="btn btn--primary btn--full" type="submit">Добавить в расписание</button>
        </form>
      </article>

          </section>

    <section class="card table-card">
      <div class="table-head">
        <h2 class="h2">Ближайшие уроки</h2>
        <p class="muted small">Демо-таблица. В будущем будет из БД.</p>
      </div>

      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th>Дата</th>
              <th>Время</th>
              <th>Урок</th>
              <th>Тип</th>
              <th>Группа</th>
              <th>Место</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>—</td>
              <td>16:30</td>
              <td>Силовая тренировка</td>
              <td><span class="pill">Силовая</span></td>
              <td>A</td>
              <td>Зал</td>
            </tr>
            <tr>
              <td>—</td>
              <td>16:30</td>
              <td>Кардио + ОФП</td>
              <td><span class="pill">Кардио</span></td>
              <td>A</td>
              <td>Зал</td>
            </tr>
            <tr>
              <td>—</td>
              <td>15:40</td>
              <td>Лекция: питание и добавки</td>
              <td><span class="pill">Лекция</span></td>
              <td>Все</td>
              <td>Кабинет</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

  </div>
</main>

<footer class="footer">
  <div class="container">Hercules © 2026</div>
</footer>

</body>
</html>
