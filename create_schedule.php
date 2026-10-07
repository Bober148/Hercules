<?php
require_once __DIR__ . '/db.php';
session_start();

if (($_SESSION['role'] ?? '') !== 'teacher') { header('Location: teacherlogin.html'); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST')   { header('Location: teacher.php');       exit; }

$teacher_id = (int)($_SESSION['user_id'] ?? 0);
$student_id = (int)($_POST['student_id'] ?? 0);
$lesson_id  = (int)($_POST['lesson_id']  ?? 0);
$date       = trim($_POST['session_date'] ?? '');
$time       = trim($_POST['session_time'] ?? '');
$location   = trim($_POST['location']     ?? '');

if ($student_id <= 0 || $lesson_id <= 0) {
    header('Location: teacher.php?error=' . urlencode('Выбери ученика и урок.'));
    exit;
}

// необязательные поля: пустое -> NULL
$date     = $date     !== '' ? $date     : null;
$time     = $time     !== '' ? $time     : null;
$location = $location !== '' ? $location : null;

try {
    $conn = db();

    // в БД нет внешних ключей — проверяем существование вручную
    $chk = $conn->prepare(
        "SELECT
           (SELECT COUNT(*) FROM students WHERE StudentID = ?) AS s,
           (SELECT COUNT(*) FROM lessons  WHERE LessonID  = ?) AS l"
    );
    $chk->bind_param("ii", $student_id, $lesson_id);
    $chk->execute();
    $row = $chk->get_result()->fetch_assoc();
    if ((int)$row['s'] === 0 || (int)$row['l'] === 0) {
        header('Location: teacher.php?error=' . urlencode('Такого ученика или урока нет.'));
        exit;
    }

    $stmt = $conn->prepare(
        "INSERT INTO schedule (StudentID, TeacherID, LessonID, SessionDate, SessionTime, Location)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("iiisss", $student_id, $teacher_id, $lesson_id, $date, $time, $location);
    $stmt->execute();

    header('Location: teacher.php?created=1');
    exit;
} catch (mysqli_sql_exception $e) {
    error_log('create_schedule error: ' . $e->getMessage());
    header('Location: teacher.php?error=' . urlencode('Не удалось добавить запись.'));
    exit;
}
