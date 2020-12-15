<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
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
            'Name',
            'PhoneNumber',
            'role',
            'schoolId',
            'gradeId',
            'lessonId'
        ];
    }

    public function array(): array
    {
        return $this->answerData;
    }
    
}
