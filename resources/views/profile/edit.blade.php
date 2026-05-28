@extends('layouts.app')
@section('title', 'Cuenta')

@section('content')
<div class="page-shell max-w-5xl">
    <div>
        <div class="page-kicker">Cuenta</div>
        <h1 class="page-title">Configuracion de tu perfil.</h1>
        <p class="page-subtitle">Actualiza tus datos de acceso y la informacion asociada a tu workspace.</p>
    </div>

    <div class="grid gap-5">
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            @include('profile.partials.update-profile-information-form')
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            @include('profile.partials.update-password-form')
        </section>

        <section class="rounded-xl border border-red-200 bg-white p-6 shadow-sm">
            @include('profile.partials.delete-user-form')
        </section>
    </div>
</div>
@endsection
