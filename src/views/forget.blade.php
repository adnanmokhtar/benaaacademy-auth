@extends("auth::layouts.auth")

@section("content")

    <p>{{ trans("auth::auth.password_reset") }}</p>

    <form class="m-t" role="form" action="{{ route("admin.auth.forget") }}" id="signin-form_id" method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        @if ($errors->first("not_registed"))
            <div class="alert alert-danger">{{ $errors->first("not_registed") }}</div>
        @endif

        @if($errors->first("email_sent"))
            <div class="alert alert-success">{{ $errors->first("email_sent") }}</div>
        @endif

        <div class="form-group" @if ($errors->first("email")) has-error @endif>
            <input type="text" name="email" value="{{ request()->old("email") }}" class="form-control"
                   placeholder="{{ trans("auth::auth.email") }}" required="required">
            <span class="help-block">{{ $errors->first("email") }}</span>
        </div>

        <button type="submit"
                class="btn btn-primary block full-width m-b">{{ trans("auth::auth.send_reset_link") }}</button>

        <a class="text-navy" href="{{ route("admin.auth.login") }}">
            <small>{{ trans("auth::auth.back_to_login") }}</small>
        </a>

    </form>

@stop
