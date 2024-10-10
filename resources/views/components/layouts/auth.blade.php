<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" />
    @vite('resources/js/app.js')

    @livewireStyles
</head>
<!-- end::Head -->
<body class="antialiased flex h-full text-base text-gray-700 dark:bg-coal-500">
    <div class="flex items-center justify-center grow bg-center bg-no-repeat bg-[url('images/2600x1200/bg-10.png')] dark:bg-[url('images/2600x1200/bg-10-dark.png')]">
        <div class="rounded-xl bg-[--tw-card-background-color] border border-solid border-[--tw-card-border] shadow shadow-[--tw-card-box-shadow] flex flex-col max-w-[370px] w-full">
            <form action="{{ route('login') }}" class="grow flex flex-col gap-5 p-10" method="post">
                @csrf
                <div class="text-center mb-2.5">
                    <h3 class="text-lg font-medium text-gray-900 leading-none mb-2.5">
                        {{ __('auth.signin') }}
                    </h3>
                </div>
                <div class="flex flex-col gap-1">
                    <x-form.input-label label="{{ __('user.email.label') }}"/>
                    <x-form.inputs.text placeholder="{{ __('user.email.placeholder') }}" name="email"/>
                    <x-form.validation-error field="email"/>
                </div>
                <div class="flex flex-col gap-1">
                    <x-form.input-label label="{{ __('user.password.label') }}"/>
                    <x-form.inputs.password/>
                    <x-form.validation-error field="password"/>
                </div>
                <button type="submit" class="btn btn-primary flex justify-center grow">
                    {{ __('auth.signin') }}
                </button>
            </form>
        </div>
   </div>
   <!-- End of Page -->
</body>
</html>
