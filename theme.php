<?php defined('HABARI_PATH') or die('No direct script access.');

define('THEME_CLASS', 'Manifest');

class Manifest extends Theme
{
    public function action_init_theme()
    {
        Format::apply('tag_and_list', 'post_tags_out', ' : ', ' : ');
        Format::apply_with_hook_params('more', 'post_content_excerpt', '', 56, 1);
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

    public function add_template_vars()
    {
        if ($this->request->display_404) {
            if (!$this->template_engine->assigned('posts')) {
                $this->assign('posts', Posts::get(array('content_type' => 'entry', 'status' => Post::status('published'), 'limit' => 5)));
            }
        }

        parent::add_template_vars();
    }

    public function tag_cloud($args = '')
    {
        $tags = Tags::get();

        if (count($tags) == 0) {
            return;
        }

        $counts = array();
        foreach ($tags as $tag) {
            $counts[] = $tag->count;
        }

        $largest = 22;
        $smallest = 8;

        $min_count = min( $counts );
        $spread = max( $counts ) - $min_count;
        if ( $spread <= 0 )
            $spread = 1;
        $font_spread = $largest - $smallest;
        if ( $font_spread < 0 )
            $font_spread = 1;
        $font_step = $font_spread / $spread;

        $a = array();

        foreach ($tags as $tag) {
            $tag_link = URL::get('display_entries_by_tag', array('tag' => $tag->slug));
            $a[] = "<a href='$tag_link' class='tag-link-$tag->id' title='" . $tag->count. " topic(s)' rel='tag' style='font-size: " .
                ( $smallest + ( ( $tag->count - $min_count ) * $font_step ) )
                . "pt;'>$tag->tag</a>";
        }

        $return = "<ul class='wp-tag-cloud'>\n\t<li>";
        $return .= implode( "</li>\n\t<li>", $a );
        $return .= "</li>\n</ul>\n";

        return $return;
    }

    public function get_archives()
    {
        $q = "SELECT YEAR( FROM_UNIXTIME(pubdate) ) AS year, MONTH(  FROM_UNIXTIME(pubdate)  ) AS month, COUNT( id ) AS cnt
                FROM  {posts}
                WHERE content_type = ? AND status = ?
                GROUP BY year, month
                ORDER BY pubdate DESC";
        $p[]= Post::type( 'entry' );
        $p[]= Post::status( 'published' );
        $results = DB::get_results( $q, $p );


        $archives[]= '<ul id="monthly_archives">';

        if ( empty( $results ) ) {
            $archives[]= '<li>No Archives Found</li>';
        } else {
            foreach ($results as $result) {

                // make sure the month has a 0 on the front, if it doesn't
                $result->month = str_pad( $result->month, 2, 0, STR_PAD_LEFT );

                $result->month_ts = mktime( 0, 0, 0, $result->month );
                $result->display_month = date('F', $result->month_ts);

                $archives[]= '<li>';
                $archives[]= '<a href="' . URL::get( 'display_entries_by_date', array('year' => $result->year, 'month' => $result->month)) . '" title="' . $result->display_month . ' ' . $result->year . '">' . $result->display_month . ' ' . $result->year . '</a>';
                $archives[]= '</li>';

            }
        }

        $archives[]= '</ul>';

        return implode("\n", $archives);
    }
}
