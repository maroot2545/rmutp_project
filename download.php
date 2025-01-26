<?php
// เชื่อมต่อ Composer autoload (ถ้าคุณใช้ PhpSpreadsheet หรืออื่นๆ)
// require 'vendor/autoload.php';  // ถ้าใช้ Composer

// โหลดไฟล์ TCPDF
require_once('C:\xampp\htdocs\attendance_system\vendor\tecnickcom\tcpdf\tcpdf.php'); // ใช้เส้นทางที่ถูกต้องตามที่คุณติดตั้ง TCPDF

// ตรวจสอบประเภทของไฟล์
$type = $_GET['type'];

if ($type == 'xlsx') {
    // โค้ดสร้างไฟล์ Excel (เช่น PhpSpreadsheet)
    // ... (ตามที่เคยอธิบายไปแล้ว)
} elseif ($type == 'pdf') {
    // สร้างไฟล์ PDF ด้วย TCPDF
    $pdf = new TCPDF();
    $pdf->AddPage();

    // ตั้งค่าเนื้อหา
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(40, 10, 'รหัสนักศึกษา', 1);
    $pdf->Cell(80, 10, 'ชื่อ-นามสกุล', 1);
    $pdf->Cell(40, 10, 'สถานะ', 1);
    $pdf->Ln();

    // ดึงข้อมูลจากฐานข้อมูล
    $conn = new mysqli("localhost", "root", "", "attendance_system");
    $result = $conn->query("SELECT student_id, student_name, status FROM attendance");

    while ($data = $result->fetch_assoc()) {
        $pdf->Cell(40, 10, $data['student_id'], 1);
        $pdf->Cell(80, 10, $data['student_name'], 1);
        $pdf->Cell(40, 10, $data['status'], 1);
        $pdf->Ln();
    }

    // ตั้งค่าให้ดาวน์โหลด
    $pdf->Output('attendance_data.pdf', 'D');
    exit();
} else {
    // ถ้าไม่พบประเภทที่ต้องการ
    echo "ไม่พบประเภทไฟล์ที่ต้องการ";
    exit();
}
?>
