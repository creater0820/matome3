@extends('layouts.layout')

@section('addSASS')
<link href="{{ asset('css/sign_up.css') }}" rel="stylesheet">
@endsection

@section('addJs')
<script type="text/javascript" src="/js/matomeApi.js"></script>
@endsection

@section('content')

<div class="header">
</div>
<div class="container">
    <div class="link">
        <ul>
            <li>
                <a href="">トップページへ</a>
            </li>
            @foreach($urls as $url)
            <li>
                <a href="{{route(
                        'index',
                        [
                            'url_id' => $url->id
                        ]
                        )}}">{{$url->title}}
                </a>
            </li>
            @endforeach
        </ul>
    </div>

    <div class="userPost">

        <div>
            <p class="error_name"></p>
            <label for="name">投稿者</label>
            <input class="comment_name" type="text" name="name" value="名無しさん">
            <p class="error_comment"></p>
            <div><label for="textarea">コメント</label></div>
            <textarea class="comment_text" name="comment" rows="10" cols="40"></textarea>
            <input type="hidden" class="comment_url_id" value="{{$urlId}}">
            <button type="button" class="btn btn-info commit">
                送信</button>
        </div>




        @foreach($anchorComments as $anchorComment)
        <div class="user_post">
            <p class="top" onclick="anchor(
                    {{$anchorComment['id']}}
                    )
                    scrollUp()">{!!$anchorComment['id']!!}
                [{{$anchorComment['created_at']}}]</p>

            <div class="comment" data-toggle="tooltip" data-placement="top" data-delay="200" title="">
                <div class="tooltip1">
                    <p>{!! $anchorComment['comment'] !!}</p>
                    @if(!empty($anchorComment['anchor_post']))
                    @foreach($anchorComment['anchor_post'] as $anchor)
                    <div class="description1">
                        <p class="top">{{$anchor['id']}} [{{$anchor['created_at']}}]</p>
                        <p class="middle">{{$anchor['anchor_comment']}}</p>
                        <p class="bottom">[{{$anchor['name']}}]</p>
                    </div>
                    @endforeach
                    @endif
                </div>
                <p class="bottom"><span>[{{$anchorComment['name']}}]</span></p>
            </div>
        </div>
        @endforeach

    </div>

    <div class="content">
        <div id="map-canvas"></div>

        @foreach($todaysInformation as $todayInformation)
        <div id="contents_{{($todayInformation->id)}}">
            <p class="top">{{($todayInformation->date)}}</p>
            <p class="middle">{!!($todayInformation->putTogether)!!}</p>
            <p class="bottom">[{{($todayInformation->title)}}]</p>
            @if($todayInformation->user_favorite)
            <button type="button" class="btn btn-info" disabled>いいね！
                ({{($todayInformation->favorite_count)}})</button>
            @else
            <button type="button" class="btn btn-info favorite" data-content-id="{{($todayInformation->id)}}" data-user-id="{{$userId}}" data-url-id="{{$urlId}}">いいね！
                ({{($todayInformation->favorite_count)}})</button>
            @endif

        </div>
        @endforeach
    </div>
    <div class="right-box">
        <div class="variety">
            <span class="">検索</span>
            <input type="text">
        </div>
        <div class="month">
            <span>日付</span>
            <select name="month" id="">
                <option value="">1月</option>
                <option value="">2月</option>
                <option value="">3月</option>
                <option value="">4月</option>
                <option value="">5月</option>
                <option value="">6月</option>
            </select>
        </div>
        <div class="variety">
            <span class="">サイトの種類</span>
            <select name="month" onChange="select(this)">
                <option value="">選択してください</option>
                <option value="http://127.0.0.1:8000/page?url_id=1">オープン２ちゃん</option>
                <option value="http://127.0.0.1:8000/page?url_id=2">Yahoo!ファイナンス</option>
                <option value="http://127.0.0.1:8000/page?url_id=3">JapanTimes</option>

            </select>
        </div>
        <div class="sort">
            <span>並び替え</span>
            <select name="sort" onChange="sort(this,{{$urlId}})">
                <option value="">選択してください</option>
                <option value="1">いいね順</option>
                <option value="2">更新日</option>
            </select>
        </div>
        <div class="prefecture">
            <span>都道府県</span>
            <input type="text" id="prefecture"><button id="text">検索</button>
        </div>
        <button class="test">テスト</button>
        <button onclick="current()">現在位置</button>

    </div>

</div>
@endsection