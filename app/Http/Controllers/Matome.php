<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class Matome extends Controller
{

    public function index()
    {
        $urls  = DB::table('urls')->get();
        dd($urls);
        $detailsYahoo = array();
        foreach ($urls as $url) {
            if ($url->id === 1) {
                // Open2ちゃん
                $getSureds = $this->getOpen();
            } else if ($url->id === 2) {
                // Yahoo!ファイナンス
                $getSureds = array_slice($this->getYahoo(), 1, 7);
            }
            if (empty($getSureds)) continue;
            foreach ($getSureds as $getOpen) {
                $publisher = '';
                $gotContent = array();
                if (!empty($getOpen['comment'])) $publisher = $getOpen['comment'];
                if (!empty($getOpen['publisher'])) $publisher = $getOpen['publisher'];

                $gotContent['url_id'] = $url->id;
                $gotContent['title'] = $getOpen['title'];
                $gotContent['date'] = $getOpen['date'];
                $gotContent['publisher'] = $publisher;
                $detailsYahoo[] = $gotContent;
            }
        }
        dd($detailsYahoo);
        foreach ($detailsYahoo as $open) {
            DB::table('sureds')->insert([
                'url_id' => $open['url_id'],
                'title'           => $open['title'],
                'date'      => $open['date'],
                'publisher'          => $open['publisher']
            ]);
        }
        // $getJapans[] = array_slice($this->getJapan(), 9, 5);
        // // dd($getJapans);
        // foreach ($getJapans[0] as $getJapan) {
        //     $gotContent['title'] = $getJapan['title'];
        //     $gotContent['date'] = $getJapan['date'];
        //     // $gotContent['publisher'] = $getJapan['publisher'];
        //     $detailsJapan[] = $gotContent;
        //     // dd($detailsJapan);
        // }
        // foreach ($detailsJapan as $japan) {
        //     DB::table('japan_times')->insert([
        //         'title' => $japan['title'],
        //         'date' => $japan['date']
        //     ]);
        // }

        return view('index', compact('detailsYahoo'));
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
                    "/^<dd class=\"(.*)\" rnum=\"[0-9]+\">(.*)<\/dd>/is",
                    $res,
                    $comment
                )) {
                    dd($comment);
                    $detail['publisher'] = $comment[2];
                }
                $detail['publisher'] = "修正！レスが取れてない";
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

            foreach ($contents[1] as $res) {
                $detail = array();

                // $detail['title'] = $res;
                // dd($detail);

                if (preg_match(
                    "/<span class=\"dtl\">(.*?)<\/span>/is",
                    $res,
                    $title
                )) {
                    // dd($title);
                    $detail['title'] = $title[1];
                }
                if (preg_match(
                    "/[0-9]+:[0-9]+/is",
                    $res,
                    $date
                )) {
                    $detail['date'] = $date;
                    // dd($detail['date']);
                }
                if (preg_match(
                    "/<span class=\"hdlSource\">(.*?)<\/span>/",
                    $res,
                    $publisher
                )) {
                    // dd($publisher);
                    $detail['publisher'] = $publisher[1];
                }
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
    //     public function index()
    //     {
    //         $articles = @file_get_contents('https://finance.yahoo.co.jp/');
    //         if (preg_match_all("/<p>(.*?)<\/p>/i", $articles, $contents)) {
    //             return view('index', compact('contents'));
    //         }
    //     }


    public function try()
    {
    }






    // public function store(Request $request)
    // {

    //     $articles = @file_get_contents('https://finance.yahoo.co.jp/');
    //     if (preg_match_all("/<p>(.*?)<\/p>/i", $articles, $contents)) 


    //     $contact = new Cloning;
    //     DB::table('clonings')->insert(
    //       ['id' => $contents]
    //     );
    //     $contact->save();
    //     // return redirect('contact/index');
    // }
}
