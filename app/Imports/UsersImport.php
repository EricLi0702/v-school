<?php

namespace App\Imports;

use App\User;
use App\Member;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class UsersImport implements ToCollection, WithHeadingRow
{
    
    public function collection(Collection $rows)
    {
        // return new User([
        //     'name' =>$row['name'],
        //     'phoneNumber'=>$row['phonenumber'],
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'roleId'=>5
        // ]);
        foreach($rows as $row){
            $user = User::create([
                'name'=>$row['name'],
                'phoneNumber'=>$row['phonenumber'],
                'password'=>Hash::make('password'),
                
            ]);
            // $user->role()->create([
            //     ''
            // ]);
        }
    }

}
