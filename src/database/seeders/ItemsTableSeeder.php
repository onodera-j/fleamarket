<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $url = "https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg";
        $filename = "Armani_Mens_Clock.jpg";
        $client = new Client();
        $response= $client->get($url);
        $image = $response->getBody()->getContents();
        $path = Storage::put('public/seeditems/' . $filename, $image);
        $param = [
            "user_id" => 1,
            "condition" => 1,
            "item_name" => "腕時計",
            "brand_name" => "",
            "item_detail" => "スタイリッシュなデザインのメンズ腕時計",
            "price" => 15000,
            "soldout" => 0,
            "item_image" =>"seeditems/" . $filename,
        ];
        DB::table("items")->insert($param);

        $url = "https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg";
        $filename = "HDD_Hard_Disk.jpg";
        $client = new Client();
        $response= $client->get($url);
        $image = $response->getBody()->getContents();
        $path = Storage::put('public/seeditems/' . $filename, $image);
        $param = [
            "user_id" => 1,
            "condition" => 2,
            "item_name" => "HDD",
            "brand_name" => "",
            "item_detail" => "高速で信頼性の高いハードディスク",
            "price" => 5000,
            "soldout" => 0,
            "item_image" =>"seeditems/" . $filename,
        ];
        DB::table("items")->insert($param);

        $url = "https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg";
        $filename = "onion.jpg";
        $client = new Client();
        $response= $client->get($url);
        $image = $response->getBody()->getContents();
        $path = Storage::put('public/seeditems/' . $filename, $image);
        $param = [
            "user_id" => 1,
            "condition" => 3,
            "item_name" => "玉ねぎ3束",
            "brand_name" => "",
            "item_detail" => "新鮮な玉ねぎ3束のセット",
            "price" => 300,
            "soldout" => 0,
            "item_image" =>"seeditems/" . $filename,
        ];
        DB::table("items")->insert($param);

        $url = "https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg";
        $filename = "Shoes_Product.jpg";
        $client = new Client();
        $response= $client->get($url);
        $image = $response->getBody()->getContents();
        $path = Storage::put('public/seeditems/' . $filename, $image);
        $param = [
            "user_id" => 1,
            "condition" => 4,
            "item_name" => "革靴",
            "brand_name" => "",
            "item_detail" => "クラシックなデザインの革靴",
            "price" => 4000,
            "soldout" => 0,
            "item_image" =>"seeditems/" . $filename,
        ];
        DB::table("items")->insert($param);

        $url = "https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg";
        $filename = "Living_room_laptop.jpg";
        $client = new Client();
        $response= $client->get($url);
        $image = $response->getBody()->getContents();
        $path = Storage::put('public/seeditems/' . $filename, $image);
        $param = [
            "user_id" => 1,
            "condition" => 1,
            "item_name" => "ノートPC",
            "brand_name" => "",
            "item_detail" => "高性能なノートパソコン",
            "price" => 45000,
            "soldout" => 0,
            "item_image" =>"seeditems/" . $filename,
        ];
        DB::table("items")->insert($param);

        $url = "https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg";
        $filename = "Music_Mic.jpg";
        $client = new Client();
        $response= $client->get($url);
        $image = $response->getBody()->getContents();
        $path = Storage::put('public/seeditems/' . $filename, $image);
        $param = [
            "user_id" => 2,
            "condition" => 2,
            "item_name" => "マイク",
            "brand_name" => "",
            "item_detail" => "高音質のレコーディング用マイク",
            "price" => 8000,
            "soldout" => 0,
            "item_image" =>"seeditems/" . $filename,
        ];
        DB::table("items")->insert($param);

        $url = "https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg";
        $filename = "Purse_fashion_poket.jpg";
        $client = new Client();
        $response= $client->get($url);
        $image = $response->getBody()->getContents();
        $path = Storage::put('public/seeditems/' . $filename, $image);
        $param = [
            "user_id" => 2,
            "condition" => 3,
            "item_name" => "ショルダーバッグ",
            "brand_name" => "",
            "item_detail" => "おしゃれなショルダーバッグ",
            "price" => 3500,
            "soldout" => 0,
            "item_image" =>"seeditems/" . $filename,
        ];
        DB::table("items")->insert($param);

        $url = "https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg";
        $filename = "Tumblre_souvenir.jpg";
        $client = new Client();
        $response= $client->get($url);
        $image = $response->getBody()->getContents();
        $path = Storage::put('public/seeditems/' . $filename, $image);
        $param = [
            "user_id" => 2,
            "condition" => 4,
            "item_name" => "タンブラー",
            "brand_name" => "",
            "item_detail" => "使いやすいタンブラー",
            "price" => 500,
            "soldout" => 0,
            "item_image" =>"seeditems/" . $filename,
        ];
        DB::table("items")->insert($param);

        $url = "https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg";
        $filename = "Coffee_Grinder.jpg";
        $client = new Client();
        $response= $client->get($url);
        $image = $response->getBody()->getContents();
        $path = Storage::put('public/seeditems/' . $filename, $image);
        $param = [
            "user_id" => 2,
            "condition" => 1,
            "item_name" => "コーヒーミル",
            "brand_name" => "",
            "item_detail" => "手動のコーヒーミル",
            "price" => 4000,
            "soldout" => 0,
            "item_image" =>"seeditems/" . $filename,
        ];
        DB::table("items")->insert($param);

        $url = "https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg";
        $filename = "making_tool.jpg";
        $client = new Client();
        $response= $client->get($url);
        $image = $response->getBody()->getContents();
        $path = Storage::put('public/seeditems/' . $filename, $image);
        $param = [
            "user_id" => 2,
            "condition" => 2,
            "item_name" => "メイクセット",
            "brand_name" => "",
            "item_detail" => "便利なメイクアップセット",
            "price" => 2500,
            "soldout" => 0,
            "item_image" =>"seeditems/" . $filename,
        ];
        DB::table("items")->insert($param);

    }
}
