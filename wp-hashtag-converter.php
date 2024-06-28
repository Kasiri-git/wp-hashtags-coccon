<?php
/*
Plugin Name: WP Hashtags for Cocoon
Description: タグのテキスト部分の最初に＃を付与します。
Version: 1.1.2
Author: Kasiri
*/

function modify_tag_cloud($tag_string) {
    return preg_replace('/>([^<]+)<\/a>/', '>#$1</a>', $tag_string);
}
add_filter('wp_generate_tag_cloud', 'modify_tag_cloud', 10, 1);

function force_add_hash_to_tags_and_archive_title() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var archiveTitle = document.getElementById('archive-title');
        if (archiveTitle) {
            var titleText = archiveTitle.lastElementChild.textContent.trim();
            if (!titleText.startsWith('#')) {
                archiveTitle.lastElementChild.textContent = '#' + titleText;
            }
        }

        var tagLinks = document.querySelectorAll('.entry-tags .tag-link');
        tagLinks.forEach(function(link) {
            var tagIcon = link.querySelector('.tag-icon');
            if (tagIcon && tagIcon.nextSibling) {
                var textNode = tagIcon.nextSibling;
                var tagText = textNode.textContent.trim();
                if (!tagText.startsWith('#')) {
                    textNode.textContent = ' #' + tagText;
                }
            }
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'force_add_hash_to_tags_and_archive_title');

function add_hash_to_tag_links($output, $args) {
    return preg_replace('/<a([^>]+)><span([^>]+)><\/span>\s*([^<]+)<\/a>/', '<a$1><span$2></span> #$3</a>', $output);
}
add_filter('wp_tag_cloud', 'add_hash_to_tag_links', 10, 2);

function add_hash_to_post_tags($tags_list) {
    return preg_replace('/<a([^>]+)><span([^>]+)><\/span>\s*([^<]+)<\/a>/', '<a$1><span$2></span> #$3</a>', $tags_list);
}
add_filter('the_tags', 'add_hash_to_post_tags');