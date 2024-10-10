<x-layouts.auth>
    <form action="{{ route('login') }}" class="grow flex flex-col gap-5 p-10" method="post">
        @csrf
        <div class="text-center mb-2.5">
            <h3 class="text-lg font-medium text-gray-900 leading-none mb-2.5">
                {{ __('auth.signin') }}
            </h3>
        </div>
        <div class="flex flex-col gap-1">
            <x-form.input-label label="{{ __('user.email.label') }}" />
            <x-form.inputs.text placeholder="{{ __('user.email.placeholder') }}" name="email" />
            <x-form.validation-error field="email" />
        </div>
        <div class="flex flex-col gap-1">
            <x-form.input-label label="{{ __('user.password.label') }}" />
            <x-form.inputs.password />
            <x-form.validation-error field="password" />
        </div>
        <button type="submit" class="btn btn-primary flex justify-center grow">
            {{ __('auth.signin') }}
        </button>
    </form>
</x-layouts.auth>
