
<div class="pagination">
    @if ($paginator->hasPages())
        @php(isset($this->numberOfPaginatorsRendered[$paginator->getPageName()]) ? $this->numberOfPaginatorsRendered[$paginator->getPageName()]++ : $this->numberOfPaginatorsRendered[$paginator->getPageName()] = 1)

        <div class="flex justify-between flex-1 md:hidden">
            @if ($paginator->onFirstPage())
                <button class="btn">
                    {!! __('pagination.previous') !!}
                </button>
            @else
                <button class="btn" type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before">
                    {!! __('pagination.previous') !!}
                </button>
            @endif

            @if ($paginator->hasMorePages())
                <button class="btn disabled" type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before">
                    {!! __('pagination.next') !!}
                </button>
            @else
                <button class="btn">
                    {!! __('pagination.next') !!}
                </button>
            @endif
        </div>

        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <button class="btn disabled" aria-hidden="true">
                <i class="ki-outline ki-black-left"></i>
            </button>
        @else
            <button class="btn" type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after" rel="prev" aria-label="{{ __('pagination.previous') }}">
                <i class="ki-outline ki-black-left"></i>
            </button>
        @endif

        {{-- Pagination Elements --}}
        @if ($elements ?? null)
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <button class="btn">
                        {{ $element }}
                    </button>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <button class="btn active disabled" wire:key="paginator-{{ $paginator->getPageName() }}-{{ $this->numberOfPaginatorsRendered[$paginator->getPageName()] }}-page{{ $page }}">{{ $page }}</button>
                        @else
                            <button type="button" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" class="btn" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <button class="btn" type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after" rel="next" aria-label="{{ __('pagination.next') }}">
                <i class="ki-outline ki-black-right"></i>
            </button>
        @else
            <button class="btn disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                <i class="ki-outline ki-black-right"></i>
            </button>
        @endif
    @endif
</div>
