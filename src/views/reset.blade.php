@extends("auth::layouts.auth")

@section("content")

    <p>{{ trans("auth::auth.password_reset") }}</p>

    @if($reseted)

        <div class="alert alert-success">{{ trans("auth::auth.password_changed") }}</div>
        <div class="form-actions">
            <a href="{{ route("admin.auth.login") }}" class="signin-btn">{{ trans("auth::auth.back_to_login") }}</a>
        </div>

    @elseif

        <form class="m-t" role="form" action="{{ route("admin.auth.reset", ["code" => $code]) }}" id="signin-form_id"
              method="post">

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <input type="hidden" name="code" value="{{ $code }}"/>

            @if ($errors->first("not_registed"))
                <div class="alert alert-danger">{{ $errors->first("not_registed") }}</div>
            @endif

            @if($errors->first("email_sent"))
                <div class="alert alert-success">{{ $errors->first("email_sent") }}</div>
            @endif

            <div class="form-group" @if($errors->first("password")) has-error @endif>
                <input type="password" name="password" class="form-control"
                       placeholder="{{ trans("auth::auth.password") }}" required="required">
                <span class="help-block">{{ $errors->first("password") }}</span>
            </div>

            <div class="form-group" @if($errors->first("repassword")) has-error @endif>
                <input type="password" name="repassword" class="form-control"
                       placeholder="{{ trans("auth::auth.confirm_password") }}" required="required">
                <span class="help-block">{{ $errors->first("repassword") }}</span>
            </div>

            <button type="submit"
                    class="btn btn-primary block full-width m-b">{{ trans("auth::auth.reset_my_password") }}
            </button>

            <a class="text-navy" href="{{ route("admin.auth.login") }}">
                <small>{{ trans("auth::auth.login_now") }}</small>
            </a>
        </form>

    @endif

@stop
