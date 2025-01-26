<?php
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'attendance_system');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // ตรวจสอบว่ามีผู้ใช้ชื่อนี้อยู่แล้วหรือไม่
    $check_sql = "SELECT * FROM users WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param('s', $username);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $message = "ชื่อผู้ใช้นี้มีอยู่แล้ว";
    } elseif ($password !== $confirm_password) {
        $message = "รหัสผ่านไม่ตรงกัน";
    } elseif (strlen($password) < 8) {
        $message = "รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร";
    } else { // ภายในส่วนของการตรวจสอบรหัสผ่านสำเร็จ
        $hashed_password = hash('sha256', $password);
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)"; // ถูกต้อง: INSERT ลงในตารางที่มีอยู่
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $username, $hashed_password);

        if ($stmt->execute()) {
            $message = "สมัครสมาชิกสำเร็จ";
            header('Location: login.php'); // Redirect to login page
            exit();
        } else {
            $message = "เกิดข้อผิดพลาดในการสมัครสมาชิก: " . $stmt->error;
        }
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <h3 class="text-center">สมัครสมาชิก</h3>
                <?php if ($message): ?>
                    <div class="alert alert-danger"><?= $message ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">ชื่อผู้ใช้</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">รหัสผ่าน</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">ยืนยันรหัสผ่าน</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">สมัครสมาชิก</button>
                    <a href="login.php" class="btn btn-secondary w-100 mt-2">กลับไปหน้าล็อกอิน</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>