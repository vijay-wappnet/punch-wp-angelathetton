<?php

namespace App\Blocks;

class FeaturedArticlePostSection
{
    /**
     * Render the Featured Article Post Section block
     *
     * @param array $block The block settings and attributes
     * @param string $content The block content
     * @param bool $is_preview Whether we are in preview mode
     * @param int $post_id The post ID
     * @return void
     */
    public static function render($block, $content = '', $is_preview = false, $post_id = 0)
    {
        // Get field values using ACF
        $button = get_field('button') ?: [];
        $section_bg = get_field('section_bg') ?: '';
        $margin = get_field('margin');
        $padding = get_field('padding');

        // Generate unique block ID
        $blockId = 'faps-' . ($block['id'] ?? uniqid());

        // Generate responsive CSS for margin and padding
        $responsiveCss = custom_acf_dimensions($margin, $padding, $blockId);

        // Get featured article post where is_featured_article is true
        $featured_post = self::getFeaturedPost();

        // Render the Blade template with data using view helper
        echo view('blocks.featured-article-post-section', [
            'blockId'       => $blockId,
            'responsiveCss' => $responsiveCss,
            'button'        => $button,
            'section_bg'    => $section_bg,
            'featured_post' => $featured_post,
            'is_preview'    => $is_preview,
        ]);
    }

    /**
     * Get the featured article post
     *
     * @return object|null
     */
    public static function getFeaturedPost()
    {
        $args = [
            'post_type'      => 'post',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
            'meta_query'     => [
                [
                    'key'     => 'is_featured_article',
                    'value'   => '1',
                    'compare' => '=',
                ],
            ],
        ];

        $query = new \WP_Query($args);

        if ($query->have_posts()) {
            $post = $query->posts[0];
            $post_id = $post->ID;

            return (object) [
                'id'             => $post_id,
                'title'          => html_entity_decode(get_the_title($post_id), ENT_QUOTES, 'UTF-8'),
                'permalink'      => get_permalink($post_id),
                'featured_image' => get_the_post_thumbnail_url($post_id, 'full'),
                'publish_date'   => get_the_date('m/d/y', $post_id),
            ];
        }

        wp_reset_postdata();

        return null;
    }
}
