<?php
// เชื่อมต่อกับฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "attendance_system");

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่าเป็นการส่งฟอร์มหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $student_id = $_POST['student_id'];
    $student_name = $_POST['name'];
    $status = $_POST['status'];

    // เตรียมคำสั่ง SQL
    $stmt = $conn->prepare("INSERT INTO attendance (student_id, student_name, status) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $student_id, $student_name, $status);

    // รันคำสั่ง
    if ($stmt->execute()) {
        // ถ้าบันทึกสำเร็จ, เปลี่ยนเส้นทางไปยัง dashboard.php
        header("Location: dashboard.php");
        exit();  // จำเป็นต้องใช้ exit() เพื่อหยุดการทำงานของโค้ดหลังจาก header()
    } else {
        echo "Error: " . $stmt->error;
    }

    // ปิดการเชื่อมต่อ
    $stmt->close();
}

// ปิดการเชื่อมต่อ
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มข้อมูลนักศึกษา</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .form-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: blue;
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: darkblue;
        }

        .back-link {
            display: block;
            margin-bottom: 15px;
            text-align: center;
        }

        .back-link a {
            text-decoration: none;
            color: green;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>เพิ่มข้อมูลนักศึกษา</h2>
        <form method="POST">
            <label for="student_id">รหัสนักศึกษา:</label>
            <input type="text" name="student_id" required>

            <label for="name">ชื่อ-นามสกุล:</label>
            <input type="text" name="name" required>

            <label for="status">สถานะ:</label>
            <select name="status">
                <option value="มาเรียน">มาเรียน</option>
                <option value="ลา">ลา</option>
                <option value="ขาด">ขาด</option>
                <option value="สาย">สาย</option>
            </select>
            <button type="submit">บันทึกข้อมูล</button>
            <button type="button" onclick="window.location.href='dashboard.php'">กลับไปที่หน้าหลัก</button>
        </form>
    </div>
</body>
</html>
