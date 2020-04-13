<x-layouts.app>
    <x-subheader
        title="{{ $wrestler->name }}"
        :link="route('wrestlers.index')"
    />
    <x-content>
        <div class="row">
            <div class="col-lg-3">
                <x-portlet>
                    <div class="kt-widget kt-widget--user-profile-1">
                        <div class="kt-widget__head">
                            <div class="kt-widget__media">
                                <img src="https://via.placeholder.com/100" alt="image">
                            </div>
                            <div class="kt-widget__content">
                                <div class="kt-widget__section">
                                    <span class="kt-widget__username">{{ $wrestler->name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="kt-widget__body">
                            <div class="kt-widget__content">
                                <div class="kt-widget__info">
                                    <span class="kt-widget__label">Height:</span>
                                    <span class="kt-widget__data">{{ $wrestler->formatted_height }}</span>
                                </div>
                                <div class="kt-widget__info">
                                    <span class="kt-widget__label">Weight:</span>
                                    <span class="kt-widget__data">{{ $wrestler->weight }} lbs.</span>
                                </div>
                                <div class="kt-widget__info">
                                    <span class="kt-widget__label">Hometown:</span>
                                    <span class="kt-widget__data">{{ $wrestler->hometown }}</span>
                                </div>
                                <div class="kt-widget__info">
                                    <span class="kt-widget__label">Signature Move:</span>
                                    <span class="kt-widget__data">{{ $wrestler->signature_move }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-portlet>
            </div>
        </div>
    </x-content>
</x-layouts.app>
