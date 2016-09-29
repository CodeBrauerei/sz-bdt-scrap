<?php
// quick and dirty
if (isset($_GET['images'])) {
    header('Content-Type: application/json');
    $arr = array_diff(scandir('data'), ['.', '..']);
    foreach ($arr as $key => $value) {
        if (strpos($value, '.json') !== false) { unset($arr[$key]); }
    }
    echo json_encode(array_values($arr));
    die();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <style>
        body {text-align: center;}
        img {max-width: 90vw; width: auto;}
    </style>
</head>
<body>
    <img src="http://placehold.it/1200x600?text=Navigate+with+your+keys+(left/right)">
    <p></p>
    <script src="//cdn.jsdelivr.net/g/mousetrap@1.6.0,superagent@0.18.0"></script>
    <script>
        function refreshImg() {
            document.querySelector('img').setAttribute('src', 'data/' + window.myImages[window.page]);
            superagent.get('data/' + window.myImages[window.page].replace('jpg', 'json') )
            .set('Accept', 'application/json')
            .end(function(err, res) {
                if (res.ok) {
                    document.querySelector('p').innerHTML = JSON.parse(res.text).text;
                }
            });
        }
        window.page = 0;
        superagent.get('?images')
        .set('Accept', 'application/json')
        .end(function(err, res) {
            if (err || !res.ok) {
                alert('Oh no! error');
            } else {
                window.myImages = JSON.parse(res.text);
            }
        });
        Mousetrap.bind('left', function(e) {
            window.page--;
            refreshImg();
            return false;
        });
        Mousetrap.bind('right', function(e) {
            window.page++;
            refreshImg();
            return false;
        });
    </script>
</body>
</html>