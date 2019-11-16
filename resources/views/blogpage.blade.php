<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta name="cl-code" content="{{ $user['api_token'] }}">
        <meta name="base_url" content="{{ $base_url }}">
        <meta name="has_user" content="{{ ($user) ? true : false }}">
        <meta name="app_name" content="{{ config('app.name') }}">
        <meta name="frontpage" content="true">

        <title>Blog Post</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

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
        </style>
    </head>
    <body>
        <div id="blog" class="blog-post">
            <div class="position-ref" style="padding-bottom: 80px">
                <div style="background-color: #2f4050; width: 100%; height: 58px; margin-bottom: 50px; color: #fff;">
                    <div class="top-left" style="position: absolute; left: 35px; top: 14px;">
                        <a href="/" style="color: #fff; text-decoration: none;"> <h2 style="margin: 0">{{ config('app.name') }}</h2></a>
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
                @if($user || $hasPost)
                <div class="container">
                    <router-view></router-view>
                </div
                </div>
                @endif
            </div>
            @if(!$user && !$hasPost)
            <div class="flex-center position-ref xfull-height" style="height: calc(100vh - 400px">
                <div class="content">
                    <div class="title m-b-md">
                        {{ config('app.name') }}
                    </div>

                    <div class="middle-box text-center" style="padding-top: 0;">
                        <h3 class="font-bold">Sorry, no article to display.</h3>
                        <div class="error-desc">
                            @if($user)
                            Check out our dashboard and start your new blog by adding new post in admin blog page. Enjoy our new app.
                            @else
                            we're still creating a new article, please stick around for the news. for the meantime see our homepage.
                            @endif
                        </div>
                    </div>

                    <div class="">
                        @if($user)
                        <a href="{{ config('app.url') }}/admin/blog" class="btn btn-primary m-t">Post Listing</a>
                        @else
                        <a href="{{ config('app.url') }}/" class="btn btn-primary m-t">Home</a>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            <div class="footer" style="position: fixed">
                <div class="container">
                    <div class=" float-right">

                    </div>
                    <div>
                        <strong>Copyright</strong> Wavo Blog © 2014-2018
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="{{ asset('js/blog.js') }}" defer></script>
    </body>
</html>
