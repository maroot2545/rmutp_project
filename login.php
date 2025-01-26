<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'attendance_system');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (hash('sha256', $password) === $user['password']) {
            $_SESSION['user'] = $user['username'];
            header('Location: dashboard.php');
            exit();
        } else {
            $message = "รหัสผ่านไม่ถูกต้อง";
        }
    } else {
        $message = "ไม่พบผู้ใช้นี้";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .time-display {
            font-size: 2.5rem;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
            <div class="time-display" id="current-time"></div>
                <h3 class="text-center">เข้าสู่ระบบ</h3>
                <?php if ($message): ?>
                    <div class="alert alert-danger"><?= $message ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">ชื่อผู้ใช้</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">รหัสผ่าน</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">เข้าสู่ระบบ</button>

                    <a href="register.php" class="btn btn-secondary w-100 mt-2">สมัครสมาชิก</a>
                </form>
            </div>
        </div>
    </div>
    <!-- JavaScript สำหรับแสดงวันที่และเวลา -->
    <script>
        function updateTime() {
            const now = new Date();

            // ชื่อวันเป็นภาษาไทย
            const days = ["อาทิตย์", "จันทร์", "อังคาร", "พุธ", "พฤหัสบดี", "ศุกร์", "เสาร์"];

            // ชื่อเดือนเป็นภาษาไทย
            const months = [
                "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
                "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
            ];

            const dayName = days[now.getDay()];
            const date = now.getDate();
            const month = months[now.getMonth()];
            const year = now.getFullYear() + 543; // แปลงปีคริสต์ศักราชเป็นพุทธศักราช
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            const timeString = `วัน${dayName}ที่ ${date} ${month} ${year} เวลา ${hours}:${minutes}:${seconds}`;

            document.getElementById("current-time").innerText = timeString;
        }

        // อัปเดตเวลาในทุก ๆ วินาที
        setInterval(updateTime, 1000);

        // เรียกฟังก์ชันครั้งแรกเพื่อแสดงทันที
        updateTime();
    </script>
</body>
</html>
