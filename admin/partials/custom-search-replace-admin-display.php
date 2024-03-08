<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://index.html
 * @since      1.0.0
 *
 * @package    Custom_Search_Replace
 * @subpackage Custom_Search_Replace/admin/partials
 */
?>

<div class="custom-search-replace__container wrap">
    <h1><?php _e('Custom Search', $args['text-domain']); ?></h1>
    <div class="tablenav top">
            <input  class="custom-search-replace-input custom-search-replace-input--search" type="text" name="search-keyword" placeholder="<?php _e('Enter keyword', $args['text-domain']); ?>">
            <input data-action="search_keyword"  class="button custom-search-replace-button" type="submit" name="search_submit" value="<?php _e('Search', $args['text-domain']); ?>">
    </div>
    <table class="wp-list-table widefat fixed striped table-view-list pages">
	    <thead>
            <tr>
                <th class="manage-column check-column"><?php _e('#', $args['text-domain']); ?></th>
                <th class="manage-column" >
                    <span><?php _e('Title', $args['text-domain']); ?></span>
                    <div class="form-container">
                        <input class="custom-search-replace-input custom-search-replace-input--replace" type="text" name="search-title-keyword" placeholder="<?php _e('Enter new keyword', $args['text-domain']); ?>">
                        <input data-action="replace_keyword_in_title" class="button custom-search-replace-button" type="submit" name="search_submit" value="<?php _e('Replace', $args['text-domain']); ?>">
                    </div>
                </th>
                <th class="manage-column" >
                    <span><?php _e('Content', $args['text-domain']); ?></span>
                    <div class="form-container">
                        <input class="custom-search-replace-input custom-search-replace-input--replace" type="text" name="search-content-keyword" placeholder="<?php _e('Enter new keyword', $args['text-domain']); ?>">
                        <input data-action="replace_keyword_in_content"  class="button custom-search-replace-button" type="submit" name="search_submit" value="<?php _e('Replace', $args['text-domain']); ?>">
                    </div>
                </th>
                <th class="manage-column">
                    <span><?php _e('Meta title', $args['text-domain']); ?></span>
                    <div class="form-container">
                        <input class="custom-search-replace-input custom-search-replace-input--replace" type="text" name="search-meta-title-keyword" placeholder="<?php _e('Enter new keyword', $args['text-domain']); ?>">
                        <input data-action="replace_keyword_in_meta_title" class="button custom-search-replace-button" type="submit" name="search_submit" value="<?php _e('Replace', $args['text-domain']); ?>">
                    </div>
                </th>
                <th class="manage-column">
                    <span><?php _e('Meta description', $args['text-domain']); ?></span>
                    <div class="form-container">
                        <input class="custom-search-replace-input custom-search-replace-input--replace" type="text" name="search-meta-description-keyword" placeholder="<?php _e('Enter new keyword', $args['text-domain']); ?>">
                        <input data-action="replace_keyword_in_meta_description" class="button custom-search-replace-button" type="submit" name="search_submit" value="<?php _e('Replace', $args['text-domain']); ?>">
                    </div>
                </th>
            </tr>
        </thead>
        <tbody id="search-results"></tbody>
    </table>
</div>
