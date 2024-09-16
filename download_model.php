<?php
    require_once('load.php');
    require('vendor/autoload.php');
    page_require_level(1);

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $headers = ['Identificação', 'Nome', 'Email','Password', 'Email de Recuperação', 'Nível de usuário']; 

    $sheet->fromArray($headers, null,'A1');

    
    $filename = 'data_model.xlsx';

    $writer = new Xlsx($spreadsheet);
    $writer->save($filename);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');

    exit();
?>