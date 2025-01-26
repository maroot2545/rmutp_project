<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'attendance_system');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $sql = "UPDATE attendance SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $status, $id);
    $stmt->execute();
}
$result = $conn->query("SELECT * FROM attendance");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบเช็คชื่อ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center">ระบบเช็คชื่อนักศึกษา</h3>
        <a href="logout.php" class="btn btn-danger mb-3">ออกจากระบบ</a>
        <a href="add.php" class="btn btn-success mb-3">เพิ่มข้อมูลนัก</a>
        <a href="download.php?type=xlsx" class="btn btn-outline-dark mb-3">ดาวน์โหลดเป็น Excel</a>
        <a href="download.php?type=pdf" class="btn btn-outline-dark mb-3">ดาวน์โหลดเป็น PDF</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>รหัสนักศึกษา</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>สถานะ</th>
                    <th>อัปเดตสถานะ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['student_id'] ?></td>
                        <td><?= $row['student_name'] ?></td>
                        <td><?= $row['status'] ?></td>
                        <td>
                            <form method="POST" class="d-flex">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <select name="status" class="form-select me-2">
                                    <option <?= $row['status'] === 'มาเรียน' ? 'selected' : '' ?>>มาเรียน</option>
                                    <option <?= $row['status'] === 'ลา' ? 'selected' : '' ?>>ลา</option>
                                    <option <?= $row['status'] === 'ขาด' ? 'selected' : '' ?>>ขาด</option>
                                    <option <?= $row['status'] === 'สาย' ? 'selected' : '' ?>>สาย</option>
                                </select>
                                <button type="submit" class="btn btn-primary">อัปเดต</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
