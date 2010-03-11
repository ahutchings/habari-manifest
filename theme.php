<?php

define('THEME_CLASS', 'Manifest');

class Manifest extends Theme
{
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
}
