<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            "name" => "出品ユーザー1",
            "email" => "test1@example.com",
            "email_verified_at" => now(),
            "password" => Hash::make("12345678"),
            "profile_image" => "default.png",
        ];
        DB::table("users")->insert($param);

        $param = [
            "name" => "出品ユーザー2",
            "email" => "test2@example.com",
            "email_verified_at" => now(),
            "password" => Hash::make("12345678"),
            "profile_image" => "default.png",
        ];
        DB::table("users")->insert($param);

        $param = [
            "name" => "ダミーユーザー",
            "email" => "test3@example.com",
            "email_verified_at" => now(),
            "password" => Hash::make("12345678"),
            "profile_image" => "default.png",
        ];
        DB::table("users")->insert($param);

        $param = [
            "user_id" => 1,
            "post_code" => "123-4567",
            "address" => "東京都大田区",
            "building" => "タイガースマンション",
        ];
        DB::table("addresses")->insert($param);

        $param = [
            "user_id" => 2,
            "post_code" => "987-6543",
            "address" => "神奈川県横浜市",
            "building" => "ドラゴンズヒルズ",
        ];
        DB::table("addresses")->insert($param);

        $param = [
            "user_id" => 3,
            "post_code" => "456-7654",
            "address" => "福岡県博多区",
            "building" => "プライムホークス",
        ];
        DB::table("addresses")->insert($param);
    }
}
