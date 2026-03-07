<?php

namespace App\Blocks;

class PostListingWithAjaxSection
{
    /**
     * Render the Post Listing With Ajax Section block
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
        $selectPostType = get_field('select_post_type') ?? 'post';

        // Validate post type against allowed values
        $selectPostType = in_array($selectPostType, $allowedPostTypes) ? $selectPostType : 'post';

        $postsPerPage = get_field('posts_per_page') ?? 3;
        $orderby = get_field('orderby') ?? 'date';
        $order = get_field('order') ?? 'DESC';
        $loadMoreButton = get_field('load_more_button');
        $sectionBg = get_field('section_bg');
        $margin = get_field('margin');
        $padding = get_field('padding');

        // Generate unique block ID
        $blockId = 'plwas-' . ($block['id'] ?? uniqid());

        // Generate responsive CSS for margin and padding
        $responsiveCss = custom_acf_dimensions($margin, $padding, $blockId);

        // Background style
        $backgroundStyle = '';
        if (!empty($sectionBg)) {
            $backgroundStyle = 'background-color: ' . esc_attr($sectionBg) . ';';
        }

        // Validate load more button structure
        if (!is_array($loadMoreButton)) {
            $loadMoreButton = [
                'button_title' => 'Load More',
                'aria_label' => '',
                'button_google_event_label' => '',
                'button_class' => 'trans-black-btn',
            ];
        }

        // Query posts
        $args = [
            'post_type' => $selectPostType,
            'posts_per_page' => intval($postsPerPage),
            'orderby' => $orderby,
            'order' => $order,
            'post_status' => 'publish',
        ];

        $posts_query = new \WP_Query($args);
        $posts = $posts_query->posts;
        $totalPosts = $posts_query->found_posts;
        $hasMorePosts = $totalPosts > intval($postsPerPage);

        // Render the Blade template with data
        echo view('blocks.post-listing-with-ajax-section', [
            'block'             => $block,
            'blockId'           => $blockId,
            'selectPostType'    => $selectPostType,
            'postsPerPage'      => $postsPerPage,
            'orderby'           => $orderby,
            'order'             => $order,
            'loadMoreButton'    => $loadMoreButton,
            'backgroundStyle'   => $backgroundStyle,
            'responsiveCss'     => $responsiveCss,
            'posts'             => $posts,
            'hasMorePosts'      => $hasMorePosts,
            'totalPosts'        => $totalPosts,
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
