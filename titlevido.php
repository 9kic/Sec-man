<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحة الفيديو</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }
        video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <video id="videoPlayer" controls autoplay>
        <source src="imges/lv_0_20230920183234.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <script src="vido.js"></script>
</body>
</html>