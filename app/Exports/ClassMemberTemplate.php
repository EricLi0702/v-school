<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
// use Maatwebsite\Excel\Concerns\WithColumnWidths;
class ClassMemberTemplate implements FromArray,ShouldAutoSize,WithHeadings
{
    protected $answerData;

    public function __construct(array $answerData)
    {
        $this->answerData = $answerData;
    }
    
    public function headings():array
    {
        return [
            'name',
            'phoneNumber',
            'role',
            'school',
            'grade',
            'lesson'
        ];
    }

    public function array(): array
    {
        return $this->answerData;
    }

    // public function columnWidths(): array
    // {
    //     return [
    //         'A' => 15,
    //         'B' => 15,
    //         'C'=>5,
    //         'D'=>5,
    //         'E'=>5,
    //         'F'=>5,
    //         'G'=>5
    //     ];
    // }
}
