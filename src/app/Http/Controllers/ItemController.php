<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\CategoryItem;
use App\Models\Comment;
use App\Models\Address;
use App\Models\Transaction;
use App\Models\Favorite;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    public function index(Request $request)
    {

        $tab = $request->query("tab", "index");
        $keyword = $request->input("keyword");


        $Items = [];
        $mylistItems = [];
        $user = [];

        if($tab === "mylist"){
            if(Auth::check()){
                if(empty($keyword)){
                    $user = Auth::user();
                    $mylistItems = Favorite::where("user_id",$user->id)
                                            ->with("item")->get();
                }else{
                    $user = Auth::user();
                    $mylistItems = Favorite::where("user_id",$user->id)
                                            ->whereHas("item", function ($query) use ($keyword){$query->where('item_name', 'LIKE', '%' . $keyword . '%');})
                                            ->with("item")->get();
                }
            }else{
                $mylistItems = [];
            }
        }elseif(Auth::check()){
            $user = Auth::user();
            $Items = Item::where("user_id","!=", $user->id)->get();
        }else{
            $Items = Item::get();
        }

        return view("index",compact("user","mylistItems", "Items", "tab"));

    }

    public function search(Request $request)
    {
        $tab = $request->query("tab", "index");
        $keyword = $request->input("keyword");

        $Items = [];
        $mylistItems = [];
        $user = [];

        if($tab === "mylist"){
            if(Auth::check()){
                $user = Auth::user();
                $mylistItems = Favorite::where("user_id",$user->id)
                                ->whereHas("item", function ($query) use ($request){$query->keywordSearch($request->keyword);})
                                ->with("item")->get();
            }else{
                $mylistItems = [];
            }

        }elseif(Auth::check()){
            $user = Auth::user();
            $Items = Item::where("user_id","!=", $user->id)
                            ->keywordSearch($request->keyword)
                            ->get();
        }else{
            $Items = Item::keywordSearch($request->keyword)
                            ->get();
        }

        $request->flash();
        return view("index", compact("user", "Items", "mylistItems", "tab", "keyword"));
    }

    public function sell()
    {
        $user = Auth::user(); // ログインユーザーを取得
        $categories = Category::all();
        return view("sell",compact("user","categories"));
    }

    public function store(ExhibitionRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'condition' => ["required"],
            'category_id' => ["required","array","min:1"],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        DB::beginTransaction();
        try{
            $user = Auth::user(); // ログインユーザーを取得
            Log::info("User ID: " . $user->id);

            $itemData = $request->only(["condition","item_name","brand_name","item_detail",]);
            $itemData["price"] = $request->input("price");
            $itemData["soldout"] = 0;
            $itemData["user_id"] = $user->id;

            if ($request->hasFile('item_image')) {
                $file = $request->file('item_image');
                $file_name = Str::random(10) . '.' . $file->getClientOriginalExtension(); // ユニークなファイル名を生成
                $path = $file->storeAs('items', $file_name, 'public'); // storage/app/public/itemsに保存
                $itemData["item_image"] = 'items/' . $file_name; // データベースに保存するパス
                }

                $item = Item::create($itemData);
                $categoryIds = $request->input("category_id",[]);

                foreach($categoryIds as $categoryId){
                    CategoryItem::create([
                        "item_id"=> $item->id,
                        "category_id"=>$categoryId,
                    ]);
                }

                DB::commit();

            return redirect("/mypage/mypage")->with("success", "商品を出品しました");
        }catch (\Exception $e) {
            DB::rollback();
            Log::error("Error: " . $e->getMessage());
            return back()->withErrors(["error", "商品の出品に失敗しました"]);
        }

    }

    public function mypage(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query("tab", "sell");

        $buyItems = [];
        $sellItems = [];

        if($tab === "buy"){
            $buyItems = Transaction::where("purchaser_id",$user->id)
            ->with("item")->get();
        }else{
            $sellItems = Item::where("user_id", $user->id)->get();
        }


        return view("mypage.mypage",compact("user","buyItems", "sellItems", "tab"));
    }

    public function item(Item $item)
    {
        if(Auth::check()){
            $user = Auth::user();
            $favorite = Favorite::where('item_id', $item->id)
                            ->where('user_id', $user->id)
                            ->first();
        }else{
            $favorite = [];
        }

        $data = [
            "item"=>$item,
        ];
        $categories = CategoryItem::where("item_id", $item->id)->get();
        $comments = Comment::where("item_id", $item->id)->get();
        $counts = Favorite::where('item_id', $item->id)->get();

        return view("item", compact("categories", "item", "comments", "favorite", "counts"));
    }

    public function comment(CommentRequest $request)
    {
        $user = Auth::user();

        $commentData["item_id"] = $request->input("item_id");
        $commentData["user_id"] = $user->id;
        $commentData["comment"] = $request->input("comment");

        Comment::create($commentData);
        return redirect("/item/" . $request->input("item_id"));
    }

    public function purchase(Item $item)
    {
        $user = Auth::user();
        $data = [
            "item"=>$item,
        ];

        $address = Address::where("user_id", $user->id)->first();

        return view("purchase.purchase", compact("item","address"));
    }

    public function transaction(PurchaseRequest $request)
    {
        DB::beginTransaction();
        try{
        $user = Auth::user();

        $transactionData = $request->only(["item_id","post_code","address","building","payment_method"]);
        $transactionData["purchaser_id"] = $user->id;
        Transaction::create($transactionData);

        $item = Item::where('id', $transactionData["item_id"])->first();
        $itemData["soldout"] = 1;
        $item->update($itemData);

        DB::commit();

        return redirect("/mypage/mypage?tab=buy");

        }catch (\Exception $e) {
            DB::rollback();
            Log::error("Error: " . $e->getMessage());
            return back()->withErrors(["error", "エラーが発生しました"]);
        }

    }

    public function favorite(Request $request)
    {
        $user = Auth::user();
        $favoriteData["item_id"] = $request->input("item_id");
        $favoriteData["user_id"] = $user->id;

        Favorite::create($favoriteData);
        return redirect()->back();
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        Favorite::where("item_id", $request->item_id)
                ->where("user_id", $user->id)
                ->delete();
        return redirect()->back();
    }




}
