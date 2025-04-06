<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PostCodeRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function passes($attribute, $value)
    {
       return preg_match('/^[0-9]{3}-?[0-9]{4}$|^[0-9]{7}$/', $value);
    }

    /**
     * バリデーションエラーメッセージの取得
     *
     * @return string
     */
    public function message()
    {
       // return 'validation.zipcode';
       //翻訳ファイルからメッセージを取得する場合
       return '郵便番号は7桁で入力してください';

    }

}
