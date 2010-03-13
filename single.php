<?php $theme->display('header') ?>

<div id="coreContent">

      <div class="post single hentry">
        <div class="postContent">
          <h3 class="entry-title"><?php echo $post->title_out ?></h3>
          <h4 class="vcard author">by <span class="fn"><?php echo $post->author->username ?></span></h4>
          <div class="entry-content">
              <?php echo $post->content_out ?>
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
            <a href="<?php URL::out('display_entries_by_date', $arc_args) ?>"><?php echo $post->pubdate->text_format('{F} {j}, {Y}'); ?></a></abbr></div>
            <?php if (count($post->tags)): ?>
            <span>Tags:</span> <?php echo $post->tags_out ?>
            <?php endif ?>
        </div>
      </div>

      <?php
      /*
      <div class="googleAd">

        <!-- You Ad Code Here -->

      </div>
      */
      ?>

    <?php $theme->display('comments') ?>

  <div class="pageNav">
    <div class="prev">
    <?php if ($prev = $post->descend()): ?>
        <a href="<?php echo $prev->permalink ?>" title="<?php echo $prev->title_out ?>">&laquo; Previous Post</a>
    <?php endif ?>
    </div>
    <div class="next">
    <?php if ($next = $post->ascend()): ?>
        <a href="<?php echo $next->permalink ?>" title="<?php echo $next->title_out ?>">Next Post &raquo;</a>
    <?php endif ?>
    </div>
  </div>

</div>


<?php $theme->display('footer') ?>
