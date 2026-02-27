<?php

/**
 * Register custom Gutenberg blocks.
 */

namespace App;

use Illuminate\Support\Facades\Vite;

/**
 * Register the Introtext Section Block
 */
if (function_exists('acf_register_block_type')) {
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
}
