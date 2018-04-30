<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> --}}
        {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> --}}

        <title>Teleport</title>
        
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            p {
                font-size: 15%;
                color: red;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif
                <div class="content">

                    <div class="title m-b-md">
                            Teleport
                    </div>

                    <form method="POST" action="/search" autocomplete="off">

                        {{ csrf_field() }}

                        <input type="text" name="searchQuery" id="searchQuery" placeholder="Wpisz miasto">
                        <input type="submit" value="Szukaj">

                    </form>
                    
                    @if(Session::has('message'))
                    <p class='alert alert-info'>{{ Session::get('message') }}</p>
                    @endif
                    
                </div>

                <script>
                    $('#searchQuery').keyup(function() {
                    if($('#searchQuery').val().length >= 3) {
                        var inputValue = $('#searchQuery').val();
                        $.ajax({
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        cache: false,
                        encoding: "UTF-8",
                        url: "<?php echo e(url('teleport')); ?>",
                        beforeSend: function (xhr) {
                        var token = $('meta[name="csrf_token"]').attr('content');
                        if (token) {
                            return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                            } 
                        },
                        data: {input: inputValue},
                        success: function (response) { 
                            document.getElementById("list").innerHTML = "";
                            response.map(function (x) {
                                var li = document.createElement("LI");
                                li.innerHTML = "<a href=''#>"+x+"</a>";                     
                                document.getElementById("list").appendChild(li);
                            })
                        },
                        error: function (response) {
                            $('#errormessage').html(response.message);
                        }
                        });
                        }
                        else {
                            list.innerHTML = "Brak wyników.";
                        }
                    });
                
                </script>

            <div id='list'></div>
            
            </div>
        </div>
    </body>
</html>
