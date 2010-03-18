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
     * Output the page title.
     */
    public function out_title()
    {
        switch (URL::get_matched_rule()->name) {
        case 'display_entry':
        case 'display_page':
            $title = $this->post->title;
            break;

        case 'display_entries_by_tag':
            $title = $this->tag;
            break;

        case 'display_entries_by_date':
            $args = URL::get_matched_rule()->named_arg_values;

            if (isset($args['day'])) {
                $title = date('d : F : Y', mktime(0, 0, 0, $args['month'], $args['day'], $args['year']));
            } elseif (isset($args['month'])) {
                $title = date('F : Y', mktime(0, 0, 0, $args['month'], 1, $args['year']));
            } else {
                $title = date('Y', mktime(0, 0, 0, 1, 1, $args['year']));
            }
            break;

        default:
            $title = NULL;
        }

        if (!empty($title)) {
            $title .= ' :';
        }

        echo $title;
    }

    /**
     * Output a list of pages.
     *
     * @return null
     */
    public function list_pages()
    {
        $out = array();

        $pages = Posts::get(array('content_type' => 'page', 'status' => Post::status('published'), 'nolimit' => 1));

        foreach ($pages as $page) {
            $anchor = "<a href=\"$page->permalink\" title=\"$page->title\">$page->title</a>";
            $classes = array('page_item', 'page-item-'.$page->id);

            if (isset($this->post) && $this->post->id == $page->id) {
                $classes[] = 'current_page_item';
            }

            $out[] = '<li class="'.implode(' ', $classes).'">'.$anchor.'</li>';
        }

        echo implode("\n", $out);
    }

    /**
     * Output a link to the post's comment form.
     *
     * @param object $post Post object
     *
     * @return null
     */
    public function comments_link($post)
    {
        if ($post->info->comments_disabled) {
            $content = 'comments closed';
        } elseif ($post->comments->approved->count == 0) {
            $content = 'leave a comment';
        } else {
            $content = $post->comments->approved->count . ' '
                . _n('comment', 'comments', $post->comments->approved->count);
        }

        echo '<a href="'.$post->permalink.'#comments" title="'
            . _t('Comments on this post') . '">'
            . $content
            . '</a>';
    }

    /**
     * Retrieve the avatar for a user.
     *
     * @param object $comment A comment object
     *
     * @return string <img> tag for the user's avatar
    */
    function get_avatar($comment)
    {
        $size = 48;
        $host = (self::is_ssl()) ? 'https://secure.gravatar.com' : 'http://www.gravatar.com';

        $default = "$host/avatar/ad516503a11cd5ca435acc9bb6523536?s=$size"; // ad516503a11cd5ca435acc9bb6523536 == md5('unknown@gravatar.com')

        if (!empty($comment->email)) {
            $out = "$host/avatar/";
            $out .= md5(strtolower($comment->email));
            $out .= "?s=$size";
            $out .= '&amp;d='.urlencode($default);

            $avatar = "<img alt='' src='{$out}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
        } else {
            $avatar = "<img alt='' src='{$default}' class='avatar avatar-{$size} photo avatar-default' height='{$size}' width='{$size}' />";
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

    /**
     * Retrieve an unordered list of tags.
     *
     * @return string
     */
    public function tag_cloud()
    {
        $tags = Tags::get();

        if (count($tags) == 0) {
            return;
        }

        $counts = array();
        foreach ($tags as $tag) {
            $counts[] = $tag->count;
        }

        $min    = min($counts);
        $step   = 14 / max(max($counts) - $min, 1);

        $out[] = '<ul class="tag-cloud">';

        foreach ($tags as $tag) {
            $link = URL::get('display_entries_by_tag', array('tag' => $tag->slug));
            $size = 8 + (($tag->count - $min) * $step);

            $out[] = '<li>';
            $out[] = "<a href='$link' class='tag-link-$tag->id' title='".$tag->count." topic(s)' rel='tag' style='font-size:"
                .$size."pt;'>$tag->tag</a>";
            $out[] = '</li>';
        }

        $out[] = '</ul>';

        return implode("\n", $out);
    }

    /**
     * Retrieve an unordered list of monthly archive links.
     *
     * @return string
     */
    public function get_archives()
    {
        $q = "SELECT YEAR(FROM_UNIXTIME(pubdate)) AS year, MONTH(FROM_UNIXTIME(pubdate)) AS month
                FROM  {posts}
                WHERE content_type = ? AND status = ?
                GROUP BY year, month
                ORDER BY pubdate DESC";
        $p = array(Post::type('entry'), Post::status('published'));
        $results = DB::get_results($q, $p);

        $out[] = '<ul id="monthly_archives">';

        if (empty($results)) {
            $out[] = '<li>No Archives Found</li>';
        } else {
            foreach ($results as $result) {
                // make sure the month has a 0 on the front, if it doesn't
                $result->month = str_pad($result->month, 2, 0, STR_PAD_LEFT);
                $display_month = date('F', mktime(0, 0, 0, $result->month));

                $out[] = '<li>';
                $out[] = '<a href="' . URL::get('display_entries_by_date', array('year' => $result->year, 'month' => $result->month)) . '" title="' . $display_month . ' ' . $result->year . '">' . $display_month . ' ' . $result->year . '</a>';
                $out[] = '</li>';
            }
        }

        $out[] = '</ul>';

        return implode("\n", $out);
    }
}
