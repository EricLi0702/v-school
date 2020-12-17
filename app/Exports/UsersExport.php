<?php

namespace App\Exports;

use App\User;
use App\UserRole;
use Carbon\Carbon;
// use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\WithColumnWidths;
class UsersExport implements FromCollection,WithMapping,WithHeadings,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection(){
        return User::with('role')->get();
    }

    public function headings():array
    {
        return [
            'name',
            'phoneNumber',
            'role'
        ];
    }

    public function map($user):array{
        return [
            // $user->id,
            $user->name,
            $user->phoneNumber,
            $user->roleId
        ];
    }

    // public function columnWidths(): array
    // {
    //     return [
    //         'A' => 15,
    //         'B' => 15,
    //         'c'=>5            
    //     ];
    // }
}
