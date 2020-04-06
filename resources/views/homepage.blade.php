<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ str_replace('-', ' ', config('app.name')) }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
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

            .links a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            .links .btn {
                border-radius: 3px;
                font-size: inherit;
                border: 1px solid #6c757d;
                padding: 0.375rem 0.75rem;
            }
            .m-t {
                margin-top: 15px;
            }
            .footer {
                background: none repeat scroll 0 0 white;
                border-top: 1px solid #e7eaec;
                bottom: 0;
                left: 0;
                padding: 10px 20px;
                position: absolute;
                right: 0;
            }
        </style>
    </head>
    <body>
        <div class="homepage">
            <div class="position-ref" style="padding-bottom: 80px">
                <div style="background-color: #2f4050; width: 100%; height: 58px; margin-bottom: 50px; color: #fff;">
                    <div class="top-left" style="position: absolute; left: 35px; top: 14px;">
                        <a href="/" style="color: #fff; text-decoration: none;"> <h2 style="margin: 0">{{ str_replace('-', ' ', config('app.name')) }}</h2></a>
                    </div>
                    @if (Route::has('login'))
                        <div class="top-right links">
                            @auth
                                <a href="{{ url('/admin') }}">Admin</a>
                            @else
                                <a href="{{ route('login') }}">Login</a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}">Register</a>
                                @endif
                            @endauth
                        </div>
                    @endif

                </div>
            </div>
            <div class="flex-center position-re xfull-height" style="height: calc(100vh - 400px);">

                <div class="content">
                    <div class="title m-b-md">
                        {{ str_replace('-', ' ', config('app.name')) }}
                    </div>

                    <div class="links">
                        <a href="{{ config('app.url') }}/blog" class="btn btn-primary m-t">See Blog Post</a>
                    </div>
                </div>
            </div>
            <div class="footer" style="position: fixed">
                <div class="container">
                    <div class=" float-right">

                    </div>
                    <div>
                        <strong>Copyright</strong> {{ str_replace('-', ' ', config('app.name')) }} © 2014-2018
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
