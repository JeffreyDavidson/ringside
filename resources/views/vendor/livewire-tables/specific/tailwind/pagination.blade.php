@if ($paginator->hasPages())
    @php(isset($this->numberOfPaginatorsRendered[$paginator->getPageName()]) ? $this->numberOfPaginatorsRendered[$paginator->getPageName()]++ : ($this->numberOfPaginatorsRendered[$paginator->getPageName()] = 1))

    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <button
            class="inline-flex items-center cursor-pointer leading-[0] rounded-md size-7.5 ps-px pe-px gap-1.5 border border-solid border-transparent font-medium text-2sm shrink-0 justify-center p-0 opacity-100 pointer-events-none text-gray-500"
            aria-hidden="true">
            <i class="ki-outline ki-black-left text-base text-gray-400"></i>
        </button>
    @else
        <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')"
            dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
            rel="prev"
            class="inline-flex items-center cursor-pointer leading-[0] rounded-md size-7.5 ps-px pe-px gap-1.5 border border-solid border-transparent font-medium text-2sm shrink-0 justify-center p-0 hover:bg-gray-200 hover:text-gray-800"
            aria-label="{{ __('pagination.previous') }}">
            <i class="ki-outline ki-black-left text-base text-gray-700"></i>
        </button>
    @endif

    {{-- Pagination Elements --}}
    @if ($elements ?? null)
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <button
                    class="inline-flex items-center cursor-pointer leading-[0] rounded-md size-7.5 ps-px pe-px gap-1.5 border border-solid border-transparent font-medium text-2sm shrink-0 justify-center p-0">{{ $element }}</button>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    <button
                        wire:key="paginator-{{ $paginator->getPageName() }}-{{ $this->numberOfPaginatorsRendered[$paginator->getPageName()] }}-page{{ $page }}">
                        @if ($page == $paginator->currentPage())
                            <button
                                class="inline-flex items-center cursor-pointer leading-[0] rounded-md size-7.5 ps-px pe-px gap-1.5 border border-solid border-transparent font-medium text-2sm shrink-0 justify-center p-0 opacity-100 pointer-events-none text-gray-500 bg-gray-200">{{ $page }}</button>
                        @else
                            <button type="button"
                                wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                class="inline-flex items-center cursor-pointer leading-[0] rounded-md size-7.5 ps-px pe-px gap-1.5 border border-solid border-transparent font-medium text-2sm shrink-0 justify-center p-0 hover:bg-gray-200 hover:text-gray-800"
                                aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                {{ $page }}
                            </button>
                        @endif
                    </button>
                @endforeach
            @endif
        @endforeach
    @endif

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')"
            dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
            rel="next"
            class="inline-flex items-center cursor-pointer leading-[0] rounded-md size-7.5 ps-px pe-px gap-1.5 border border-solid border-transparent font-medium text-2sm shrink-0 justify-center p-0 hover:bg-gray-200 hover:text-gray-800"
            aria-label="{{ __('pagination.next') }}">
            <i class="ki-outline ki-black-right text-base text-gray-700"></i>
        </button>
    @else
        <button
            class="inline-flex items-center cursor-pointer leading-[0] rounded-md size-7.5 ps-px pe-px gap-1.5 border border-solid border-transparent font-medium text-2sm shrink-0 justify-center p-0"
            aria-hidden="true">
            <i class="ki-outline ki-black-right text-base text-gray-400"></i>
        </button>
    @endif
@endif
