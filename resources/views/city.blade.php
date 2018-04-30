<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Teleport - city</title>
        
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
                font-size: 10%;
                color: red;
            }
            .arr {
                padding: 10%;
                border-width: 1px;
                border-color: #636b6f;
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
            
            <div class='content'>
                <div class="title m-b-md">
                    Teleport
                </div>
                <div class='arr'>
                    <?php
                        // dd($arr);
                        $name = $arr['_embedded']["city:search-results"][0]['matching_alternate_names'][0]['name'];
                        echo '<b> ',$name,' </b>';
                        echo '<hr style="opacity:0.4">';
                        echo $arr['_embedded']["city:search-results"][0]['matching_full_name'];
                        echo '<hr width="50%" style="opacity:0.4">';
                        echo 'Populacja: ', $arr['population'];
                        echo '<br>';
                        echo 'Szerokość geograficzna: ', $arr['location']['latlon']['latitude'];
                        echo '<br>';
                        echo 'Długość geograficzna: ', $arr['location']['latlon']['longitude'];
                        $latitude = $arr['location']['latlon']['latitude'];
                        $longitude = $arr['location']['latlon']['longitude'];
                    ?>    
                </div>

                <div id="googleMap" style="width:400px;height:250px;"></div>

                <script>
                function myMap() {
                var mapProp= {
                    center:new google.maps.LatLng("<?php echo $latitude ?>", "<?php echo $longitude; ?>"),
                    zoom:5,
                };
                var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
                }
                </script>
                
                <script src="https://maps.googleapis.com/maps/api/js?AIzaSyAsS1XR_yTs0-bzpIHmqI9eIPO0umSVw6g&callback=myMap"></script>
                
                {{--  --}}
                
                <footer>
                    <hr style="opacity:0.4">
                    Julia Zigouras
                    <hr style="opacity:0.4">
                </footer>
                
            </div>   
        </div>
    </body>
</html>
