<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ques and Ans Layout</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            height: 100vh;
            background-image: url('{{ asset('image/Picture-ques-ans.png') }}');
            background-repeat: no-repeat;
            background-position: center center;
            background-size: 100% 100%;
            overflow: hidden;
        }

        .page {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .grid-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: grid;
            grid-template-columns: repeat(24, 1fr);
            grid-template-rows: repeat(24, 1fr);
            pointer-events: none;
        }

        .grid-overlay > div {
            /* border: 1px solid rgba(0, 0, 0, 0.3); */
        }

        .textbox-1 {
            grid-column: 5 / 20;
            grid-row: 10 / 15;
            background-color: transparent;
            border: 2px solid #333;
            padding-top: 15px;
            font-family: Arial, sans-serif;
            font-size: 16px;
            resize: none;
            pointer-events: auto;
        }
        .textbox-2 {
            grid-column: 5 / 20;
            grid-row: 18 / 23;
            background-color: transparent;
            border: 2px solid #333;
            padding-top: 20px;
            font-family: Arial, sans-serif;
            font-size: 16px;
            resize: none;
            pointer-events: auto;
        }    </style>
</head>
<body>
    <div class="page">
        <div class="grid-overlay">
            @for ($i = 0; $i < 576; $i++)
                <div></div>
            @endfor
            <div class="textbox-1">
               
            </div>
            <div class="textbox-2">
                
            </div>
        </div>
    </div>
</body>
</html>
