@if(!empty($responsiveCss))
<style>{{ $responsiveCss }}</style>
@endif
<div id="{{ $blockId }}" class="container-width-two-column-image-section" style="background-color: {{ $section_bg }};">
  <div class="container">
    <div class="row">
      @if ($images)
        @foreach ($images as $item)
          @if ($item['image'])
            @php
              $hideMobileClass = '';
              if ($loop->first && $hideFirstImageInMobile) {
                $hideMobileClass = 'hide-mobile';
              } elseif ($loop->last && $hideSecondImageInMobile) {
                $hideMobileClass = 'hide-mobile';
              }
            @endphp
            <div class="col-12 col-md-6 col-img-wrapper {{ $hideMobileClass }}">
              <img class="img-fluid two-column-img" src="{{ $item['image']['url'] }}" alt="{{ $item['image']['alt'] }}">
            </div>
          @endif
        @endforeach
      @endif
    </div>
  </div>
</div>
