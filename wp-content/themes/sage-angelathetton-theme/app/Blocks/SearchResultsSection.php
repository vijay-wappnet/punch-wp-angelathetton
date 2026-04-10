<?php

namespace App\Blocks;

class SearchResultsSection
{
    /**
     * Render the Search Results Section block
     *
     * @param array $block The block settings and attributes
     * @param string $content The block content
     * @param bool $is_preview Whether we are in preview mode
     * @param int $post_id The post ID
     * @return void
     */
    public static function render($block, $content = '', $is_preview = false, $post_id = 0)
    {
        // Allowed post types
        $allowedPostTypes = ['post', 'page', 'product', 'package', 'career'];

        // Get ACF fields
        $mainSearchTitle = get_field('main_search_title');
        $headingLevel = get_field('heading_level') ?: 'h2';
        $searchPlaceholder = get_field('search_placeholder') ?: 'Search...';
        $searchButton = get_field('search_button');
        $sectionBg = get_field('section_bg');
        $margin = get_field('margin');
        $padding = get_field('padding');
        $postsPerPage = 9; // Fixed posts per page for search results
        $orderby = 'date';
        $order = 'DESC';

        // Generate unique block ID
        $blockId = 'srs-' . ($block['id'] ?? uniqid());

        // Generate responsive CSS for margin and padding
        $responsiveCss = custom_acf_dimensions($margin, $padding, $blockId);

        // Background style
        $backgroundStyle = '';
        if (!empty($sectionBg)) {
            $backgroundStyle = 'background-color: ' . esc_attr($sectionBg) . ';';
        }

        // Validate search button structure
        if (!is_array($searchButton)) {
            $searchButton = [
                'button_title' => 'Search',
                'aria_label' => '',
                'button_google_event_label' => '',
                'button_class' => 'site-button',
            ];
        }

        // Handle search
        $results = [];
        $pagination = '';
        $totalResults = 0;
        // Get current page for pagination (support pretty permalinks and query string)
        $paged = 1;
        if (get_query_var('paged')) {
            $paged = (int) get_query_var('paged');
        } elseif (get_query_var('page')) {
            $paged = (int) get_query_var('page');
        } elseif (isset($_GET['paged'])) {
            $paged = (int) $_GET['paged'];
        } elseif (isset($_POST['paged'])) {
            $paged = (int) $_POST['paged'];
        }
        $searchTerm = isset($_GET['search-field']) ? sanitize_text_field($_GET['search-field']) : (isset($_POST['search-field']) ? sanitize_text_field($_POST['search-field']) : '');
        if (!empty($searchTerm)) {
            $args = [
                'post_type' => $allowedPostTypes,
                'posts_per_page' => $postsPerPage,
                'orderby' => $orderby,
                'order' => $order,
                'post_status' => 'publish',
                'paged' => $paged,
                's' => $searchTerm,
            ];
            $query = new \WP_Query($args);
            $totalResults = $query->found_posts;
            foreach ($query->posts as $post) {
                $results[] = [
                    'title' => get_the_title($post->ID),
                    'link' => get_permalink($post->ID),
                ];
            }
            // Pagination with search term in query string
            $totalPages = $query->max_num_pages;
            if ($totalPages > 1) {
                $pagination = paginate_links([
                    'total' => $totalPages,
                    'current' => $paged,
                    'type' => 'list',
                    'format' => '?search-field=' . urlencode($searchTerm) . '&paged=%#%',
                    'add_args' => ['search-field' => $searchTerm],
                    'prev_text' => __('«'),
                    'next_text' => __('»'),
                    'before_page_number' => '<span>',
                    'after_page_number' => '</span>',
                    'class' => 'pagination',
                ]);
            }
            wp_reset_postdata();
        }

        // Render the Blade template with data
        echo view('blocks.search-results-section', [
            'blockId'         => $blockId,
            'mainSearchTitle' => $mainSearchTitle,
            'headingLevel'    => $headingLevel,
            'searchPlaceholder' => $searchPlaceholder,
            'searchButton'    => $searchButton,
            'backgroundStyle' => $backgroundStyle,
            'responsiveCss'   => $responsiveCss,
            'results'         => $results,
            'pagination'      => $pagination,
            'searchTerm'      => $searchTerm,
            'totalResults'    => $totalResults,
        ]);
    }
}
