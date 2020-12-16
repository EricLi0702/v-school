<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
// use Maatwebsite\Excel\Concerns\WithColumnWidths;

class CurriCulumExport implements FromArray,ShouldAutoSize,WithHeadings
{
    protected $classData;

    public function __construct(array $classData)
    {
        $this->classData = $classData;
    }
    public function headings():array
    {
        return [
            'lessonName',
            'location',
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7'
        ];
    }

    public function array(): array
    {
        return $this->classData;
        // return true;
    }

    // public function columnWidths(): array
    // {
    //     return [
    //         'A' => 20,
    //         'B' => 20,
    //         'C'=>20,
    //         'D'=>20,
    //         'E'=>20,
    //         'F'=>20,
    //         'G'=>20,
    //         'H'=>20,
    //         'I'=>20
    //     ];
    // }
}
