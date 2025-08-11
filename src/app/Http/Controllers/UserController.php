<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\User;
use App\Models\Address;
use App\Models\Item;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Str;


class UserController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $address = Address::where('user_id', $user->id)->first();
        $profileImagePath = $user->profile_image;

        // 画像が存在するか確認
        $profileImageUrl = null;
        if ($profileImagePath && Storage::disk('public')->exists($profileImagePath)) {
            // 画像のURLを生成
            $profileImageUrl = Storage::disk('public')->url($profileImagePath);
        }

        return view('mypage.profile', compact('user','address','profileImageUrl'));
    }

    public function store(AddressRequest $addressrequest , ProfileRequest $profilerequest)
    {
        DB::beginTransaction();
        try{
            $user = Auth::user();
            Log::info("User ID: " . $user->id);
            $user->name = $profilerequest->input('name');
            if ($profilerequest->hasFile('profile_image')) {
                $file = $profilerequest->file('profile_image');
                $file_name = Str::random(10) . '.' . $file->getClientOriginalExtension(); // ユニークなファイル名を生成
                $path = $file->storeAs('profiles', $file_name, 'public'); // storage/app/public/profile に保存
                $user->profile_image = 'profiles/' . $file_name; // データベースに保存するパス
            }

            $user->save();

            $address = Address::where('user_id', $user->id)->first();
            $postcode = $addressrequest->input("post_code");
            if(preg_match('/^[0-9]{7}$/', $postcode))
            {
                $postcode = substr($postcode ,0,3) . "-" . substr($postcode ,3);
            }
            $addressData = $addressrequest->only(["address","building"]);
            $addressData["post_code"] = $postcode;
            $addressData["user_id"] = $user->id;

            if($address) {
                $address->update($addressData);
            }else{
                Address::create($addressData);
            }

            DB::commit();

            return redirect("/mypage/profile")->with("success", "プロフィールを更新しました");
        }catch (\Exception $e) {
            DB::rollback();
            Log::error("Error: " . $e->getMessage());
            return back()->withErrors(["error", "プロフィールの更新に失敗しました"]);
        }

    }

    public function updateAddress(Item $item)
    {
        $user = Auth::user();
        $address = Address::where('user_id', $user->id)->first();
        $data = [
            "item"=>$item,
        ];
        return view("purchase.address",compact("item","address"));
    }

    public function update(AddressRequest $request, Item $item)
    {
        $user = Auth::user();
        $item = $request->input("item_id");
        $address = Address::where('user_id', $user->id)->first();
        $postcode = $request->input("post_code");
        if(preg_match('/^[0-9]{7}$/', $postcode))
        {
            $postcode = substr($postcode ,0,3) . "-" . substr($postcode ,3);
        }
        $addressData = $request->only(["address","building"]);
        $addressData["post_code"] = $postcode;
        $addressData["user_id"] = $user->id;

        $address->update($addressData);

        return redirect('/purchase/' . $item);
    }

}
