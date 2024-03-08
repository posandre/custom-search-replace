<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://index.html
 * @since      1.0.0
 *
 * @package    Custom_Search_Replace
 * @subpackage Custom_Search_Replace/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Custom_Search_Replace
 * @subpackage Custom_Search_Replace/admin
 * @author     Andrii Postoliuk <an.postoliuk@gmail.com>
 */
class Custom_Search_Replace_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

    /**
     * The text domain of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $text_domain   The text domain of this plugin.
     */
    private $text_domain;


    /**
     * Function find posts by keyword in title an content
     *
     * @param $keyword
     * @return array
     */
    private function find_keyword_in_content($keyword) {
        $args = array(
            'post_type'         => 'post',
            'post_status'       => 'any',
            'posts_per_page'    => -1,
            'fields'            => 'ids',
            's'                 => $keyword // Search keyword for title and content
        );

        $search_results = get_posts($args);

        return $search_results;
    }

    /**
     * Function find posts by keyword in meta title
     *
     * @param $keyword
     * @return array
     */
    private function find_keyword_in_meta_title($keyword) {
        $args = array(
            'post_type'         => 'post',
            'post_status'       => 'any',
            'posts_per_page'    => -1,
            'fields'            => 'ids',
            'meta_query'     => array(
                array(
                    'key'     => '_yoast_wpseo_title', // Meta key for Yoast SEO title
                    'value'   => $keyword,
                    'compare' => 'LIKE'
                )
            ),
        );

        $search_results = get_posts($args);

        return $search_results;
    }

    /**
     * Function find posts by keyword in meta description
     *
     * @param $keyword
     * @return array
     */
    private function find_keyword_in_meta_description($keyword) {
        $args = array(
            'post_type'         => 'post',
            'post_status'       => 'any',
            'posts_per_page'    => -1,
            'fields'            => 'ids',
            'meta_query'     => array(
                array(
                    'key'     => '_yoast_wpseo_metadesc', // Meta key for Yoast SEO meta description
                    'value'   => $keyword,
                    'compare' => 'LIKE'
                )
            ),
        );

        $search_results = get_posts($args);

        return $search_results;
    }


    /**
     * Function find posts by keyword in title, content, meta title and meta description
     *
     * @param $keyword
     * @return array | boolean
     */
    private function find_keyword_in_posts($keyword) {

        $search_results_1 = $this->find_keyword_in_content($keyword);
        $search_results_2 = $this->find_keyword_in_meta_title($keyword);
        $search_results_3 = $this->find_keyword_in_meta_description($keyword);

        $search_results = array_merge($search_results_1, $search_results_2, $search_results_3);

        $result =  empty($search_results) ? false : array_unique($search_results);

        return $result;
    }

    /**
     * Function get table section content
     *
     * @param $ids
     * @return string
     */
    private function get_result_content( $ids ) {
        if ( empty($ids) ) return __('<tr><td class="message message--error" colspan="5">There have no posts, which include this keyword !!!</td></tr>', $this->text_domain);

        $result = '';

        $i = 1;
        foreach ( $ids as $id ) {
            $title = get_the_title($id);
            $content = get_post_field('post_content', $id);
            $meta_title = get_post_meta($id, '_yoast_wpseo_title', true);
            $meta_description = get_post_meta($id, '_yoast_wpseo_metadesc', true);

            $result .= "
            <tr id='post-{$id}'>
                <th>{$i}</th>
                <td>{$title}</td>
                <td>{$content}</td>
                <td>{$meta_title}</td>
                <td>{$meta_description}</td>
            </tr>
            ";

            $i++;
        }

        return $result;
    }


    /**
     * Function replace keyword in post content (post_title or post_content)
     *
     * @param $field
     * @return void
     */
    private function replace_keyword_in_content($field) {
        if ( !in_array($field, array('post_title', 'post_content')) ) wp_send_json_success( __('<tr><td class="message message--error" colspan="5">Field not supported</td></tr>', $this->text_domain) );

        if ( empty($_POST['replace_text'])  ) wp_send_json_success( __('<tr><td class="message message--error" colspan="5">New keyword is empty</td></tr>', $this->text_domain) );
        if ( empty($_POST['keyword'])  ) wp_send_json_success( __('<tr><td class="message message--error" colspan="5">Keyword is empty</td></tr>', $this->text_domain) );

        $replace_text = sanitize_text_field($_POST['replace_text']);
        $keyword = sanitize_text_field($_POST['keyword']);

        $ids = $this->find_keyword_in_content($keyword);

        foreach($ids as $id) {

            if ($field == 'post_title') {
                $current_string = get_the_title($id);
            } else {
                $current_string = get_post_field('post_content', $id);
            }

            $new_string = str_ireplace($keyword, $replace_text, $current_string);

            $post_data = array(
                'ID'        => $id,
                $field      => $new_string
            );

            wp_update_post($post_data);

        }

        $search_result = $this->find_keyword_in_posts($keyword);

        $result = $this->get_result_content( $search_result );

        wp_send_json_success( $result);
    }

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $text_domain ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->text_domain= $text_domain;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style(
            $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/custom-search-replace-admin.css',
            array(), $this->version,
            'all'
        );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script(
            $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/custom-search-replace-admin.js',
            array( 'jquery' ),
            $this->version,
            false
        );

	}

    /**
     * Add admin menu item.
     *
     * @since    1.0.0
     */
    public function add_admin_menu_item() {
        add_menu_page(
            __('Custom Search Replace', 'custom-search-replace'),
            __('Custom Search Replace', 'custom-search-replace'),
            'manage_options',
            'custom-search-replace',
            array($this, 'custom_search_replace_page')
        );
    }


    /**
     * Add plugin page in the admin panel.
     *
     * @since    1.0.0
     */
    public function custom_search_replace_page() {
        load_template(
            plugin_dir_path(__FILE__) . 'partials/custom-search-replace-admin-display.php',
            true,
            array('text-domain' => $this->text_domain)
        );
    }

    /**
     * Callback AJAX function, which search keyword in posts.
     *
     * @since    1.0.0
     */
    public function search_keyword_callback() {
        if ( empty($_POST['keyword'])  ) wp_send_json_success( __('<tr><td class="message message--error" colspan="5">Keyword is empty</td></tr>', $this->text_domain) );

        $keyword = sanitize_text_field($_POST['keyword']);

        $search_result = $this->find_keyword_in_posts($keyword);

        $result = $this->get_result_content( $search_result );

        wp_send_json_success( $result );
    }

    /**
     * Callback AJAX function, which replacing keyword in the post title.
     *
     * @since    1.0.0
     */
    public function replace_keyword_in_title_callback() {

        $this->replace_keyword_in_content('post_title');

    }

    /**
     * Callback AJAX function, which replacing keyword in the post content.
     *
     * @since    1.0.0
     */
    public function replace_keyword_in_content_callback() {

        $this->replace_keyword_in_content('post_content');

    }

    /**
     * Callback AJAX function, which replacing keyword in the post meta title.
     *
     * @since    1.0.0
     */
    public function replace_keyword_in_meta_title_callback() {
        if ( empty($_POST['replace_text'])  ) wp_send_json_success( __('<tr><td class="message message--error" colspan="5">New keyword is empty</td></tr>', $this->text_domain) );
        if ( empty($_POST['keyword'])  ) wp_send_json_success( __('<tr><td class="message message--error" colspan="5">Keyword is empty</td></tr>', $this->text_domain) );

        $replace_text = sanitize_text_field($_POST['replace_text']);
        $keyword = sanitize_text_field($_POST['keyword']);

        $ids = $this->find_keyword_in_meta_title($keyword);

        foreach($ids as $id) {
            $current_string = get_post_meta($id, '_yoast_wpseo_title', true);

            $new_string = str_ireplace($keyword, $replace_text, $current_string);

            update_post_meta($id, '_yoast_wpseo_title', $new_string);
        }

        $search_result = $this->find_keyword_in_posts($keyword);

        $result = $this->get_result_content( $search_result );

        wp_send_json_success( $result );

    }

    /**
     * Callback AJAX function, which replacing keyword in the post meta description.
     *
     * @since    1.0.0
     */
    public function replace_keyword_in_meta_description_callback() {

        if ( empty($_POST['replace_text'])  ) wp_send_json_success( __('<tr><td class="message message--error" colspan="5">New keyword is empty</td></tr>', $this->text_domain) );
        if ( empty($_POST['keyword'])  ) wp_send_json_success( __('<tr><td class="message message--error" colspan="5">Keyword is empty</td></tr>', $this->text_domain) );

        $replace_text = sanitize_text_field($_POST['replace_text']);
        $keyword = sanitize_text_field($_POST['keyword']);

        $ids = $this->find_keyword_in_meta_description($keyword);

        foreach($ids as $id) {
            $current_string = get_post_meta($id, '_yoast_wpseo_metadesc', true);

            $new_string = str_ireplace($keyword, $replace_text, $current_string);

            update_post_meta($id, '_yoast_wpseo_metadesc', $new_string);
        }

        $search_result = $this->find_keyword_in_posts($keyword);

        $result = $this->get_result_content( $search_result );

        wp_send_json_success( $result );

    }
}
