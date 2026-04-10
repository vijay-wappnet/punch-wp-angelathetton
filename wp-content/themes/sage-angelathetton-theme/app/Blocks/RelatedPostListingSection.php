<?php

namespace App\Blocks;

class RelatedPostListingSection
{
    /**
     * Render the Related Post Listing Section block
     *
     * @param array $block The block settings and attributes
     * @param string $content The block content
     * @param bool $is_preview Whether we are in preview mode
     * @param int $post_id The post ID
     * @return void
     */
    public static function render($block, $content = '', $is_preview = false, $post_id = 0)
    {
        // Allowed post types from ACF Select field
        $allowedPostTypes = ['post', 'package', 'product'];

        // Get field values using ACF
        $title = html_entity_decode(get_field('heading_text') ?? '', ENT_QUOTES, 'UTF-8');
        $headingLevel = get_field('heading_level') ?: 'h2';
        $selectPostType = get_field('select_post_type') ?? 'post';

        // Validate post type against allowed values
        $selectPostType = in_array($selectPostType, $allowedPostTypes) ? $selectPostType : 'post';

        $postsPerPage = get_field('posts_per_page') ?? 3;
        $orderby = get_field('orderby') ?? 'date';
        $order = get_field('order') ?? 'DESC';
        $backgroundImage = get_field('background_image');
        $backgroundColor = get_field('background_color');
        $margin = get_field('margin');
        $padding = get_field('padding');

        // Generate unique block ID
        $blockId = 'rpls-' . ($block['id'] ?? uniqid());

        // Generate responsive CSS for margin and padding
        $responsiveCss = custom_acf_dimensions($margin, $padding, $blockId);

        // Build background style
        $backgroundStyle = [];

        // Always add background color if selected
        if (!empty($backgroundColor)) {
            $backgroundStyle[] = 'background-color: ' . esc_attr($backgroundColor);
        }

        // Always add background image if selected
        if (!empty($backgroundImage['url'])) {
            $backgroundStyle[] = 'background-image: url("' . esc_url($backgroundImage['url']) . '")';
            $backgroundStyle[] = 'background-size: cover';
            $backgroundStyle[] = 'background-position: center';
            $backgroundStyle[] = 'background-repeat: no-repeat';
        }

        // Convert array to inline style string
        $backgroundStyle = !empty($backgroundStyle) ? implode('; ', $backgroundStyle) . ';' : '';

        // Query posts
        $args = [
            'post_type' => $selectPostType,
            'posts_per_page' => intval($postsPerPage),
            'orderby' => $orderby,
            'order' => $order,
            'post_status' => 'publish',
        ];

        // Exclude current post if viewing a single post
        if (is_singular() && get_the_ID()) {
            $args['post__not_in'] = [get_the_ID()];
        }

        $posts_query = new \WP_Query($args);
        $posts = $posts_query->posts;

        // Render the Blade template with data
        echo view('blocks.related-post-listing-section', [
            'block'             => $block,
            'blockId'           => $blockId,
            'title'             => html_entity_decode($title, ENT_QUOTES, 'UTF-8'),
            'headingLevel'      => $headingLevel,
            'backgroundStyle'   => $backgroundStyle,
            'responsiveCss'     => $responsiveCss,
            'posts'             => $posts,
            'isPreview'         => $is_preview,
        ]);

        wp_reset_postdata();
    }

    /**
     * Get post excerpt or IntrotextSection content
     *
     * @param int $post_id The post ID
     * @return string The excerpt or introtext content
     */
    public static function getPostDescription($post_id)
    {
        // First try to get excerpt
        $excerpt = get_the_excerpt($post_id);

        if (!empty($excerpt) && $excerpt !== '') {
            return wp_trim_words($excerpt, 20, '...');
        }

        // If no excerpt, try to get IntrotextSection content from post content
        $post = get_post($post_id);
        if ($post && has_blocks($post->post_content)) {
            $blocks = parse_blocks($post->post_content);
            foreach ($blocks as $block) {
                if ($block['blockName'] === 'acf/introtext-section') {
                    // Get the content field from this block
                    if (!empty($block['attrs']['data']['content'])) {
                        $content = $block['attrs']['data']['content'];
                        return wp_trim_words(wp_strip_all_tags($content), 20, '...');
                    }
                }
            }
        }

        // Fallback to post content
        $content = get_the_content(null, false, $post_id);
        return wp_trim_words(wp_strip_all_tags($content), 20, '...');
    }
}
