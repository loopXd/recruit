<?php

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


if (!function_exists('import_failed')) {
    function import_failed($file, $failures)
    {
        $rowsData = [];
        $rowIndexes = [];
        $errors = [];
        $colCount = count($failures[0]->values());
        foreach ($failures as $failure) {
            if (!in_array($failure->row(), $rowIndexes)) {
                array_push($rowIndexes, $failure->row());
                array_push($rowsData, collect($failure->values())->values()->toArray());
            }

            $rowIndex = $failure->row();
            $failure->attribute();
            $comment = $failure->errors();
            $columnIndex = array_search($failure->attribute(), collect($failure->values())->keys()->all());
            array_push($errors, [$columnIndex, $rowIndex, $comment]);
        }

        $cellStr = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T'];

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();

        $spreadsheet = $reader->load($file->getRealPath());
        // Retrieve the current active worksheet
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($errors as $error) {
            $cell = $cellStr[$error[0]] . $error[1];
            $sheet
                ->getComment($cell)
                ->getText()
                ->createTextRun($error[2]);
            $styleArray = [
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FFFF0000'],
                    ],
                ],
            ];
            $sheet->getStyle($cell)->applyFromArray($styleArray);
        }
        $successfulRows = 0;
        // remove successful data row
        for ($i = $sheet->getHighestDataRow(); $i > 1; --$i) {
            if (!in_array($i, $rowIndexes)) {
                $sheet->removeRow($i, 1);
                $successfulRows++;
            }
        }

        // Write a new .xlsx file
        $writer = new Xlsx($spreadsheet);

        // Save the new .xlsx file
        $pathToFile = storage_path('/app/public/importReport.xlsx');
        $writer->save($pathToFile);

        return [
            'successfull' => $successfulRows,
            'successRate' => round(($successfulRows / ($successfulRows + count($rowIndexes))) * 100),
            'unsuccessfull' => count($rowIndexes),
            'unsuccessRate' => round((count($rowIndexes) / ($successfulRows + count($rowIndexes))) * 100),
            'errors' => $failures->count(),
            'errorRate' => round(($failures->count() / ($colCount * count($rowIndexes))) * 100)
        ];
    }
}
