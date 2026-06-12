<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cuộc thi kiến thức an toàn và chất lượng</title>
    <link rel="icon" href="{{ asset('image/iconHMT.ico') }}">
    <style>
        /* Reset & base styles */
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
            font-family: 'Arial', sans-serif;
        }

        .page {
            position: relative;
            width: 100%;
            height: 100%;
        }

        /* GRID OVERLAY WITH RESTORED COLORS – VISIBLE LAYOUT GUIDES */
        .grid-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: grid;
            grid-template-columns: repeat(24, 1fr);
            grid-template-rows: repeat(24, 1fr);
            /* pointer-events: none;   */
        }


        .grid-overlay > div {
            background-color: transparent;
            border: 1px solid transparent;
            transition: all 0.1s ease;
            /* pointer-events: none; */
        }

        /* Optional hover effect to highlight cells (does not affect functionality) */
        .grid-overlay > div:hover {
            background-color: transparent;
            border-color: transparent;
        }

        /* Text containers – transparent background, placed above grid */
        .textbox-1,
        .textbox-2,
        .question-id-box {
            pointer-events: auto;
            z-index: 5;
        }

        .textbox-1,
        .textbox-2 {
            background-color: transparent !important;
            border: none !important;
            pointer-events: auto;
            z-index: 5;
        }

        .question-id-box {
            grid-column: 7 / 9;
            grid-row: 8 / 10;
            padding: 12px 16px;
            font-size: 32px;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            text-shadow: 0 1px 2px rgba(255,255,255,0.6);
            background: rgba(255, 255, 255, 0.75);
            text-align: center;
        }

        /* Question area (grid position: columns 5-20, rows 10-15) */
        .textbox-1 {
            grid-column: 5 / 20;
            grid-row: 10 / 15;
            padding: 15px;
            font-size: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            white-space: pre-wrap;
            color: #1e2a3a;
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(255,255,255,0.6);
        }

        /* Answer area (grid position: columns 5-20, rows 18-23) */
        .textbox-2 {
            grid-column: 5 / 20;
            grid-row: 18 / 23;
            padding: 15px;
            font-size: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            white-space: pre-wrap;
            color: #1e2a3a;
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(255,255,255,0.6);
        }

        /* Overlay with backdrop-filter – blurs only the background content */
        .slot-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 15;
            display: none;
        }

        /* Slot machine container – appears above overlay, not blurred */
        .slot-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 20;
            display: none;
            text-align: center;
        }

        .slot-machine {
            display: flex;
            gap: 30px;
            background: rgba(0, 0, 0, 0.85);
            padding: 40px 50px;
            border-radius: 50px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
        }

        .slot-reel {
            width: 140px;
            height: 180px;
            background: linear-gradient(145deg, #fff, #e0e0e0);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 90px;
            font-weight: bold;
            font-family: 'Courier New', monospace;
            color: #222;
            box-shadow: inset 0 0 15px rgba(0, 0, 0, 0.2), 0 10px 20px rgba(0, 0, 0, 0.3);
            border: 5px solid #ffcc00;
        }

        .spin-slot-btn {
            margin-top: 30px;
            width: 180px;
            height: 70px;
            border-radius: 40px;
            border: none;
            background: #ffcc00;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 5px 0 #b37b00;
            transition: 0.05s linear;
            font-family: Arial;
        }

        .spin-slot-btn:active {
            transform: translateY(3px);
            box-shadow: 0 2px 0 #b37b00;
        }

        /* Button to toggle slot machine visibility */
        .toggle-slot-btn {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 30;
            background: #ffcc00;
            border: none;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        /* Remaining questions counter */
        .remain-info {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 14px;
            z-index: 25;
            pointer-events: none;
        }

        /* Next question button */
        .next-question-btn {
            position: fixed;
            bottom: 80px;
            right: 20px;
            background: #ffcc00;
            border: none;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 25;
            transition: 0.1s linear;
        }

        .next-question-btn:active {
            transform: translateY(2px);
        }

        .next-question-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .reset-question-btn {
            position: fixed;
            bottom: 90px;
            left: 20px;
            background: #f44336;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 30;
            transition: 0.1s linear;
        }

        .reset-question-btn:active {
            transform: translateY(2px);
        }

        /* Answer reveal button (can be injected by JS) */
        .view-answer-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 18px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.1s linear;
        }

        .view-answer-btn:active {
            transform: translateY(2px);
        }

        @media (max-width: 700px) {
            .slot-reel { width: 80px; height: 110px; font-size: 55px; }
            .slot-machine { gap: 15px; padding: 20px 30px; }
            .spin-slot-btn { width: 140px; height: 55px; font-size: 22px; }
            .next-question-btn { bottom: 70px; font-size: 14px; padding: 8px 16px; }
            .remain-info { bottom: 20px; font-size: 12px; }
            .question-id-box { font-size: 22px; padding: 10px 14px; }
            /* .grid-overlay > div {
                border-width: 0.5px;
                background-color: rgba(65, 105, 225, 0.1);
            } */
        }

        /* Utility class to temporarily hide grid colors (toggle via Ctrl+Alt+G) */
        /* .grid-overlay.hide-grid > div {
            background-color: transparent;
            border-color: transparent;
        }
        .grid-overlay.hide-grid > div:hover {
            background-color: transparent;
        } */
    </style>
</head>
<body>

@php
    $questions = $dataPool ?? [];
    $totalQuestions = count($questions);
@endphp

<div class="page">
    <div class="grid-overlay">
        @for ($i = 0; $i < 576; $i++) <div style="pointer-events: none;"></div> @endfor
        <div class="textbox-1" id="questionBox" contenteditable="false" placeholder="Câu hỏi sẽ xuất hiện ở đây..."></div>
        <div class="question-id-box" id="questionIdBox"></div>
        <div class="textbox-2" id="answerBox" contenteditable="false" placeholder="Đáp án sẽ hiện ở đây..."></div>
    </div>

    <!-- Button to toggle slot machine -->
    <button class="toggle-slot-btn" id="toggleSlotBtn">Mở máy quay</button>

    <!-- Nút câu hỏi tiếp theo -->
    <button class="next-question-btn" id="nextQuestionBtn">Câu hỏi tiếp theo</button>
    <button class="reset-question-btn" id="resetQuestionBtn">Reset câu hỏi</button>

    <!-- Dark overlay with blur effect (only blurs background content) -->
    <div class="slot-overlay" id="slotOverlay"></div>

    <!-- Slot machine container (not blurred) -->
    <div class="slot-container" id="slotContainer">
        <div class="slot-machine">
            <div class="slot-reel" id="reel1">0</div>
            <div class="slot-reel" id="reel2">0</div>
            <div class="slot-reel" id="reel3">0</div>
        </div>
        <button class="spin-slot-btn" id="spinBtn">QUAY</button>
    </div>

    <div class="remain-info" id="remainInfo">Câu còn lại: {{ $totalQuestions }}</div>
</div>

<script>
    window.allQuestions = @json($questions);
</script>
<script src="{{ asset('js/wheel.js') }}"></script>
</body>
</html>