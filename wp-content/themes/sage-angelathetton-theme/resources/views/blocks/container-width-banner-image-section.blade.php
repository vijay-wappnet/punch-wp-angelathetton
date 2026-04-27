@if(!empty($responsiveCss))
  <style>
    {{ $responsiveCss }}
  </style>
@endif
<div id="{{ $blockId }}" class="container-width-banner-image-section" style="background-color: {{ $section_bg }};">
  <div class="container">
    <div class="row">
      <div class="col-12">
        @if ($banner_image)
          <img class="img-fluid container-width-banner-img" src="{{ $banner_image['url'] }}"
            alt="{{ $banner_image['alt'] }}" width="{{ $banner_image['width'] }}" height="{{ $banner_image['height'] }}">
        @endif
      </div>
    </div>
  </div>
</div>