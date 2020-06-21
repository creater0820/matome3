function anchor(id) {
    var userComment = document.querySelector('p.top').innerText;
    document.getElementById('textarea').value = ">>" + id;
}

function display(id) {
    // var element = document.getElementById('post_'+id).innerText;
    var element = document.getElementById('post_' + id);
    // var original = element.style.display;
    // element.style.visibility = "hidden";
    // document.getElementById('post_'+id).innerHTML=element;
}
function scrollUp() {
    window.scrollTo(0, 50);
}
function select(obj) {
    var idx = obj.selectedIndex;
    location.href = obj.options[idx].value;
}




function postForm(contentId,userId,urlId) {
    window.console.log(contentId);
    window.console.log(userId);
    window.console.log(urlId);
    var form = document.createElement('form');
    form.method = 'post';
    form.action = "/index?url_id="+urlId+ "#contents_" +contentId;

    var input = document.createElement('input');
    input.name = 'content_id';
    input.value = contentId;
    form.appendChild(input);

    var input = document.createElement('input');
    input.name = 'user_id';
    input.value = userId;
    form.appendChild(input);

    var csrf = document.createElement('input');
    // すでに存在しているname="csrf-token"のvalueの値を取得する。
    csrf.name = '_token';
    csrf.value = document.getElementsByName('csrf-token')[0].content;
    form.appendChild(csrf);

    document.body.appendChild(form);
    form.submit();
    // window.console.log(form.action);
}

function sort(obj, urlId) {
    // var idx = obj.selectedIndex;

    // var form = document.createElement('form');
    // form.method = 'post';
    // form.action = "/index?url_id=" + urlId;

    // var input = document.createElement('input');
    // input.name = 'sort_id';
    // input.value = obj.options[idx].value;
    // form.appendChild(input);

    // var csrf = document.createElement('input');
    // すでに存在しているname="csrf-token"のvalueの値を取得する。
    // csrf.name = '_token';
    // csrf.value = document.getElementsByName('csrf-token')[0].content;
    // form.appendChild(csrf);

    // document.body.appendChild(form);
    // form.submit();

    var idx = obj.selectedIndex;
    location.href = location.href + '&sort_id=' + obj.options[idx].value;
}

$(function () {
    $('button#text').on('click', function () {
        var city = $('#prefecture').val();
        var map = 'https://maps.googleapis.com/maps/api/geocode/json?address=' + city + '&components=country:JP&key=AIzaSyD6zYu86YxyiRQAkEXLZvqUS-ack6tAwVE';
        var url = "http://127.0.0.1:8000/page?url_id=3";
        $.ajax(map,
            {
                type: 'get',
                // dataType: 'json',
                // data:'',
            }
        ).done(function (data) {
            // window.console.log(data);
            var lat = data.results[0].geometry.location.lat;
            var lng = data.results[0].geometry.location.lng;
            createMap(lat, lng);
        });
    });
    function createMap(lat, lng) {
        var LatLng = new google.maps.LatLng(lat, lng);
        var map = new google.maps.Map(document.getElementById("map-canvas"), {
            center: new google.maps.LatLng(lat, lng),
            zoom: 11,
        });
    }
});



function prefecture() {
    var city = document.getElementById('prefecture').value;
    var url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' + city + '&components=country:JP&key=AIzaSyD6zYu86YxyiRQAkEXLZvqUS-ack6tAwVE';

    var req = new XMLHttpRequest();		  // XMLHttpRequest オブジェクトを生成する
    req.onreadystatechange = function () {		  // XMLHttpRequest オブジェクトの状態が変化した際に呼び出されるイベントハンドラ
        if (req.readyState == 4 && req.status == 200) { // サーバーからのレスポンス完了し、かつ、通信が正常に終了した場合
            var prefecture = JSON.parse(req.responseText);
            window.console.log(prefecture.results.length);

            if (prefecture.results.length) {
                var lat = prefecture.results[0].geometry.location.lat;		          // 取得した JSON ファイルの中身を表示
                var lng = prefecture.results[0].geometry.location.lng;
                // 取得した JSON ファイルの中身を表示
                var mapDiv = document.getElementById("map-canvas");
                var map = new google.maps.Map(mapDiv, {
                    center: new google.maps.LatLng(lat, lng),
                    zoom: 11,
                });
            }
        }
    };
    req.open("GET", url, false); // HTTPメソッドとアクセスするサーバーの　URL　を指定
    req.send(null);					    // 実際にサーバーへリクエストを送信
}

$(function () {
    $('.test').on('click', function () {
        var url = 'http://127.0.0.1:8000/api/test';
        $.ajax(url,
            {
                type: 'post',
                data: { test: $('#prefecture').val() },
                dataType: 'json'
            }
        ).done(function (data) {
            // 結果リストをクリア
            window.console.log(data);
        });
    });
});

function current() {
    // Geolocation APIに対応している
    if (navigator.geolocation) {
        alert("この端末では位置情報が取得できます");
        // Geolocation APIに対応していない
    } else {
        alert("この端末では位置情報が取得できません");
    }

    // 現在地取得処理
    function getPosition() {
        // 現在地を取得
        navigator.geolocation.getCurrentPosition(
            // 取得成功した場合
            function (position) {
                alert("緯度:" + position.coords.latitude + ",経度" + position.coords.longitude);
            },
            // 取得失敗した場合
            function (error) {
                switch (error.code) {
                    case 1: //PERMISSION_DENIED
                        alert("位置情報の利用が許可されていません");
                        break;
                    case 2: //POSITION_UNAVAILABLE
                        alert("現在位置が取得できませんでした");
                        break;
                    case 3: //TIMEOUT
                        alert("タイムアウトになりました");
                        break;
                    default:
                        alert("その他のエラー(エラーコード:" + error.code + ")");
                        break;
                }
            }
        );
    }
}