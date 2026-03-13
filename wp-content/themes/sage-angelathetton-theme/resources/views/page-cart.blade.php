{{--
  Template Name: Cart Page
--}}

@extends('layouts.app')

@section('content')

  @php
    do_action('get_header', 'shop');
  @endphp

  <div class="container cart-page-container">

    @php
      do_action('woocommerce_before_main_content');
    @endphp

    @php
      echo do_shortcode('[woocommerce_cart]');
    @endphp

    @php
      do_action('woocommerce_after_main_content');
    @endphp

  </div>

  @php
    do_action('get_footer', 'shop');
  @endphp

@endsection
