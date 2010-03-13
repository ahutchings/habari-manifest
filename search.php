<?php defined('HABARI_PATH') or die('No direct script access.') ?>
<?php $theme->display('header') ?>

<div id="coreContent" class="searchresults">

  <div class="searchpanel">
    <form method="get" id="searchform" action="<?php URL::out('display_search') ?>">
      <div id="search">
        <input type="text" value="<?php if (isset($criteria)) { echo htmlentities($criteria, ENT_COMPAT, 'UTF-8'); } ?>" name="s" id="s" />
        <input type="submit" id="searchsubmit" value="Search" />
      </div>
    </form>
  </div>

  <h2>Search Results</h2>


        <?php if (count($posts)) : ?>

            <?php foreach ($posts as $post): ?>
      <div class="post hentry">
        <div class="postContent">
          <h3 class="entry-title"><a href="<?php echo $post->permalink ?>" rel="bookmark"><?php echo $post->title_out ?></a></h3>
          <div class="entry-content">
            <?php the_excerpt('Read the rest of this entry &raquo;'); ?>
          </div>
        </div>
        <div class="postMeta">
          <?php
          $arc_args = array(
            'year' => $post->pubdate->text_format('{Y}'),
            'month' => $post->pubdate->text_format('{m}'),
            'day' => $post->pubdate->text_format('{d}')
          );
          ?>

          <div class="postDate"><span>Published:</span> <abbr class="published" title="<?php echo $post->pubdate->text_format('{Y}-{m}-{d}T{H}:{i}:{s}{O}') ?>">
            <a href="<?php URL::out('display_entries_by_date', $arc_args) ?>"><?php echo $post->pubdate->text_format('{F} {j}, {Y}') ?></a></abbr></div>
            <?php if (count($post->tags)): ?>
            <span>Tags:</span> <?php echo $post->tags_out ?>
            <?php endif ?>
        </div>
      </div>
        <?php endforeach ?>

    <div class="pageNav">
      <div class="prev"><?php $theme->next_page_link('&laquo; Older') ?></div>
      <div class="next"><?php $theme->prev_page_link('Newer &raquo;') ?></div>
    </div>

    <?php else : ?>

        <h2 class="center">Not Found</h2>
        <p class="center">Sorry, but you are looking for something that isn't here.</p>

    <?php endif; ?>

</div>


<?php $theme->display('footer') ?>

