<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromQuery,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings():array
    {
        return [
            'Id',
            'Name',
            'PhoneNumber',
            'created_at',
            'updated_at',
            'roleId'
        ];
    }

    public function query(){
        return User::query()->select('id','name','phoneNumber','created_at','updated_at','roleId');
    }

    // public function collection()
    // {
    //     return User::all();
    // }
}
