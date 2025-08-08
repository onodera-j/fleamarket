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

class ChatController extends Controller
{
    public function index(Request $request)
    {
        return view('mypage.chat');
    }
}
