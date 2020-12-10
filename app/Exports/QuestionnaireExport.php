<?php

namespace App\Exports;

use App\Questionnaire;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
class QuestionnaireExport implements FromArray,ShouldAutoSize
{
    protected $answerData;

    public function __construct(array $answerData)
    {
        $this->answerData = $answerData;
    }

    public function array(): array
    {
        return $this->answerData;
    }
}
