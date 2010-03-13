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
    <div class="prev"><?php previous_post_link('%link', '&laquo; Previous Post'); ?></div>
    <div class="next"><?php next_post_link('%link', 'Next Post &raquo;') ?></div>
  </div>

</div>


<?php $theme->display('footer') ?>
