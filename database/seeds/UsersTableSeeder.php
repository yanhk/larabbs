<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // 生成数据集合
        $users = factory(User::class)->times(10)->create();

        // 单独处理第一个用户的数据
//        $user = User::find(1);
//        $user->name = 'Summer';
//        $user->email = '123@163.com';
//        $user->avatar = 'https://cdn.learnku.com/uploads/images/201710/14/1/ZqM7iaP4CR.png';
//        $user->save();

        // 初始化用户角色，将 1 号用户指派为『站长』
//        $user->assignRole('Founder');

        // 将 2 号用户指派为『管理员』
//        $user = User::find(2);
//        $user->assignRole('Maintainer');
    }
}
