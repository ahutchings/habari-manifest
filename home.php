<?php defined('HABARI_PATH') or die('No direct script access.') ?>
<?php $theme->display('header') ?>

<?php if ($this->request->display_entries_by_tag): ?>
<h2 class="archiveTitle">Tag: <strong><?php echo $tag ?></strong></h2>
<?php endif ?>

<?php if ($this->request->display_entries_by_date && isset($month) && !isset($day)): ?>
<h2 class="archiveTitle">Month: <strong><?php echo date('F, Y', mktime(0, 0, 0, $month, 1, $year)) ?></strong></h2>
<?php endif ?>

    <div id="coreContent" class="hfeed">

    <?php if (count($posts)): ?>

        <?php foreach ($posts as $post): ?>

      <div class="post hentry">
        <h5 class="postDate"><abbr class="published">
        <?php echo $post->pubdate->text_format('{F} {j}, {Y}') ?>
        </abbr></h5>
        <div class="postContent">
          <h3 class="entry-title"><a href="<?php echo $post->permalink ?>" rel="bookmark"><?php echo $post->title_out ?></a></h3>
          <!-- <h4 class="vcard author">by <span class="fn"><?php echo $post->author->username ?></span></h4> -->

          <div class="entry-content">

              <?php echo $post->content_out ?>

          </div>
        </div>
        <div class="postMeta">

        <?php if ($post->info->comments_disabled): ?>

          <div class="comments closed">

        <?php else: ?>

          <div class="comments">

        <?php endif; ?>

            <?php $theme->comments_link($post) ?>
          </div>
        </div>
      </div>

        <?php endforeach ?>

    <div class="pageNav">
      <div class="prev"><?php $theme->next_page_link('&laquo; Older') ?></div>
      <div class="next"><?php $theme->prev_page_link('Newer &raquo;') ?></div>
    </div>

    <?php else : ?>

        <h2>Not Found</h2>
        <p>Sorry, but you are looking for something that isn't here.</p>

    <?php endif; ?>

  </div>


<?php $theme->display('footer') ?>
