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
    <div class="container">

        @section('form')
        <form action="/index" method="post">
            @csrf
            <h2>コメントを残す</h2>
            <h3>フォーム</h3>
            <div>
                @if ($errors->has('name'))
                <p>{{$errors->first('name')}}</p>
                @endif
                <label for="name">投稿者</label>
                <input type="text" name="name">
            </div>
            <div>
                @if ($errors->has('name'))
                <p>{{$errors->first('comment')}}</p>
                @endif
                <label for="textarea">コメント</label>
                <textarea name="comment">
                </textarea>
            </div>
            <div>
                <input type="submit" value="送信">
            </div>
        </form>
        @isset($userPost)
        @foreach($userPost as $newone)
        <table class="table table-borderd table-responsive">
            <th>投稿者</th>
            <td>{{$newone->name}}</td>
            <th>コメント</th>
            <td>{{$newone->comment}}</td>

            @endforeach
            @endisset
    </div>
    @show

    <table class="table table-hover table-bordered ">
        @foreach($detailsOpen as $detailOpen)
        <tr>
            <th>
                {{$loop->iteration}}：オープン2ちゃんねる
            </th>
            <!-- <td>残り{{$loop->remaining}}</td> -->
            <td>{{($detailOpen->title)}}</td>
            <th>投稿日時</th>
            <td>{{($detailOpen->date)}}</td>
            <th>{{$loop->iteration}}レス目</th>
            <td>{{($detailOpen->comment)}}</td>
        </tr>
        @endforeach

        @foreach($detailsYahoo as $detailYahoo)
        <tr>
            <th>Yahoo!ファイナンス</th>
            <td>{{$detailYahoo->title}}</td>
            <th>更新時間</th>
            <td>{{$detailYahoo->date}}</td>
            <th>投稿者</th>
            <td>{{$detailYahoo->publisher}}</td>

        </tr>
        @endforeach

        @foreach($detailsJapan as $detailJapan)
        <tr>
            <th>JapanTimes</th>
            <td>{{$detailJapan->title}}</td>
            <th>更新時間</th>
            <td>{{$detailJapan->date}}</td>
            <th>カテゴリー</th>
            <td>{{$detailJapan->contributor}}</td>

        </tr>
        @endforeach

    </table>
    </div>
</body>

</html>