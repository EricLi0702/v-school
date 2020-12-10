<?php

namespace App\Imports;

use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Throwable;
class UsersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $rows)
    {
        return new User([
            'name'     => $row['name'],
            'email'    => $row['phonenumber'],
            'password' => Hash::make('password'),
            // 'roleId'=>1
        ]);
    }
}
