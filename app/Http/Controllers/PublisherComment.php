<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publisher;
use App\Models\Comment;

class PublisherComment extends Controller
{
    //
public function saveBoth(){
    $this->savePublisher();
    $this->saveComment();
    return '成功';
}


    public function saveComment()
    {
        $contents = array(
            [
                'publisher_id' => '1',
                'title' => 'こんにちは',
            ],
            [
                'publisher_id' => '2',
                'title' => 'hello',
            ],
            [
                'publisher_id' => '2',
                'title' => 'How are you?',
            ],
            [
                'publisher_id' => '2',
                'title' => 'what\'s up?',
            ],
            [
                'publisher_id' => '2',
                'title' => 'How have you been?',
            ],
            [
                'publisher_id' => '2',
                'title' => 'さようなら',
            ],
            [
                'publisher_id' => '2',
                'title' => 'おやすみ',
            ]

        );
        Comment::insert($contents);
        return 'commentsテーブルに正常に登録しました';
    }


    public function savePublisher()
    {
        $contents = array(
            [
                'name' => '横山'
            ],
            [
                'name' => '北山'
            ],
            [
                'name' => '神谷'
            ]
        );
        Publisher::insert($contents);
        return '正常にPublishersテーブルに保存しました';
    }
}
