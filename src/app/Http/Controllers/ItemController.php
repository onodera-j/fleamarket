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
use Stripe\Stripe;
use Stripe\Checkout\Session;

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
                                            ->whereHas("item", function($query) use ($user){
                                                $query->where("user_id","!=", $user->id);
                                            })
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
            if($user->address){
                $Items = Item::where("user_id","!=", $user->id)->get();
            }else{
                return redirect('/mypage/profile');
            }
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

            $item = Item::findOrFail($transactionData["item_id"]);

            if (app()->environment('testing')) {
                Transaction::create($transactionData);
                $item->update(['soldout' => 1]);
                DB::commit();
                return redirect('/mypage/mypage?tab=buy')->with('success', 'テスト決済完了');
            }

            Stripe::setApikey(config('services.stripe.secret'));

            $paymentMethod = (int) $transactionData['payment_method'];
            $paymentType = $paymentMethod === 1 ? 'konbini' : 'card';

            $checkoutSession = Session::create([
                'payment_method_types' => [$paymentType],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->item_name,
                        ],
                        'unit_amount' => $item->price
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('transaction.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('purchase.cancel', ['item' => $item->id]),
                'metadata' => [
                    'user_id' => $user->id,
                    'item_id' => $item->id,
                    'payment_method' => $paymentMethod,
                    ]
            ]);

                DB::commit();

                return redirect($checkoutSession->url);

        }catch (\Exception $e) {
            DB::rollback();
            Log::error("Error: " . $e->getMessage());
            return back()->withErrors(["error", "エラーが発生しました"]);
        }
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::retrieve($sessionId);

        $itemId = $session->metadata->item_id;
        $paymentMethod = (int) ($session->metadata->payment_method);

        $item = Item::find($itemId);
        $item->update(['soldout' => 1]);

        $userAddressData = Address::where('user_id', Auth::id())->first();

        Transaction::create([
            'item_id' => $itemId,
            'purchaser_id' => Auth::id(),
            'post_code' => $userAddressData->post_code,
            'address' => $userAddressData->address,
            'building' => $userAddressData->building,
            'payment_method' => $paymentMethod,
        ]);

        return redirect('/mypage/mypage?tab=buy')->with('success', '支払いが完了しました！');
    }
    public function cancel(Request $request)
    {
        $itemId = $request->query('item');

        return redirect("/purchase/{$itemId}")->with('message', '支払いがキャンセルされました');
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
