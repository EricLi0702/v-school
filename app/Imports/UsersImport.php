<?php

namespace App\Imports;

use App\User;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // $rowIndex = $row->getIndex();
        // $row      = $row->toArray();
        return new User([
            //
            'name' =>$row[1],
            'phoneNumber'=>$row[2],
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'roleId'=>5
        ]);
    }
    public function headingRow(): int{
        return 1;
    }
}
