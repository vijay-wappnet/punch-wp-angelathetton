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
    }
});
