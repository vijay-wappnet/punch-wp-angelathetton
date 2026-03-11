<?php

namespace App\Blocks;

class CareerPostListingSection
{
    /**
     * Render the Career Post Listing Section block
     */
    public static function render($block, $content = '', $is_preview = false, $post_id = 0)
    {
        $allowedPostTypes = ['career'];
        $allowedOrderBy = ['date', 'title', 'ID'];
        $allowedOrder = ['DESC', 'ASC'];

        $selectPostType = get_field('select_post_type') ?: 'career';
        $selectPostType = in_array($selectPostType, $allowedPostTypes, true) ? $selectPostType : 'career';


        // Use ACF field value, or fallback to WordPress Reading settings
        $postsPerPage = get_field('posts_per_page') ?: get_option('posts_per_page', 10);
        // Mobile posts per page (fallback to WordPress Reading settings if empty)
        $postsPerPageMobile = get_field('posts_per_page_mobile') ?: get_option('posts_per_page', 10);

        $orderby = get_field('orderby') ?: 'date';
        $orderby = in_array($orderby, $allowedOrderBy, true) ? $orderby : 'date';

        $order = get_field('order') ?: 'DESC';
        $order = in_array($order, $allowedOrder, true) ? $order : 'DESC';

        $sectionBg = get_field('section_bg');
        $margin = get_field('margin');
        $padding = get_field('padding');

        $blockId = 'cpls-' . ($block['id'] ?? uniqid());
        $responsiveCss = custom_acf_dimensions($margin, $padding, $blockId);

        $backgroundStyle = '';
        if (!empty($sectionBg)) {
            $backgroundStyle = 'background-color: ' . esc_attr($sectionBg) . ';';
        }

        $paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));

        $args = [
            'post_type'      => $selectPostType,
            'posts_per_page' => $postsPerPage,
            'orderby'        => $orderby,
            'order'          => $order,
            'post_status'    => 'publish',
            'paged'          => $paged,
        ];

        $postsQuery = new \WP_Query($args);

        $posts = $postsQuery->posts;
        $totalPosts = (int) $postsQuery->found_posts;
        $maxNumPages = (int) $postsQuery->max_num_pages;
        $currentPage = (int) max(1, $paged);

        echo view('blocks.career-post-listing-section', [
            'block'           => $block,
            'blockId'         => $blockId,
            'selectPostType'  => $selectPostType,
            'postsPerPage'    => $postsPerPage,
            'postsPerPageMobile' => $postsPerPageMobile,
            'orderby'         => $orderby,
            'order'           => $order,
            'backgroundStyle' => $backgroundStyle,
            'responsiveCss'   => $responsiveCss,
            'posts'           => $posts,
            'totalPosts'      => $totalPosts,
            'currentPage'     => $currentPage,
            'maxNumPages'     => $maxNumPages,
            'prevPageUrl'     => $currentPage > 1 ? get_pagenum_link($currentPage - 1) : '',
            'nextPageUrl'     => $currentPage < $maxNumPages ? get_pagenum_link($currentPage + 1) : '',
            'isPreview'       => $is_preview,
            'email'           => self::getAdminEmail(), // added
        ]);

        wp_reset_postdata();
    }

    /**
     * Get the post description
     */
    public static function getPostDescription($post_id): string
    {
        $excerpt = get_the_excerpt($post_id);
        if (!empty($excerpt)) {
            return wp_trim_words(wp_strip_all_tags($excerpt), 20, '...');
        }

        $content = get_post_field('post_content', $post_id);
        return wp_trim_words(wp_strip_all_tags((string) $content), 20, '...');
    }
    /**
     * Get site admin email
     */
    public static function getAdminEmail(): string
    {
        $email = get_option('admin_email');
        return is_email($email) ? sanitize_email($email) : '';
    }
}
