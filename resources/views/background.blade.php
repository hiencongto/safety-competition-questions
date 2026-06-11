{{-- <!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ques and Ans Layout</title>
    <style>
        body {
            margin: 0;
            height: 100vh;

            background-image: url('{{ asset('image/Picture-background.jpg') }}');
            background-repeat: no-repeat;
            background-position: center center;
            background-size: 100% 100%;
        }
    </style>
</head>
<body>
    <div class="page">
        
    </div>
</body>
</html> --}}


<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ques and Ans Layout</title>
    <style>
        body {
            margin: 0;
            height: 100vh;

            background-image: url('{{ asset('image/Picture-background.jpg') }}');
            background-repeat: no-repeat;
            background-position: center center;
            background-size: 100% 100%;
        }

        .btn-next {
            position: fixed;
            right: 20px;
            bottom: 20px;

            padding: 12px 24px;
            border: none;
            border-radius: 8px;

            background: #007bff;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .btn-next:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="page">
    </div>
    <button class="btn-next" onclick="goToQuesAndAns()">Tiếp theo</button>
</body>
<script>
  function goToQuesAndAns() {
    window.location.href = '{{ route("hmt.quesandans") }}';
  }
</script>
</html>