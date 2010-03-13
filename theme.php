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
}
