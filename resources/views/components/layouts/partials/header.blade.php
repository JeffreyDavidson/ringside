<header
    {{ $attributes->merge(['class' => 'fixed left-0 right-0 top-0 z-10  bg-[--tw-page-bg] h-18 dark:bg-coal-500 lg:left-70']) }}>
    <!-- Container -->
    <div class="flex items-stretch justify-between py-4 grow w-full px-6 xl:mx-auto lg:gap-4 xl:px-7.5 xl:max-w-screen-xl">
        <!-- Mega Menu -->
        <x-mega-menu />
        <!-- End of Mega Menu -->
        <!-- Topbar -->
        <x-topbar />
        <!-- End of Topbar -->
    </div>
    <!-- End of Container -->
</header>
