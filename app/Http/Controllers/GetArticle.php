<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateForm;
use Illuminate\Http\Request;
use App\Models\Open2che;
use App\Models\Yahoo;
use App\Models\Cloning;
use App\Models\japanTime;
use App\Open2che as AppOpen2che;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;
use App\User;


class GetArticle extends Controller
{
    public function index(Request $request)
    {
        $this->sendArticle($request);


        $detailsOpen = Open2che::get();
        //  dd($detailsOpen);
        $detailsYahoo = Yahoo::get();
        $detailsJapan = japanTime::get();
        $userPost = Post::get();

        return view(
            'index',
            [
                'userPost' => $userPost,
                'detailsOpen' => $detailsOpen,
                'detailsYahoo' => $detailsYahoo,
                'detailsJapan' => $detailsJapan
            ]
        );
        // compact('index','detailsOpen', 'detailsYahoo', 'detailsJapan');
    }
    public function sendArticle(Request $request)
    {
        $informations = array();


        $rules = [
            'name' => 'required|max:20',
            'comment' => 'required'
        ];
        $messages = [
            'name.required' => '入力必須です',
            'name.max' => '20文字以下',
            'comment.required' => 'コメント必須です'
        ];
        $validation = Validator::make(
            $request->all(),
            $rules,
            $messages
        );
        if ($validation->fails()) {
            return redirect('/index')
                ->withErrors($validation)
                ->withInput();
        }
        $informations = array(
            'name' => $request->input('name'),
            'comment' => $request->input('comment')
        );
        Post::insert($informations);
    }

    //取得したデータをDBに登録
    public function saveArticle()
    {
        $getOpens = $this->getOpen();

        $detailsOpen = array();
        foreach ($getOpens as $open) {
            if (
                !empty($open['title']) &&
                !empty($open['date']) &&
                !empty($open['comment'])
            ) {
                $detailsOpen[] = array(
                    'title' => $open['title'],
                    'date' => $open['date'],
                    'comment' => $open['comment']
                );
            }
        }
        Open2che::insert($detailsOpen);

        // Open2ちゃん
        // foreach ($getOpens as $open) {
        //     $detail = array();
        //     if (
        //         !empty($open['title']) &&
        //         !empty($open['date']) &&
        //         !empty($open['comment']) 
        //     ) {
        //         $detail = array(
        //             'title' => $open['title'],
        //             'date' => $open['date'],
        //             'comment' => $open['comment']
        //         );
        //     }
        //     if(!empty($detail))$detailsOpen[] = $detail;
        // }


        // Yahoo!ファイナンス
        $getYahoos = $this->getYahoo();
        // dd($getYahoos);
        $detailsYahoo = array();
        foreach ($getYahoos as $getYahoo) {
            if (
                !empty($getYahoo['title']) &&
                !empty($getYahoo['date']) &&
                !empty($getYahoo['publisher'])
            ) {
                $detailsYahoo[] = array(
                    'title' => $getYahoo['title'],
                    'date' => $getYahoo['date'],
                    'publisher' => $getYahoo['publisher']
                );
            }
        }
        // dd($detailsYahoo);
        Yahoo::insert($detailsYahoo);


        // ジャパンタイムス
        $getJapans = $this->getJapan();
        // dd($getJapans);
        $detailsJapan = array();
        foreach ($getJapans as $japan) {
            if (
                !empty($japan['title']) &&
                !empty($japan['title']) &&
                !empty($japan['title'])
            ) {
                $detailsJapan[] = array(
                    'title' => $japan['title'],
                    'date' => $japan['date'],
                    'contributor' => $japan['contributor']
                );
            }
        }
        japanTime::insert($detailsJapan);
        return '正常にデータを取得しDBに保存しました';
    }


