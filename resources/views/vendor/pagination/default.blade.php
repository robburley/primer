@if ($paginator->hasPages())
    <ul class="c-pagination__list">
        @if ($paginator->onFirstPage())
            <li class="c-pagination__item">
                <a class="c-pagination__control" href="#">
                    <i class="fa fa-caret-left"></i>
                </a>
            </li>
        @else
            <li class="c-pagination__item">
                <a class="c-pagination__control" href="{{ $paginator->previousPageUrl() }}">
                    <i class="fa fa-caret-left"></i>
                </a>
            </li>
        @endif

        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            {{--@if (is_string($element))--}}
            {{--<li class="disabled"><span>{{ $element }}</span></li>--}}
            {{--@endif--}}

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())

                        <li class="c-pagination__item">
                            <a class="c-pagination__link is-active" href="#">
                                {{ $page }}
                            </a>
                        </li>
                    @else
                        <li class="c-pagination__item">
                            <a class="c-pagination__link" href="{{ $url }}">
                                {{ $page }}
                            </a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach


        @if ($paginator->hasMorePages())
            <li class="c-pagination__item">
                <a class="c-pagination__control" href="{{ $paginator->nextPageUrl() }}">
                    <i class="fa fa-caret-right"></i>
                </a>
            </li>
        @else
            <li class="c-pagination__item">
                <a class="c-pagination__control" href="#">
                    <i class="fa fa-caret-right"></i>
                </a>
            </li>
        @endif
    </ul>
@endif
