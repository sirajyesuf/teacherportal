<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AttendanceExport implements FromView, WithStyles
{

    protected $studentName;
    protected $exportData;

    public function __construct($exportData, $studentName)
    {
        $this->exportData = $exportData;
        $this->studentName = $studentName;
    }

    public function view(): View
    {
        return view('exports.attendance-excel', [
            'exportData' => $this->exportData,
            'student' => $this->studentName
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        // Apply styles to specific elements in the Excel sheet
        for ($row = 1; $row <= $lastRow; $row++) {
            $cellValue = $sheet->getCell('A' . $row)->getValue();

            if (strpos($cellValue, 'Attendance Record') !== false) {
                // Apply styles to the "Attendance Record" line (centered and bold)
                $sheet->getStyle('A' . $row)->applyFromArray([
                    'font' => ['bold' => true, 'underline' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
            } elseif (strpos($cellValue, 'Date') !== false) {
                // Apply styles to the <th> element (Package name row)
                $sheet->getStyle('A' . $row . ':C' . $row)->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E5E5E5']],
                    'borders' => [
                        'outline' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                        'inside' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                ]);
            } elseif (strpos($cellValue, 'Hours Completed:') !== false) {
                // Apply styles to the <tr> element (Hours Completed row)
                $sheet->getStyle('A' . $row . ':C' . $row)->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
            } elseif (strpos($cellValue, 'Package:') !== false) {
                // Apply styles to the <tr> element (Hours Completed row)
                $sheet->getStyle('A' . $row . ':C' . $row)->applyFromArray([                    
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);
            } elseif (strpos($cellValue, 'Student Name:') !== false) {
                // Apply styles to the <tr> element (Hours Completed row)
                $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
                    'font' => ['bold' => true],                    
                ]);
            } elseif (!empty($cellValue)) {
                // Apply styles to other cells (with borders)
                $sheet->getStyle('A' . $row . ':C' . $row)->applyFromArray([
                    'borders' => [
                        'outline' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                        'inside' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                ]);
            }
        }

        return [];
    }
}
