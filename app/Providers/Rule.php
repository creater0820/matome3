<?php

namespace App\Providers;

class Rule
{
    public static function commentRule()
    {
       return
            [
                'name' => 'required|max:20',
                'comment' => 'required',
            ];
    }
    public static function commentMessage()
    {
        return
            [
                'name.required' => '入力必須です',
                'name.max' => '20文字以下',
                'comment.required' => 'コメント必須です'
            ];
    }
}
