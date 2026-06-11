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
        }

        /* WHEEL */
        #wheelCanvas {
            grid-column: 1 / -1;
            grid-row: 1 / -1;
            place-self: center;
            z-index: 10;
        }

        /* BUTTON GIỮA WHEEL */
        .spin-btn {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 2px solid #000;
            background: #fff;
            font-weight: bold;
            cursor: pointer;
            z-index: 20;
        }

        .spin-btn:active {
            transform: translate(-50%, -50%) scale(0.95);
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/TweenMax.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/zarocknz/javascript-winwheel/Winwheel.min.js"></script>

</head>

<body>

@php
$questions = [
    "Câu 1",
    "Câu 2",
    "Câu 3",
    "Câu 4"
];
@endphp

<div class="page">
    <div class="grid-overlay">

        @for ($i = 0; $i < 576; $i++)
            <div></div>
        @endfor

        <div class="textbox-1"></div>
        <div class="textbox-2"></div>

        <canvas id="wheelCanvas" width="450" height="450"></canvas>

        <button class="spin-btn" onclick="startSpin()">QUAY</button>

    </div>
</div>

<script>
    const questions = @json($questions);

    let wheel = new Winwheel({
        canvasId: 'wheelCanvas',
        numSegments: questions.length,

        segments: questions.map(q => ({
            text: q,
            fillStyle: '#ffffff',
            strokeStyle: '#000000',
            textFillStyle: '#000000'
        })),

        animation: {
            type: 'spinToStop',
            duration: 5,
            spins: 8,
            callbackFinished: function (segment) {
                alert("Câu được chọn: " + segment.text);
            }
        }
    });

    wheel.draw();

    function startSpin() {
        wheel.startAnimation();
    }
</script>

</body>
</html>