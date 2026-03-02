<div class="full-width-banner-image-section" style="background-color: {{ $section_bg }};">
    @if ($is_preview)
        <div class="acf-block-preview">
            @if ($banner_image)
                <img class="full-width-banner-image-section__image" src="{{ $banner_image['url'] }}" alt="{{ $banner_image['alt'] }}">
            @else
                <div class="full-width-banner-image-section__placeholder">Preview: No image selected</div>
            @endif
        </div>
    @else
        @if ($banner_image)
            <img class="full-width-banner-image-section__image" src="{{ $banner_image['url'] }}" alt="{{ $banner_image['alt'] }}">
        @else
            <div class="full-width-banner-image-section__placeholder"></div>
        @endif
    @endif
</div>