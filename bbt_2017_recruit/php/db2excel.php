<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
include("conn.php");
include_once('./PHPExcel-1.8/Classes/PHPExcel.php');

$data = array();
$query = mysqli_query($link,"SELECT * FROM messages");
while($row = mysqli_fetch_array($query)){
	$data[] = array($row['name'],$row['sex'],$row['grade'],$row['college'],$row['dorm'],$row['phone_number'],$row['branch'],$row['second_branch'],$row['adjust'],$row['introduction']);
}

$letter = array('A','B','C','D','E','F','G','H','I','J');

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '姓名')
            ->setCellValue('B1', '性别')
            ->setCellValue('C1', '年级')
            ->setCellValue('D1', '学院')
            ->setCellValue('E1', '宿舍')
            ->setCellValue('F1', '电话')
            ->setCellValue('G1', '第一志愿')
            ->setCellValue('H1', '第二志愿')
            ->setCellValue('I1', '是否调剂')
            ->setCellValue('J1', '介绍');

for($i=2;$i<=count($data)+1;$i++){
	$j = 0;
	foreach($data[$i-2] as $val) {
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter[$j].$i, $val);
        $j++;
	}

}
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

ob_end_clean();
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="bbt.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;