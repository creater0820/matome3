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
    @section('content')
    <p>親の要素です</p>
    @show
    <table class="tabel table-hover table-bordered " >
        @foreach($detailsOpen as $detailOpen)
        <tr>
            <th>Open2ちゃんねる</th>
            <td>{{($detailOpen->title)}}</td>
            <th>投稿日時</th>
            <td>{{($detailOpen->date)}}</td>
            <th>コメント</th>
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
            <td>取れていない</td>
            
        </tr>
        @endforeach
        
    </table>
</div>
</body>
</html>
