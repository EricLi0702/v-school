<?php

namespace App\Imports;

use App\User;
use App\Member;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            //
            'name' =>$row['name'],
            'phoneNumber'=>$row['phonenumber'],
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'roleId'=>5
        ]);
        // return new Member([
        //     'schoolId'=>1,
        //     'gradeId'=>1,
        //     'lessonId'=>1,
        //     'userId'=>1,
        //     'userRoleId'=>5
        // ]);
    }

}
