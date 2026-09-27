<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $general->siteName($pageTitle ?? '') }}</title>
    <link rel="shortcut icon" type="image/png" href="{{getImage(getFilePath('logoIcon') .'/favicon.png')}}">
    <link href="{{ asset('assets/common/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/main.css')}}">
    <script src="{{asset('assets/common/js/jquery-3.7.1.min.js')}}"></script>

    <style>
        #myProgress {
            width: 100%;
            background-color: #ddd;
            margin: 10px 0;
        }

        #myBar {
            width: 0%;
            height: 30px;
            background-color: #6a2dec;
            text-align: center;
            line-height: 30px;
            color: white;
            transition: width 0.1s linear;
        }

        #confirm {
            border-radius: 5px;
            padding: 3px 10px;
        }

        .inputcaptcha {
            width: 60px;
        }

        .btn {
            margin-top: -4px;
        }

        .adFram {
            border: 0;
            position: absolute;
            top: 14%;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%
        }

        .adBody {
            position: absolute;
            top: 30%;
            left: 40%;
        }

        .add_bg {
            background-color: hsl(var(--base)/0.8);
            position: relative;
            z-index: 9;
            padding: 10px 0;
        }

        #txtinput {
            border: none;
            outline: none;
            height: 35px;
            border-radius: 5px;
            padding: 20px 15px 17px 13px;
        }

        #confirm {
            border-radius: 5px !important;
        }

        .adcode {
            position: relative;
        }

        .adcode img {
            width: 60px;
            height: 35px;
        }

        .adcode .anso {
            position: absolute;
            top: 2px;
            left: 10px;
            color: hsl(var(--dark)/0.5)
        }

        @media only screen and (max-width: 441px) {
            #confirm {
                margin-top: 10px
            }
        }
    </style>
</head>

<body>
    <div class="add_bg">
        <div class="container">
            <div class="row align-items-center w-100">
                <div class="col-md-8 col-12">
                    <div class="left-form">
                        <form action="" id="confirm-form" method="post">
                            @csrf
                            <div>
                                <span id="inputcaptchahidden" class="d-none">
                                    <span class="adcode">
                                        <span id="ans" class="anso"></span>
                                        <img src="{{ asset($activeTemplateTrue . 'images/captcha.jpg') }}" alt="image">
                                    </span>
                                    <input type="hidden" id="mainCaptcha" readonly="readonly">
                                    <input type="text" id="txtinput" name="result" maxlength="4">
                                </span>
                                <button type="button" id="confirm" class="btn btn--base result-btn" disabled>
                                    @lang('Loading Ads')
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="rounded" id="myProgress">
                        <div class="rounded" id="myBar">@lang('0%')</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function ($,document) {
        "use strict";
        var durationSeconds = {{ $duration ?? $ptc->duration ?? 10 }};
        var cap = new Array('1','2','3','4','5','6','7','8','9','0');
        var i;
        for (i=0;i<4;i++){
            var a = cap[Math.floor(Math.random() * cap.length)];
            var b = cap[Math.floor(Math.random() * cap.length)];
            var c = cap[Math.floor(Math.random() * cap.length)];
            var d = cap[Math.floor(Math.random() * cap.length)];
        }
        var code = a + b + c + d;
        document.getElementById("mainCaptcha").value = code;
        document.getElementById('ans').innerHTML = code;

        $('#txtinput').on('input',function(){
            var string1 = document.getElementById('mainCaptcha').value;
            var string2 = document.querySelector('input[name=result]').value;

             if(parseInt(string1) === parseInt(string2)){
                var confirmButton = document.getElementById("confirm");
                confirmButton.removeAttribute('disabled');
                confirmButton.setAttribute('type','submit');
                confirmButton.className = "btn btn--base";
                document.getElementById('confirm-form').setAttribute('action','{{route('user.ptc.confirm',encrypt($ptc->id.'|'.auth()->user()->id))}}');
             }else{
                 var confirmButton = document.getElementById("confirm");
                 confirmButton.setAttribute('disabled', 'disabled');
                 confirmButton.className = "btn btn--base btn--sm";
                 confirmButton.removeAttribute('href', '#');
             }
        });

        var elem = document.getElementById("myBar");
        var width = 0;
        var intervalId = null;
        var stepTimeMs = (durationSeconds * 1000) / 100;

        function startTimer() {
            if (intervalId !== null || width >= 100) return;
            intervalId = setInterval(function frame() {
                if (document.visibilityState === 'hidden') {
                    pauseTimer();
                    return;
                }
                if (width >= 100) {
                    clearInterval(intervalId);
                    intervalId = null;
                    var confirmButton = document.getElementById("confirm");
                    confirmButton.className = "btn btn--base btn--danger";
                    confirmButton.innerHTML = "@lang('Confirm')";
                    var captchaInputHidden = document.getElementById("inputcaptchahidden");
                    captchaInputHidden.classList.remove("d-none");
                    elem.innerHTML = "100%";
                } else {
                    width++;
                    elem.style.width = width + '%';
                    elem.innerHTML = width + '%';
                }
            }, stepTimeMs);
        }

        function pauseTimer() {
            if (intervalId !== null) {
                clearInterval(intervalId);
                intervalId = null;
            }
            if (width < 100) {
                elem.innerHTML = "@lang('PAUSED - Stay on Window')";
            }
        }

        document.addEventListener('visibilitychange', function () {
            if (document.visibilityState === 'hidden') {
                pauseTimer();
            } else {
                startTimer();
            }
        });

        window.addEventListener('blur', function() {
            pauseTimer();
        });

        window.addEventListener('focus', function() {
            startTimer();
        });

        window.onload = startTimer;
    })(jQuery,document);
    </script>

    <div class="advertise-wrapper">
        @if($ptc->ads_type == 1)
        <iframe src="{{ $ptc->ads_body }}" class="adFram"></iframe>
        @elseif($ptc->ads_type==2)
        <div class="container mt-5 mb-5">
            <img src="{{ getImage(fileManager()->ptc()->path.'/'.$ptc->ads_body) }}" class="w-100">
        </div>
        @elseif($ptc->ads_type==3)
        <div class="adBody">
            @php echo $ptc->ads_body @endphp
        </div>
        @else
        <div class="d-flex justify-content-center">
            <div class="iframe-container">
                <iframe src="{{ $ptc->ads_body }}" title="YouTube video player" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        </div>
        @endif
        @include('includes.notify')
    </div>
</body>

</html>
