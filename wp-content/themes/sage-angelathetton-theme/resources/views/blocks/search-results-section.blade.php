@if (!empty($responsiveCss))
    <style>
        {{ $responsiveCss }}
    </style>
@endif
<section id="{{ $blockId }}" class="search-results-section"
    @if (!empty($backgroundStyle)) style="{{ $backgroundStyle }}" @endif>
    <div class="container">
        <div class="row">
            <div class="col-12">
                @if (!empty($mainSearchTitle))
                    <{{ $headingLevel }} class="search-results-title">{!! $mainSearchTitle !!}</{{ $headingLevel }}>
                @endif
                <form class="search-results-form" method="GET"
                    action="@php echo esc_url(home_url('/search-results/')); @endphp">
                    <div class="search-icon-holder">
                        <input type="text" name="search-field" class="form-control"
                            placeholder="{{ $searchPlaceholder }}" value="{{ $searchTerm ?? '' }}">
                    </div>
                    <button type="submit" class="btn srs-btn {{ $searchButton['button_class'] ?? '' }}"
                        aria-label="{{ $searchButton['aria_label'] ?? '' }}"
                        data-event-label="{{ $searchButton['button_google_event_label'] ?? '' }}">
                        {{ $searchButton['button_title'] ?? 'Search' }}
                    </button>
                </form>
                @if (!empty($searchTerm))
                    <div class="search-results-meta mb-4 mt-3">
                        <div>
                            {{ __('Your search for', 'sage') }} <strong>‘{{ $searchTerm }}’</strong>
                            {{ __('has returned', 'sage') }} <strong>{{ $totalResults }}</strong>
                            {{ __('results', 'sage') }}:
                        </div>
                    </div>
                @endif


                <div class="search-results-list">
                    @if (!empty($results))
                        @foreach ($results as $result)
                            <div class="search-result-item">
                                <div class="result-title">
                                    {{-- <a href="{{ $result['link'] }}"></a> --}}
                                    {!! $result['title'] !!}
                                </div>
                                <a href="{{ $result['link'] }}"
                                    class="btn find-out-more-btn trans-black-btn">{{ __('Find out more', 'sage') }}</a>
                                <hr>
                            </div>
                        @endforeach
                    @else
                        <div class="no-posts-message">
                            <p>{{ __('No results found.', 'sage') }}</p>
                        </div>
                    @endif
                </div>
                @if ($pagination)
                    <div class="pagination-row">
                        <div class="pagination">
                            {!! $pagination !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
