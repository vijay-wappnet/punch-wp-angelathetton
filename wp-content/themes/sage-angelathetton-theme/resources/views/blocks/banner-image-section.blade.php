@php
    $wrapper_style = $section_bg ? "background-color: " . esc_attr($section_bg) . ";" : '';
    $has_image = !empty($banner_image) && !empty($banner_image['url']);
    $image_url = $has_image ? $banner_image['url'] : '';
    $image_alt = $has_image ? ($banner_image['alt'] ?? '') : '';
@endphp

@if(!empty($responsiveCss))
<style>{{ $responsiveCss }}</style>
@endif
<section id="{{ $blockId }}" class="banner-image-section{{ $is_preview ? ' is-preview' : '' }}"@if($wrapper_style) style="{{ $wrapper_style }}"@endif role="img" @if($image_alt)aria-label="{{ esc_attr($image_alt) }}"@endif>
    @if($has_image)
        <div class="banner-image-section__wrapper" style="background-image: url('{{ esc_url($image_url) }}');"></div>
    @endif
</section>
