<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class CurriCulumExport implements FromArray,ShouldAutoSize,WithHeadings,WithColumnWidths
{
    protected $classData;

    public function __construct(array $classData)
    {
        $this->classData = $classData;
    }
    public function headings():array
    {
        return [
            '班级',
            '场所',
            '周一第1节',
            '周一第2节',
            '周一第3节',
            '周一第4节',
            '周一第5节',
            '周一第6节',
            '周一第7节'
        ];
    }

    public function array(): array
    {
        return $this->classData;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C'=>20,
            'D'=>20,
            'E'=>20,
            'F'=>20,
            'G'=>20,
            'H'=>20,
            'I'=>20
        ];
    }
}
