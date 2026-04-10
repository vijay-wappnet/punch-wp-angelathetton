{{--
WooCommerce Single Product - Delivery Section
Reusable partial for displaying delivery information
--}}

@php
  // Get ACF fields
  $title = get_field('product_delivery_title');
  $heading_level = get_field('product_delivery_title_heading_level') ?: 'h2';
  $description = get_field('product_delivery_description');
  $bg_color = get_field('product_delivery_section_bg');
  $margin = get_field('product_delivery_margin');
  $padding = get_field('product_delivery_padding');

  // Generate unique section ID
  $sectionId = 'woo-spds-' . uniqid();

  // Generate responsive CSS for margin and padding
  $responsiveCss = custom_acf_dimensions($margin, $padding, $sectionId);
@endphp

@if($title || $description)
  @if(!empty($responsiveCss))
    <style>{{ $responsiveCss }}</style>
  @endif

  <section
    id="{{ $sectionId }}"
    class="woo-single-product-delivery-section"
    @if($bg_color) style="background-color: {{ esc_attr($bg_color) }};" @endif
  >
    <div class="container">
      <div class="row">
        <div class="col-12 woo-single-product-delivery-section__wrapper">

          {{-- Title --}}
          @if($title)
            <{{ $heading_level }} class="woo-single-product-delivery-section__title">
              {{ $title }}
            </{{ $heading_level }}>
          @endif

          {{-- Description --}}
          @if($description)
            <div class="woo-single-product-delivery-section__description">
              {!! wp_kses_post($description) !!}
            </div>
          @endif

        </div>
      </div>
    </div>
  </section>
@endif
