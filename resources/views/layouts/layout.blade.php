@yield('addSASS')
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>
    <div id="header" onclick="test()">
        ヘッダー
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
            @section('form')
            <form action="{{route(
            'index',
                [
                    'url_id' => $urlId
                ]
            )}}" method="post">
                @csrf
                <div class="form">
                    <h2>コメントを残す</h2>
                    <h3>フォーム</h3>
                    @if ($errors->has('name'))
                    <p>{{$errors->first('name')}}</p>
                    @endif
                    <label for="name">投稿者</label>
                    <input type="text" name="name">

                    @if ($errors->has('name'))
                    <p>{{$errors->first('comment')}}</p>
                    @endif
                    <label for="textarea">コメント</label>
                    <textarea id="textarea" name="comment" rows="10" cols="40"></textarea>
                    <input type="hidden" name="comment_url_id" value="{{$urlId}}">
                    <input type="submit" value="送信">

                </div>
            </form>

            <table class="table table-bordered table-responsive">
                @foreach($anchorComments as $anchorComment)
                <tr id="post_{{$anchorComment['id']}}">
                    <td>No.</td>

                    <td class="anchor" onclick="anchor(
                    {{$anchorComment['id']}}
                    )
                    scrollUp()">
                        {{$anchorComment['id']}}
                    </td>

                    <td>投稿者</td>
                    <td>{{$anchorComment['name']}}</td>
                    <td>コメント</td>
                    <td class="comment" data-toggle="tooltip" data-placement="top" data-delay="200" title="">
                      
                        @if(!empty($anchorComment['anchor_post']))
                        @foreach($anchorComment['anchor_post'] as $anchor)
                        <p onmouseover="display({{$anchor['id']}})">{{$anchorComment['comment']}}</p>
                        <p class="anchor" id="anchor_{{$anchor['id']}}">{{$anchor['comment']}}</p>
                        @endforeach
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>
            @show
        </div>



        @section('content')
        <div class="content">
            <table class="table table-hover table-bordered ">

                @foreach($contents as $content)
                <tr>
                    <th>
                        <span onclick="anchor({{$content->id}})">{{$loop->iteration}}</span> ：オープン2ちゃんねる
                    </th>
                    <td>{{($content->title)}}</td>
                    <th>投稿日時</th>
                    <td>{{($content->date)}}</td>
                    <th>{{$loop->iteration}}レス目</th>
                    <td>{{($content->putTogether)}}</td>
                </tr>
                @endforeach
            </table>
        </div>
        @show

    </div>
    <script type="text/javascript" src="/js/matome.js">
    </script>
</body>

</html>