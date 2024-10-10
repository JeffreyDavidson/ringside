<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700"/>

    @vite('resources/js/app.js')

    @livewireStyles
</head>
<!-- end::Head -->

<body id="kt_body" class="app-blank bgi-size-cover bgi-attachment-fixed bgi-position-center" cz-shortcut-listen="true"
      data-new-gr-c-s-check-loaded="8.911.0" data-gr-ext-installed="">

    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">

        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <!--begin::Aside-->
            <div class="d-flex flex-lg-row-fluid">
                <!--begin::Content-->
                <div class="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100">
                    <!--begin::Image-->
                    <img class="theme-light-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20"
                        src="{{ Vite::image('auth/agency.png') }}" alt="">
                    <img class="theme-dark-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20"
                        src="{{ Vite::image('auth/agency-dark.png') }}" alt="">
                    <!--end::Image-->

                    <!--begin::Title-->
                    <h1 class="text-gray-800 fs-2qx fw-bold text-center mb-7">
                        Fast, Efficient and Productive
                    </h1>
                    <!--end::Title-->

                    <!--begin::Text-->
                    <div class="text-gray-600 fs-base text-center fw-semibold">
                        In this kind of post, <a href="#" class="opacity-75-hover text-primary me-1">the blogger</a>

                        introduces a person they’ve interviewed <br> and provides some background information about

                        <a href="#" class="opacity-75-hover text-primary me-1">the interviewee</a>
                        and their <br> work following this is a transcript of the interview.
                    </div>
                    <!--end::Text-->
                </div>
                <!--end::Content-->
            </div>
            <!--begin::Aside-->

            <!--begin::Body-->
            <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12">
                <!--begin::Wrapper-->
                <div class="bg-body d-flex flex-column flex-center rounded-4 w-md-600px p-10">
                    <!--begin::Content-->
                    <div class="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-400px">
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-center flex-column flex-column-fluid pb-15 pb-lg-20">

                            <!--begin::Form-->
                            <form class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate"
                                id="kt_sign_in_form" method="post" action="{{ route('login') }}">
                                @csrf
                                <!--begin::Heading-->
                                <div class="text-center mb-11">
                                    <!--begin::Title-->
                                    <h1 class="text-gray-900 fw-bolder mb-3">
                                        Sign In
                                    </h1>
                                    <!--end::Title-->
                                </div>
                                <!--begin::Heading-->

                                <!--begin::Input group--->
                                <div class="fv-row mb-8 fv-plugins-icon-container">
                                    <input type="text" placeholder="Email" name="email" autocomplete="off"
                                        value="{{ old('email') }}"
                                        class="form-control bg-transparent">
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                </div>

                                <div class="fv-row mb-3 fv-plugins-icon-container">
                                    <input type="password" placeholder="Password" name="password" autocomplete="off"
                                        class="form-control bg-transparent">
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                </div>

                                <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                                    <div></div>

                                    <a href="/metronic8/demo1/authentication/layouts/overlay/reset-password.html"
                                    class="link-primary">
                                        Forgot Password ?
                                    </a>
                                </div>

                                <div class="d-grid mb-10">
                                    <button type="submit" id="kt_sign_in_submit" class="btn btn-primary mb-4">
                                        <span class="indicator-label">Sign In</span>
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <svg id="SvgjsSvg1001" width="2" height="0" xmlns="http://www.w3.org/2000/svg" version="1.1"
        xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev"
        style="overflow: hidden; top: -100%; left: -100%; position: absolute; opacity: 0;">
        <defs id="SvgjsDefs1002"></defs>
        <polyline id="SvgjsPolyline1003" points="0,0"></polyline>
        <path id="SvgjsPath1004" d="M0 0 "></path>
    </svg>
    @livewireScriptConfig
</body>
</html>
