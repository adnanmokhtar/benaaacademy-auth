<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ option("site_name") }} - {{ trans("auth::auth.cms") }}</title>
    <link rel="icon" type="image/png" href="{{ assets("admin::favicon.png") }}"/>
    <link href="{{ assets("admin::css/auth.css") }}" rel="stylesheet"/>

    @if (defined("DIRECTION") && DIRECTION == "rtl")
        <link href="{{ assets("admin::css/plugins/bootstrap-rtl/bootstrap-rtl.min.css") }}" rel="stylesheet"/>
    @endif

    @yield("head")
    @stack("head")
</head>
<body class="dark-theme gray-bg rtls">
<div class="middle-box text-center loginscreen animated fadeInDown">
    <div>

        <div>
            <h1 class="logo-name">{{ option("site_name") }}</h1>
        </div>

        <h3>{{ option("site_slogan") }}</h3>

        @yield("content")

        <p class="m-t">
            <small> {{ option("site_copyrights") }} </small>
        </p>

    </div>
</div>

<script src="{{ assets("admin::js/auth.js") }}"></script>

@yield("footer")
@stack("footer")
</body>
</html>
