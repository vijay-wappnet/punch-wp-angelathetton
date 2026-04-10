@if(!empty($responsiveCss))
<style>{{ $responsiveCss }}</style>
@endif
<div id="{{ $blockId }}" class="full-width-banner-image-section" style="background-color: {{ $section_bg }};">
  @if ($banner_image)
    <img class="full-width-banner-img" src="{{ $banner_image['url'] }}" alt="{{ $banner_image['alt'] }}">
  @endif
</div>
