<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Open2che;
use App\Models\Yahoo;
use App\Models\Cloning;
use App\Models\japanTime;
use App\Open2che as AppOpen2che;
use Illuminate\Support\Facades\DB;


class GetArticle extends Controller
{
    public function index()
    {
        $detailsOpen = Open2che::get();
        //  dd($detailsOpen);
        $detailsYahoo = Yahoo::get();
        $detailsJapan = japanTime::get();
        return view(
            'index',
            [
                'detailsOpen' => $detailsOpen,
                'detailsYahoo' => $detailsYahoo,
                'detailsJapan' => $detailsJapan
            ]
        );
        // compact('index','detailsOpen', 'detailsYahoo', 'detailsJapan');
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
                    'date' => $japan['date']
                );
            }
        }
        japanTime::insert($detailsJapan);
        return '正常にデータを取得しDBに保存しました';
    }


    // オープン２ちゃん取得
    private function getOpen()
    {
        $url = 'https://uni.open2ch.net/test/read.cgi/newsplus/1588817462/l10';
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
                    "/<dd class=\"(.*?)\"rnum=\"[0-9]+\">(.*?)<\/font>/is",
                    $res,
                    $comment
                )) {
                    dd($comment);
                    $detail['comment'] = $comment[2];
                }
                dd($res);
                // dd($comment);
                $detail['comment'] = "修正！レスが取れてない";
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
                    // dd($title);
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
                // if (preg_match(
                //     "/<h3><a href=\"(.*?)\"class=\"category-link\">(.*?)<\/a><\/h3>/is",
                //     $res,
                //     $publisher
                // )) {
                //     dd($publisher);
                //     $detail['publisher'] = $publisher[1];
                // }
                if (
                    !empty($detail['title']) &&
                    !empty($detail['date'])
                )
                    $details[] = $detail;
                // dd($detail);
            }
            // dd($details);
            return !empty($details) ? $details : null;
        }
        return null;
    }





    public function sign_up()
    {

        return view('sign_up');
    }
    public function sign_in()
    {

        return view('sign_in');
    }



    // class GetArticle extends Controller
    // {

    //         public function index(Request $request)
    //         {
    //             $items = ::all();
    //             return view('user.index', ['items' => $items]);
    //         }
    //     }


    // public function try()
    // {
    // }






    //     public function store(Request $request)
    //     {

    // $items = ::all();

    //     }
    // }
}
