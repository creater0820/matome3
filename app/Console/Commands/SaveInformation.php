<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Models\Content;

class SaveInformation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contents:save';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('saveInformation start');
        $getOpens = $this->getOpen();
        $this->info('getOpens length '.count($getOpens));

        $detailsOpen = [];
        foreach ($getOpens as $open) {
            if (
                !empty($open['title']) &&
                !empty($open['date']) &&
                !empty($open['putTogether'])
            ) {
                $detailsOpen[] =
                    [
                        'title' => $open['title'],
                        'date' => $open['date'],
                        'putTogether' => $open['putTogether'],
                        'url_id' => 1,
                    ];
            }
        }
        $this->info('detailsOpen length '.count($detailsOpen));

        Content::insert($detailsOpen);

        // Yahoo!ファイナンス
        $getYahoos = $this->getYahoo();

        $detailsYahoo = [];
        foreach ($getYahoos as $getYahoo) {
            if (
                !empty($getYahoo['title']) &&
                !empty($getYahoo['date']) &&
                !empty($getYahoo['putTogether'])
            ) {
                $detailsYahoo[] =
                    [
                        'title' => $getYahoo['title'],
                        'date' => $getYahoo['date'],
                        'putTogether' => $getYahoo['putTogether'],
                        'url_id' => 2,
                    ];
            }
        }
        Content::insert($detailsYahoo);

        // ジャパンタイムス
        $getJapans = $this->getJapan();
        // dd($getJapans);
        $detailsJapan = [];
        foreach ($getJapans as $japan) {
            if (
                !empty($japan['title']) &&
                !empty($japan['date']) &&
                !empty($japan['putTogether'])
            ) {
                $detailsJapan[] =
                    [
                        'title' => $japan['title'],
                        'date' => $japan['date'],
                        'putTogether' => $japan['putTogether'],
                        'url_id' => 3
                    ];
            }
        }
        Content::insert($detailsJapan);
        $this->info('saveInformation end');

        return '正常にデータを取得しDBに保存しました';
    }
     // オープン２ちゃん取得
     public function getOpen()
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
             $detail = [];
             $details = [];
             foreach ($contents[0] as $res) {
                 $detail = [];
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
                         $detail['putTogether'] = $message[1];
                     } else {
                         $detail['putTogether'] = $comment[2];
                     }
                 } else {
                     $detail['putTogether'] = '取れていない';
                 }
                 if (
                     !empty($detail['title']) &&
                     !empty($detail['date']) &&
                     !empty($detail['putTogether'])
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
             $detail = [];
             $details = [];
             // dd($contents[1]);
             foreach ($contents[1] as $res) {
                 $detail = [];
 
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
                         $detail['putTogether'] = $publisher[1];
                 }
                 if (
                     !empty($detail['title']) &&
                     !empty($detail['date']) &&
                     !empty($detail['putTogether'])
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
             $detail = [];
             $details = [];
 
             foreach ($contents[1] as $res) {
                 $detail = [];
 
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
                 if (preg_match(
                     "/<p><a href=\"(.*?)\">(.*?)<\/a><\/p>/is",
                     $res,
                     $contributor
                 )) {
                     // dd($publisher);
                     $detail['putTogether'] = $contributor[2];
                 }
                 if (
                     !empty($detail['title']) &&
                     !empty($detail['date']) &&
                     !empty($detail['putTogether'])
                 )
                     $details[] = $detail;
                 // dd($detail);
             }
             // dd($details);
             return !empty($details) ? $details : null;
         }
         return null;
     }
}

