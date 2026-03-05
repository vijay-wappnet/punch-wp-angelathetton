@if(!empty($responsiveCss))
<style>{{ $responsiveCss }}</style>
@endif
<div id="{{ $blockId }}" class="container-width-banner-image-section" style="background-color: {{ $section_bg }};">
  <div class="container">
    <div class="row">
      <div class="col-12">
        @if ($banner_image)
          <img class="img-fluid container-width-banner-img" src="{{ $banner_image['url'] }}" alt="{{ $banner_image['alt'] }}">
        @endif
      </div>
    </div>
  </div>
</div>