    // オープン２ちゃん取得
    private function getOpen()
    {
        $url = 'https://uni.open2ch.net/test/read.cgi/newsplus/1588817462/';
        $articles = @file_get_contents($url);
        // $articles = mb_convert_encoding($articles, "UTF-8", "SJIS");
        // dd($articles);

        if (preg_match_all(
            // "/<dt>(.*?)<br><br>/",
            "/<dl val=\"[0-9]+\">(.*?)<\/dl>/is",
            $articles,
            $contents,
        )) {
            // dd($contents);
            $detail = array();
            $details = array();
            foreach ($contents[0] as $res) {
                $detail = array();
                if (preg_match(
                    "/<b>(.*?)<\/b>/",
                    $res,
                    $title
                )) {
                    // dd($title);
                    $detail['title'] = $title[1];
                }
                if (preg_match(
                    "/[0-9]+\/[0-9]+\/[0-9]+\(.+?\)[0-9]+:[0-9]+:[0-9]+/us",
                    // "/<b>(.*?)<\/b>/",
                    $res,
                    $date
                )) {
                    // dd($date);
                    $detail['date'] = $date[0];
                }
                if (preg_match(
                    "/<dd class=\"(.*?)\" rnum=\"[0-9]+\">(.*?)<\/font>/is",
                    $res,
                    $comment
                )) {
                    // dd($res);
                    // dd($comment);
                    if (preg_match(
                        "/(.*?)<[a-z]+/is",
                        $comment[2],
                        $message
                    )) {
                        $detail['comment'] = $message[1];
                    } else {
                        $detail['comment'] = $comment[2];
                    }
                } else {
                    $detail['comment'] = '取れていない';
                }
                if (
                    !empty($detail['title']) &&
                    !empty($detail['date']) &&
                    !empty($detail['comment'])
                )
                    $details[] = $detail;
            }

            // dd($details);
            return !empty($details) ? $details : null;
        }
        return null;
    }

    // yahooファイナンス取得
    public function getYahoo()
    {
        $url = 'https://finance.yahoo.co.jp/';
        $articles = @file_get_contents($url);
        // $articles = mb_convert_encoding($articles, "UTF-8", "SJIS");
        // dd($articles);

        if (preg_match_all(
            // "/<dt>(.*?)<br><br>/",
            "/<li>(.*?)<\/li>/is",
            $articles,
            $contents,
        )) {
            // dd($contents);
            $detail = array();
            $details = array();
            // dd($contents[1]);
            foreach ($contents[1] as $res) {
                $detail = array();

                // $detail['title'] = $res;
                // dd($res);

                if (preg_match(
                    "/<span class=\"dtl\">(.*?)<\/span>/i",
                    $res,
                    $title
                )) {
                    // dd($title);
                    if (!empty($title[1]))
                        $detail['title'] = $title[1];
                }
                if (preg_match(
                    "/[0-9]+:[0-9]+/i",
                    $res,
                    $date
                )) {
                    if (!empty($date[0]))
                        $detail['date'] = $date[0];
                    // dd($detail['date']);
                }
                if (preg_match(
                    "/<span class=\"hdlSource\">(.*?)<\/span>/",
                    $res,
                    $publisher
                )) {
                    // dd($publisher);
                    if (!empty($publisher[1]))
                        $detail['publisher'] = $publisher[1];
                }
                if (
                    !empty($detail['title']) &&
                    !empty($detail['date']) &&
                    !empty($detail['publisher'])
                )
                    $details[] = $detail;
                // dd($detail);
            }
            // dd($details);
            return !empty($details) ? $details : null;
        }
        return null;
    }

    // japanTimes取得
    public function getJapan()
    {
        $url = 'https://www.japantimes.co.jp/';
        $articles = @file_get_contents($url);
        // $articles = mb_convert_encoding($articles, "UTF-8", "SJIS");
        // dd($articles);

        if (preg_match_all(
            // "/<dt>(.*?)<br><br>/",
            "/<header>(.*?)<\/header>/is",
            $articles,
            $contents,
        )) {
            // dd($contents);
            $detail = array();
            $details = array();

            foreach ($contents[1] as $res) {
                $detail = array();

                // $detail['title'] = $res;
                // dd($detail);

                if (preg_match(
                    "/<p><a href=\"(.*?)\">(.*?)<\/a><\/p>/is",
                    $res,
                    $title
                )) {
                    // dd($res);
                    $detail['title'] = $title[2];
                }
                if (preg_match(
                    "/<time datetime=\"(.*?)\" pubdate>(.*?)<\/time>/is",
                    $res,
                    $date
                )) {
                    $detail['date'] = $date[1];
                    // dd($detail['date']);
                }
                if (preg_match(
                    "/<p><a href=\"(.*?)\">(.*?)<\/a><\/p>/is",
                    $res,
                    $contributor
                )) {
                    // dd($publisher);
                    $detail['contributor'] = $contributor[2];
                }
                if (
                    !empty($detail['title']) &&
                    !empty($detail['date']) &&
                    !empty($detail['contributor'])
                )
                    $details[] = $detail;
                // dd($detail);
            }
            // dd($details);
            return !empty($details) ? $details : null;
        }
        return null;
    }

    public function saveUserInformation(Request $request)
    {
    }



    public function result(Yahoo $yahoos)
    {

        $data = [
            'msg' => $yahoos
        ];
        //  echo($data);
        return view(
            'sign_up',
            [
                'data' => $data
            ]
        );
    }
}
