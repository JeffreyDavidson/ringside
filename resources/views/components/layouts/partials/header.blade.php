<header
    {{ $attributes->merge(['class' => 'fixed top-0 z-10 left-0 right-0 flex items-stretch shrink-0 bg-[--tw-page-bg] dark:bg-coal-500 lg:left-70 h-18']) }}>
    <!-- Container -->
    <x-container-fixed class="lg:gap-4">
        <!-- Mega Menu -->
        <x-mega-menu />
        <!-- End of Mega Menu -->
        <!-- Topbar -->
        <x-topbar />
        <!-- End of Topbar -->
    </x-container-fixed>
    <!-- End of Container -->
</header>
