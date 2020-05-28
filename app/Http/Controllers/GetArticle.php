<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateForm;
use Illuminate\Http\Request;
use App\Open2che as AppOpen2che;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;
use App\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Http\RedirectResponse;
use App\Models\Url;
use App\Models\Content;
use App\Models\PostAnchor;
use App\Models\CommentAnchor;
use App\Models\AnchorComment;
use App\Models\Anchor;

class GetArticle extends Controller
{
    public function index(Request $request)
    {
        $userPosts = Post::with(
            [
                'comment_anchors', 'comment_anchors.post'
            ]
        )
            ->where('url_id', $request->input('url_id'))->orderBy('id', 'asc')->get();

        $test = Post::join(
            'comment_anchors',
            'comment_anchors.post_comment_id',
            '=',
            'posts.id'
        )
            ->where('url_id', $request->input('url_id'))
            ->whereColumn(
                'posts.id',
                'comment_anchors.post_comment_id'
            )->toSql();
            // dd($test);

        $anchorComments = $this->mappingPost($userPosts);
        // dd($userPosts);
        $urlId = $request->input('url_id');
        $errors = $this->saveUsersComment($request);
        $contents = Content::where('url_id', $urlId)->get();
        $urls = Url::all();

        return view(
            'index',
            [
                'userPosts' => $userPosts,
                'contents' => $contents,
                'urlId' => $request->input('url_id'),
                'errors' => $errors,
                'urls' => $urls,
                'anchorComments' => $anchorComments,
                // 'anchors' => $anchors,
            ]
        );
    }

    public function saveUsersComment(Request $request)
    {
        $usersComment = [];
        $rules =
            [
                'name' => 'required|max:20',
                'comment' => 'required'
            ];
        $messages =
            [
                'name.required' => '入力必須です',
                'name.max' => '20文字以下',
                'comment.required' => 'コメント必須です'
            ];
        $validation = Validator::make(
            $request->all(),
            $rules,
            $messages
        );
        if (!$validation->fails()) {
            $post = new Post();
            $post->name = $request->input('name');
            $post->comment = $request->input('comment');
            $post->url_id = $request->input('url_id');
            $post->save();

            if (preg_match(
                "/>>([0-9]+)/is",
                $request->input('comment'),
                $anchorNumber,
            )) {

                $commentAnchor = new CommentAnchor();
                $commentAnchor->post_comment_id = $anchorNumber[1];
                $commentAnchor->post_id = $post->id;
                $commentAnchor->save();
            }
        }
        return $validation->errors();
    }

    public function mappingPost($userPosts)
    {
        $array = [];
        foreach ($userPosts as $userPost) {
            $post = [];
            $post = [
                'id' => $userPost->id,
                'name' => $userPost->name,
                'comment' => $userPost->comment,
            ];
            foreach ($userPost->comment_anchors as $comment) {
                $post['anchor_post'][] = [
                    'id' => $comment->post->id,
                    'name' => $comment->post->name,
                    'comment' => $comment->post->comment,
                ];
            }
            $array[] = $post;
        }
        // dd($array);
        return $array;
    }


    //取得したデータをDBに登録
    public function saveArticle()
    {
        $getOpens = $this->getOpen();

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
                        'url_id' => 1
                    ];
            }
        }
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
