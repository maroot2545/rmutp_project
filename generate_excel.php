<?php
require 'vendor/autoload.php'; // โหลดไลบรารี PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// สร้าง Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// ตั้งค่าหัวตาราง
$sheet->setCellValue('A1', 'ลำดับ');
$sheet->setCellValue('B1', 'รหัสนักศึกษา');
$sheet->setCellValue('C1', 'ชื่อ-นามสกุล');
$sheet->setCellValue('D1', 'สถานะ');

// ดึงข้อมูลจากฐานข้อมูล
$conn = new mysqli('localhost', 'root', '', 'attendance_system');
$result = $conn->query("SELECT * FROM students");
$rowNum = 2;

while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNum, $row['id']);
    $sheet->setCellValue('B' . $rowNum, $row['student_id']);
    $sheet->setCellValue('C' . $rowNum, $row['name']);
    $sheet->setCellValue('D' . $rowNum, $row['status']);
    $rowNum++;
}

// ดาวน์โหลดไฟล์
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="report.xlsx"');
$writer->save('php://output');
?>
