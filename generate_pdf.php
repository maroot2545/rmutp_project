<?php
require_once('C:\xampp\htdocs\attendance_system\vendor\tecnickcom\tcpdf\tcpdf.php');

// สร้าง PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('thsarabun', '', 16); // ใส่ฟอนต์ภาษาไทยถ้าจำเป็น

// ดึงข้อมูลจากฐานข้อมูล
$conn = new mysqli('localhost', 'root', '', 'attendance_system');
$result = $conn->query("SELECT * FROM students");

// สร้างเนื้อหา
$html = '<h1 style="text-align: center;">รายงานเช็คชื่อนักศึกษา</h1>';
$html .= '<table border="1" cellpadding="4"><thead><tr><th>ลำดับ</th><th>รหัสนักศึกษา</th><th>ชื่อ-นามสกุล</th><th>สถานะ</th></tr></thead><tbody>';

while ($row = $result->fetch_assoc()) {
    $html .= '<tr>
        <td style="text-align: center;">' . $row['id'] . '</td>
        <td style="text-align: center;">' . $row['student_id'] . '</td>
        <td>' . $row['name'] . '</td>
        <td style="text-align: center;">' . $row['status'] . '</td>
    </tr>';
}

$html .= '</tbody></table>';

// เพิ่มเนื้อหาใน PDF
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('report.pdf', 'D'); // ดาวน์โหลดไฟล์
?>
