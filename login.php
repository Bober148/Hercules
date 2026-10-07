<?php
require_once __DIR__ . '/db.php';

session_start();

try {
    $conn = db();
    $conn->set_charset("utf8mb4");

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        die("Invalid request.");
    }

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        die("All fields are required.");
    }

    $stmt = $conn->prepare(
        "SELECT StudentID, FirstName, PasswordHash 
         FROM students 
         WHERE Email = ?"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user["PasswordHash"])) {

         session_regenerate_id(true);

        $_SESSION["logged_in"] = true;
        $_SESSION["role"] = "student";            
        $_SESSION["user_id"] = (int)$user["StudentID"];
        $_SESSION["user_name"] = $user["FirstName"];

        header("Location: profile.php");
        exit;

        } else {
                echo "Wrong password.";
            }

    } else {
        echo "User not found.";
    }

    $stmt->close();
    $conn->close();

} catch (mysqli_sql_exception $e) {
    error_log("DB error: " . $e->getMessage());
    echo "Произошла ошибка. Попробуйте позже.";
}
?>