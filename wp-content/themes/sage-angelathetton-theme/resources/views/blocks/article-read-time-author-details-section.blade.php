{{-- filepath: c:\xampp\htdocs\punch-wp-angelathetton\wp-content\themes\sage-angelathetton-theme\resources\views\blocks\article-read-time-author-details-section.blade.php --}}
@if(!empty($responsiveCss))
<style>{{ $responsiveCss }}</style>
@endif

<div id="{{ $blockId }}"
     class="article-read-time-author-details-section{{ $hideThisBlockInMobile ? ' article-read-time-author-details-section--hide-mobile' : '' }}"
     @if($backgroundStyle) style="{{ $backgroundStyle }}" @endif>
  <div class="container">

    <div class="border-top-initialize"></div>

    <div class="article-read-time-author-details-section__inner">
      <span class="article-read-time-author-details-section__item">
        Read time: {{ esc_html($readTimeMinutes) }} {{ $readTimeMinutes === 1 ? 'min' : 'mins' }}
      </span>

      <span class="article-read-time-author-details-section__separator">|</span>

      <span class="article-read-time-author-details-section__item">
        Article Author: {{ esc_html($authorName ?: 'Author') }}
      </span>
    </div>
  </div>
</div>
