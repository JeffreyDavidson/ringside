<header
    {{ $attributes->merge(['class' => 'header fixed top-0 z-10 left-0 right-0 flex items-stretch shrink-0 bg-[--tw-page-bg] dark:bg-coal-200']) }}>
    <!-- Container -->
    <x-container-fixed class="flex justify-end lg:gap-4">
        <!-- Topbar -->
        <x-topbar />
        <!-- End of Topbar -->
    </x-container-fixed>
    <!-- End of Container -->
</header>
