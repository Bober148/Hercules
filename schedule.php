<?php
require_once __DIR__ . '/db.php';

session_start();

$conn = db();
$conn->set_charset("utf8mb4");

$student = trim($_GET["student"] ?? "");
$teacher = trim($_GET["teacher"] ?? "");
$lesson  = trim($_GET["lesson"] ?? "");
$sort = trim($_GET["sort"] ?? "id");
$dir  = strtolower(trim($_GET["dir"] ?? "desc"));

$allowedSort = [
  "id"      => "s.ScheduleID",
  "student" => "st.FirstName",
  "teacher" => "t.FirstName",
  "lesson"  => "l.LessonName",
];

$orderBy = $allowedSort["id"];
if (isset($allowedSort[$sort])) {
  $orderBy = $allowedSort[$sort];
}

$orderDir = ($dir === "asc") ? "ASC" : "DESC";

$sql = "
SELECT 
  s.ScheduleID,
  st.FirstName AS StudentName,
  t.FirstName  AS TeacherName,
  l.LessonName,
  s.SessionDate,
  s.SessionTime,
  s.Location
FROM schedule s
LEFT JOIN students st ON st.StudentID = s.StudentID
LEFT JOIN teachers t ON t.TeacherID = s.TeacherID
LEFT JOIN lessons l ON l.LessonID = s.LessonID
";

$conditions = [];
$params = [];
$types = "";


if ($student !== "") {
    $conditions[] = "st.FirstName LIKE ?";
    $params[] = "%$student%";
    $types .= "s";
}


if ($teacher !== "") {
    $conditions[] = "t.FirstName LIKE ?";
    $params[] = "%$teacher%";
    $types .= "s";
}

if ($lesson !== "") {
    $conditions[] = "l.LessonName LIKE ?";
    $params[] = "%$lesson%";
    $types .= "s";
}

if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY $orderBy $orderDir";

$stmt = $conn->prepare($sql);

if ($types !== "") {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Расписание | Hercules</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="schedule.css">
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
    </nav>
  </div>
</header>

<main class="page">
  <div class="container">

    <h1>Расписание (Schedule)</h1>
    <p class="muted">Поиск по StudentID / TeacherID / LessonID или LessonName.</p>

    <!-- SEARCH FORM -->
    <section class="card search-card">
      <form method="GET" action="schedule.php" class="search">
        <label>
  Сортировка
  <select name="sort">
    <option value="id"      <?php if(($sort ?? 'id')==='id') echo 'selected'; ?>>ID</option>
    <option value="student" <?php if(($sort ?? '')==='student') echo 'selected'; ?>>Студент</option>
    <option value="teacher" <?php if(($sort ?? '')==='teacher') echo 'selected'; ?>>Учитель</option>
    <option value="lesson"  <?php if(($sort ?? '')==='lesson') echo 'selected'; ?>>Урок</option>
  </select>
</label>

<label>
  Направление
  <select name="dir">
    <option value="asc"  <?php if(($dir ?? '')==='asc')  echo 'selected'; ?>>A → Z</option>
    <option value="desc" <?php if(($dir ?? 'desc')==='desc') echo 'selected'; ?>>Z → A</option>
  </select>
</label>
  <label>
    Студент
    <input type="text" name="student" placeholder="Имя студента">
  </label>

  <label>
    Учитель
    <input type="text" name="teacher" placeholder="Имя учителя">
  </label>

  <label>
    Урок
    <input type="text" name="lesson" placeholder="Название урока">
  </label>

  <button type="submit" class="btn btn--primary">Искать</button>
</form>
    </section>

    <!-- TABLE -->
    <section class="card table-card">
     <table class="table">
  <thead>
    <tr>
      <th>ID</th>
      <th>Студент</th>
      <th>Учитель</th>
      <th>Урок</th>
      <th>Дата</th>
      <th>Время</th>
      <th>Место</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($result->num_rows === 0): ?>
      <tr><td colspan="7">Ничего не найдено</td></tr>
    <?php else: ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?php echo $row["ScheduleID"]; ?></td>
          <td><?php echo htmlspecialchars($row["StudentName"]); ?></td>
          <td><?php echo htmlspecialchars($row["TeacherName"]); ?></td>
          <td><?php echo htmlspecialchars($row["LessonName"] ?? "—"); ?></td>
          <td><?php echo htmlspecialchars($row["SessionDate"] ?? "—"); ?></td>
          <td><?php echo htmlspecialchars($row["SessionTime"] ?? "—"); ?></td>
          <td><?php echo htmlspecialchars($row["Location"] ?? "—"); ?></td>
        </tr>
      <?php endwhile; ?>
    <?php endif; ?>
  </tbody>
</table>
    </section>

  </div>
</main>

<footer class="footer">
  <div class="container">Hercules © 2026</div>
</footer>

</body>
</html>
<?php
$stmt->close();
$conn->close();
?>