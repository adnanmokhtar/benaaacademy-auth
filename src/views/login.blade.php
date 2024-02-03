@extends("auth::layouts.auth")

@section("content")

    <p>{{ trans("auth::auth.sign_in_to_account") }}</p>

    <form class="m-t" role="form" action="{{ route("admin.auth.login") }}" id="signin-form_id" method="post">

        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

        @if(request()->filled("url"))
            <input type="hidden" name="url" value="{{ request()->get("url") }}"/>
        @elseif(session()->has("url"))
            <input type="hidden" name="url" value="{{ session()->get("url") }}"/>
        @else
            <input type="hidden" name="url" value="{{ request()->old("url") }}"/>
        @endif

        @include("admin::partials.messages")

        <div class="form-group" @if($errors->first("username")) has-error @endif>
            <input type="text" name="username" value="{{ Request::old("username") }}" class="form-control"
                   placeholder="{{ trans("auth::auth.username") }}" required="required">
            <span class="help-block">{{ $errors->first("username") }}</span>
        </div>
        <div class="form-group" @if ($errors->first("password")) has-error @endif>
            <input type="password" name="password" class="form-control"
                   placeholder="{{ trans("auth::auth.password") }}" required="required">
            <span class="help-block">{{ $errors->first("password") }}</span>
        </div>

        <div class="form-group">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="remember" value="1">
                    <span class="remember_text">{{ trans("auth::auth.remember_me") }}</span>
                </label>
            </div>
        </div>

        <button type="submit"
                class="btn btn-primary block full-width m-b">{{ trans("auth::auth.login_in") }}</button>
        <a class="text-navy" href="{{ route("admin.auth.forget") }}">
            <small>{{ trans("auth::auth.forget_password") }}</small>
        </a>
    </form>

@stop

@section("footer")

    <script src="<?php echo assets("admin::js/plugins/switchery/switchery.js") ?>"></script>

    <script>

        var elems = Array.prototype.slice.call(document.querySelectorAll('input[type=checkbox]'));
        elems.forEach(function (html) {
            var switchery = new Switchery(html);
        });

    </script>

@stop
