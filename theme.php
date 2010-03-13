<?php

define('THEME_CLASS', 'Manifest');

class Manifest extends Theme
{
    public function action_init_theme()
    {
        Format::apply('tag_and_list', 'post_tags_out', ' : ', ' : ');
    }

    /**
    * Basic emulation of wp_title().
    */
    public function title($sep = '&raquo;', $echo = true, $seplocation = null)
    {
        switch (URL::get_matched_rule()->name) {
        case 'display_entry':
        case 'display_page':
            $title = $this->post->title.' &#8211; '.Options::get('title');
            break;

        case 'display_entries_by_tag':
            $title = 'Tag Archive for &#8220;'.$this->tag.'&#8221; &#8211; '.Options::get('title');
            break;

        case 'display_home':
        default:
            $title = null;
        }

        if (!empty($title)) {
            $title = ($seplocation == 'right') ? $title.' '.$sep : $sep.' '.$title;
        }

        if ($echo) { echo $title; }

        return $title;
    }

    /**
    * Basic emulation of wp_list_pages().
    */
    public function list_pages()
    {
        $items = array();

        $pages = Posts::get(array('content_type' => 'page', 'status' => Post::status('published'), 'nolimit' => 1));

        foreach ($pages as $page) {
            $anchor = "<a href=\"$page->permalink\" title=\"$page->title\">$page->title</a>";
            $classes = array('page_item', 'page-item-'.$page->id);

            if (isset($this->post) && $this->post->id == $page->id) {
                $classes[] = 'current_page_item';
            }

            $items[] = '<li class="'.implode(' ', $classes).'">'.$anchor.'</li>';
        }

        echo implode("\n", $items);
    }

    /**
     * Emulate Wordpress's comments_popup_link().
     */
    public function comments_popup_link($post, $zero = 'No Comments', $one = '1 Comment', $more = '% Comments', $class = '', $none = 'Comments Off')
    {
        if ($post->info->comments_disabled) {
            $content = $none;
        } elseif ($post->comments->approved->count == 0) {
            $content = $zero;
        } elseif ($post->comments->approved->count == 1) {
            $content = $one;
        } elseif ($post->comments->approved->count > 1) {
            $content = str_replace('%', $post->comments->approved->count, $more);
        }

        echo "<a href=\"{$post->permalink}#comments\" title=\""
            . _t('Comments on this post') . '">'
            . $content
            . '</a>';
    }

    /**
     * Retrieve the avatar for a user who provided a user ID or email address.
     *
     * @param int|string|object $id_or_email A user ID,  email address, or comment object
     * @param int $size Size of the avatar image
     * @param string $default URL to a default image to use if no avatar is available
     * @param string $alt Alternate text to use in image tag. Defaults to blank
     * @return string <img> tag for the user's avatar
    */
    function get_avatar($id_or_email, $size = '96', $default = '', $alt = false)
    {
        if ( false === $alt)
            $safe_alt = '';
        else
            $safe_alt = esc_attr( $alt );

        if ( !is_numeric($size) )
            $size = '96';

        $email = '';
        if ( is_numeric($id_or_email) ) {
            $id = (int) $id_or_email;
            $user = get_userdata($id);
            if ( $user )
                $email = $user->user_email;
        } elseif ( is_object($id_or_email) ) {
            if ( isset($id_or_email->comment_type) && '' != $id_or_email->comment_type && 'comment' != $id_or_email->comment_type )
                return false; // No avatar for pingbacks or trackbacks

            if ( !empty($id_or_email->user_id) ) {
                $id = (int) $id_or_email->user_id;
                $user = get_userdata($id);
                if ( $user)
                    $email = $user->user_email;
            } elseif ( !empty($id_or_email->comment_author_email) ) {
                $email = $id_or_email->comment_author_email;
            }
        } else {
            $email = $id_or_email;
        }

        if ( empty($default) ) {
                $default = 'mystery';
        }

        if (self::is_ssl())
            $host = 'https://secure.gravatar.com';
        else
            $host = 'http://www.gravatar.com';

        if ( 'mystery' == $default )
            $default = "$host/avatar/ad516503a11cd5ca435acc9bb6523536?s={$size}"; // ad516503a11cd5ca435acc9bb6523536 == md5('unknown@gravatar.com')
        elseif ( 'blank' == $default )
            $default = includes_url('images/blank.gif');
        elseif ( !empty($email) && 'gravatar_default' == $default )
            $default = '';
        elseif ( 'gravatar_default' == $default )
            $default = "$host/avatar/s={$size}";
        elseif ( empty($email) )
            $default = "$host/avatar/?d=$default&amp;s={$size}";
        elseif ( strpos($default, 'http://') === 0 )
            $default = add_query_arg( 's', $size, $default );

        if ( !empty($email) ) {
            $out = "$host/avatar/";
            $out .= md5( strtolower( $email ) );
            $out .= '?s='.$size;
            $out .= '&amp;d=' . urlencode( $default );

            $rating = get_option('avatar_rating');
            if ( !empty( $rating ) )
                $out .= "&amp;r={$rating}";

            $avatar = "<img alt='{$safe_alt}' src='{$out}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
        } else {
            $avatar = "<img alt='{$safe_alt}' src='{$default}' class='avatar avatar-{$size} photo avatar-default' height='{$size}' width='{$size}' />";
        }

        return $avatar;
    }

    /**
     * Determine if SSL is used.
     *
     * @since 2.6.0
     *
     * @return bool True if SSL, false if not used.
     */
    public static function is_ssl() {
        if ( isset($_SERVER['HTTPS']) ) {
            if ( 'on' == strtolower($_SERVER['HTTPS']) )
                return true;
            if ( '1' == $_SERVER['HTTPS'] )
                return true;
        } elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
            return true;
        }
        return false;
    }
}
