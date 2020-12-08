<?php

namespace App\Imports;

use App\User;
use App\Member;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Hash;
use Throwable;
class UsersImport implements ToCollection, WithHeadingRow,SkipsOnError,WithValidation,SkipsOnFailure
{
    use Importable,SkipsErrors,SkipsFailures;
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
                'roleId'=>5
            ]);
            // $user->role()->create([
            //     ''
            // ]);
        }
    }
    // public function onError(Throwable $error){

    // }
    public function rules():array{
        return [
            '*.phonenumber'=>['unique:users,phoneNumber']
        ];
    }

}
