<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // "item_image" => ["required","image","mimes:jpeg,png"],
            "condition" => ["required"],
            "category_id" => ["required","array","min:1"],
            "item_name" => ["required"],
            "item_detail" => ["required","max:255"],
            "price" => ["required", "integer", "min:0"],
        ];
    }

     public function messages()
    {
        return [
            "item_image.required" => "画像を選択してください",
            "item_image.image" => "指定されたファイルが画像ではありません",
            "item_image.mimes" => "拡張子は.jpegまたは.pngにしてください",
            "condition.required" => "状態を選択してください",
            "category_id.required" => "カテゴリを1つ以上選択してください",
            "category_id.min" => "カテゴリを1つ以上選択してください",
            "item_name.required" => "商品名を入力してください",
            "item_detail.required" => "商品の説明を入力してください",
            "item_detail.max" => "入力できるのは最大255文字です",
            "price.required" => "販売価格を入力してください",
            "price.integer" => "販売価格は数値で入力してください",
            "price.min" => "販売価格は0円以上で入力してください",
        ];


    }
}
