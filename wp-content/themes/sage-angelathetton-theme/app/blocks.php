<?php

/**
 * Register custom Gutenberg blocks.
 */

namespace App;

use Illuminate\Support\Facades\Vite;

/**
 * Register custom blocks
 */
add_action('init', function () {


    if (function_exists('acf_register_block_type')) {

        // Register the Introtext Section Block
        acf_register_block_type([
            'name'            => 'introtext-section',
            'title'           => __('Intro Section', 'sage'),
            'description'     => __('A section with heading, content, background, and buttons', 'sage'),
            'render_callback' => ['App\Blocks\IntrotextSection', 'render'],
            'category'        => 'common',
            'icon'            => 'text',
            'mode'            => 'edit',
            'align'           => 'full',
            'supports'        => [
                'align'           => false,
                'mode'            => true,
                'jsx'             => true,
                'align_text'      => false,
            ],
        ]);

        //Register the Video Banner Section Block
        acf_register_block_type([
            'name'            => 'video-banner-section',
            'title'           => __('Video Banner Section', 'sage'),
            'description'     => __('A fullscreen video banner section with heading and scroll arrow', 'sage'),
            'render_callback' => ['App\Blocks\VideoBannerSection', 'render'],
            'category'        => 'common',
            'icon'            => 'format-video',
            'mode'            => 'preview',
            'align'           => 'full',
            'supports'        => [
                'align'           => false,
                'mode'            => true,
                'jsx'             => true,
                'align_text'      => false,
            ],
        ]);

        // Register the Two Columns Image With CTA Section Block
        acf_register_block_type([
            'name'            => 'two-columns-image-with-cta-section',
            'title'           => __('Two Columns Image With CTA Section', 'sage'),
            'description'     => __('A two column section with images, content, and call-to-action buttons', 'sage'),
            'render_callback' => ['App\Blocks\TwoColumnsImageWithCtaSection', 'render'],
            'category'        => 'common',
            'icon'            => 'columns',
            'mode'            => 'preview',
            'align'           => 'full',
            'supports'        => [
                'align'           => false,
                'mode'            => true,
                'jsx'             => true,
                'align_text'      => false,
            ],
        ]);
        // Register the Slider With Multiple Box Section Block
        acf_register_block_type([
            'name'            => 'slider-with-multiple-box-section',
            'title'           => __('Slider With Multiple Box Section', 'sage'),
            'description'     => __('A slider section with multiple boxes', 'sage'),
            'render_callback' => ['App\Blocks\SliderWithMultipleBoxSection', 'render'],
            'category'        => 'common',
            'icon'            => 'columns',
            'mode'            => 'preview',
            'align'           => 'full',
            'supports'        => [
                'align'           => false,
                'mode'            => true,
                'jsx'             => true,
                'align_text'      => false,
            ],
        ]);

        // Register the Full Width Banner Image Section Block
        acf_register_block_type([
            'name'            => 'full-width-banner-image-section',
            'title'           => __('Full Width Banner Image Section', 'sage'),
            'description'     => __('A full-width banner image section with customizable background color', 'sage'),
            'render_callback' => ['App\Blocks\FullWidthBannerImageSection', 'render'],
            'category'        => 'common',
            'icon'            => 'format-image',
            'mode'            => 'preview',
            'align'           => 'full',
            'supports'        => [
                'align'           => false,
                'mode'            => true,
                'jsx'             => true,
                'align_text'      => false,
            ],
        ]);

        // Register the Banner Image Section Block
        acf_register_block_type([
            'name'            => 'banner-image-section',
            'title'           => __('Banner Image Section Parallax', 'sage'),
            'description'     => __('A full-width banner image section with parallax effect', 'sage'),
            'render_callback' => ['App\Blocks\BannerImageSection', 'render'],
            'category'        => 'common',
            'icon'            => 'format-image',
            'mode'            => 'preview',
            'align'           => 'full',
            'supports'        => [
                'align'           => false,
                'mode'            => true,
                'jsx'             => true,
                'align_text'      => false,
            ],
        ]);

        // Register the Left Right Media Content Section Block
        acf_register_block_type([
            'name'            => 'left-right-media-content-section',
            'title'           => __('Left Right Media Content Section', 'sage'),
            'description'     => __('A Left and Right section with media and content, supporting layout toggle', 'sage'),
            'render_callback' => ['App\Blocks\LeftRightMediaContentSection', 'render'],
            'category'        => 'common',
            'icon'            => 'columns',
            'mode'            => 'preview',
            'align'           => 'full',
            'supports'        => [
                'align'           => false,
                'mode'            => true,
                'jsx'             => true,
                'align_text'      => false,
            ],
        ]);

        // Register the Two Columns Image CTA With Mobile Slider Section Block
        acf_register_block_type([
            'name'            => 'two-columns-image-cta-with-mobile-slider-section',
            'title'           => __('Two Columns Image CTA With Mobile Slider Section', 'sage'),
            'description'     => __('A two column section with images, content, and call-to-action buttons, optimized for mobile slider', 'sage'),
            'render_callback' => ['App\Blocks\TwoColumnsImageCtaWithMobileSliderSection', 'render'],
            'category'        => 'common',
            'icon'            => 'columns',
            'mode'            => 'preview',
            'align'           => 'full',
            'supports'        => [
                'align'           => false,
                'mode'            => true,
                'jsx'             => true,
                'align_text'      => false,
            ],
        ]);

        // Register the Top Bottom Media Content Section Block
        acf_register_block_type([
            'name'            => 'top-bottom-media-content-section',
            'title'           => __('Top Bottom Media Content Section', 'sage'),
            'description'     => __('A top and bottom section with media and content, supporting layout toggle', 'sage'),
            'render_callback' => ['App\Blocks\TopBottomMediaContentSection', 'render'],
            'category'        => 'common',
            'icon'            => 'columns',
            'mode'            => 'preview',
            'align'           => 'full',
            'supports'        => [
                'align'           => false,
                'mode'            => true,
                'jsx'             => true,
                'align_text'      => false,
            ],
        ]);

        // Register the Menu Items Section Block
        acf_register_block_type([
            'name'            => 'menu-items-section',
            'title'           => __('Menu Items Section', 'sage'),
            'description'     => __('A section displaying menu items with the content and buttons, supporting layout', 'sage'),
            'render_callback' => ['App\Blocks\MenuItemsSection', 'render'],
            'category'        => 'common',
            'icon'            => 'columns',
            'mode'            => 'preview',
            'align'           => 'full',
            'supports'        => [
                'align'           => false,
                'mode'            => true,
                'jsx'             => true,
                'align_text'      => false,
            ],
        ]);

        // Register the Slider Room Features Section Block
        acf_register_block_type([
            'name'            => 'slider-room-features-section',
            'title'           => __('Slider Room Features Section', 'sage'),
            'description'     => __('A section displaying Slider images with the title, content and buttons, supporting layout', 'sage'),
            'render_callback' => ['App\Blocks\SliderRoomFeaturesSection', 'render'],
            'category'        => 'common',
            'icon'            => 'columns',
            'mode'            => 'preview',
            'align'           => 'full',
            'supports'        => [
                'align'           => false,
                'mode'            => true,
                'jsx'             => true,
                'align_text'      => false,
            ],
        ]);

        // Register the Container Width Banner Image Section Block
        acf_register_block_type([
            'name'            => 'container-width-banner-image-section',
            'title'           => __('Container Width Banner Image Section', 'sage'),
            'description'     => __('A container width banner image section with customizable background color', 'sage'),
            'render_callback' => ['App\Blocks\ContainerWidthBannerImageSection', 'render'],
            'category'        => 'common',
            'icon'            => 'format-image',
            'mode'            => 'preview',
            'align'           => 'full',
            'supports'        => [
                'align'           => false,
                'mode'            => true,
                'jsx'             => true,
                'align_text'      => false,
            ],
        ]);

        // Register the Container Width Two Column Image Section Block
        acf_register_block_type([
            'name'            => 'container-width-two-column-image-section',
            'title'           => __('Container Width Two Column Image Section', 'sage'),
            'description'     => __('A container width two column image section with customizable background color', 'sage'),
            'render_callback' => ['App\Blocks\ContainerWidthTwoColumnImageSection', 'render'],
            'category'        => 'common',
            'icon'            => 'format-image',
            'mode'            => 'preview',
            'align'           => 'full',
            'supports'        => [
                'align'           => false,
                'mode'            => true,
                'jsx'             => true,
                'align_text'      => false,
            ],
        ]);

        // Register the Introtext With Right CTA Section Block
        acf_register_block_type([
            'name'            => 'introtext-with-right-cta-section',
            'title'           => __('Introtext With Right CTA Section', 'sage'),
            'description'     => __('A section with intro text and a right-aligned call to action', 'sage'),
            'render_callback' => ['App\Blocks\IntrotextWithRightCTASection', 'render'],
            'category'        => 'common',
            'icon'            => 'format-image',
            'mode'            => 'preview',
            'align'           => 'full',
            'supports'        => [
                'align'           => false,
                'mode'            => true,
                'jsx'             => true,
                'align_text'      => false,
            ],
        ]);

        // Register the Post Listing With Ajax Section Section Block
        acf_register_block_type([
            'name'            => 'post-listing-with-ajax-section',
            'title'           => __('Post Listing With Ajax Section', 'sage'),
            'description'     => __('A section with post listing with ajax', 'sage'),
            'render_callback' => ['App\Blocks\PostListingWithAjaxSection', 'render'],
            'category'        => 'common',
            'icon'            => 'format-image',
            'mode'            => 'preview',
            'align'           => 'full',
            'supports'        => [
                'align'           => false,
                'mode'            => true,
                'jsx'             => true,
                'align_text'      => false,
            ],
        ]);

        // Register the Related Post Listing Section Block
        acf_register_block_type([
            'name'            => 'related-post-listing-section',
            'title'           => __('Related Post Listing Section', 'sage'),
            'description'     => __('A section with related post listing', 'sage'),
            'render_callback' => ['App\Blocks\RelatedPostListingSection', 'render'],
            'category'        => 'common',
            'icon'            => 'format-image',
            'mode'            => 'preview',
            'align'           => 'full',
            'supports'        => [
                'align'           => false,
                'mode'            => true,
                'jsx'             => true,
                'align_text'      => false,
            ],
        ]);

        // Register the Google Map Section Block
        acf_register_block_type([
            'name'            => 'google-map-section',
            'title'           => __('Google Map Section', 'sage'),
            'description'     => __('A section with Google Map integration', 'sage'),
            'render_callback' => ['App\Blocks\GoogleMapSection', 'render'],
            'category'        => 'common',
            'icon'            => 'format-image',
            'mode'            => 'preview',
            'align'           => 'full',
            'supports'        => [
                'align'           => false,
                'mode'            => true,
                'jsx'             => true,
                'align_text'      => false,
            ],
        ]);

        // Register the Career Post Listing Section Block
        acf_register_block_type([
            'name'            => 'career-post-listing-section',
            'title'           => __('Career Post Listing Section', 'sage'),
            'description'     => __('A section with career post listing', 'sage'),
            'render_callback' => ['App\Blocks\CareerPostListingSection', 'render'],
            'category'        => 'common',
            'icon'            => 'format-image',
            'mode'            => 'preview',
            'align'           => 'full',
            'supports'        => [
                'align'           => false,
                'mode'            => true,
                'jsx'             => true,
                'align_text'      => false,
            ],
        ]);

        // Register the FAQs Accordion Section Block
        acf_register_block_type([
            'name'            => 'faqs-accordion-section',
            'title'           => __('FAQs Accordion Section', 'sage'),
            'description'     => __('A section for the FAQs accordion', 'sage'),
            'render_callback' => ['App\Blocks\FaqsAccordionSection', 'render'],
            'category'        => 'common',
            'icon'            => 'format-image',
            'mode'            => 'preview',
            'align'           => 'full',
            'supports'        => [
                'align'           => false,
                'mode'            => true,
                'jsx'             => true,
                'align_text'      => false,
            ],
        ]);

    }
});
