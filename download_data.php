<?php
require_once('load.php');
require 'vendor/autoload.php';
page_require_level(1);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$SpreadSheet = new Spreadsheet();
$sheet = $SpreadSheet->getActiveSheet();

$data = [];

$headers = ['Nome', 'Email','Password', 'Email de Recuperação', 'Nível de usuário']; 
$data[] = $headers;

$search = $_GET['search'];
$result = search_init($search);
$rows = $db->while_loop($result);

foreach($rows as $row) {
    $data[] = [
        $row['name'],
        $row['email'],
        $row['password'],
        $row['recuperation_email'],
        $row['level_name'],
    ];
}

$sheet->fromArray($data, null, 'A1');

$filename = 'users.xlsx';

$writer = new Xlsx($SpreadSheet);
$writer->save($filename);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');

exit();
?>
