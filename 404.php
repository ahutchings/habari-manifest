<?php defined('HABARI_PATH') or die('No direct script access.') ?>
<?php $theme->display('header') ?>

<div id="coreContent">


    <div class="post hentry single">
      <div class="postContent">
        <h2 class="entry-title">404 - Page Not Found</h2>
        <div class="entry-content">
          <p>Unfortunately the content you're looking for isn't here. There may be a misspelling in your web address or you may have clicked a link for content that no longer exists. Perhaps you would be interested in our most recent articles.</p>
        </div>
      </div>
   </div>


   <h4>Recent Articles</h4>
   <ul id="recentPosts">

      <?php foreach ($posts as $post): ?>

      <li>
        <a href="<?php echo $post->permalink ?>" rel="bookmark"><?php echo $post->title_out ?></a>
        <div class="postDate"><abbr class="published" title="<?php echo $post->pubdate->text_format('{Y}-{m}-{d}T{H}:{i}:{s}{O}') ?>"><?php echo $post->pubdate->text_format('{F} {j}, {Y}') ?></abbr></div>
      </li>

      <?php endforeach ?>

    </ul>
       </div>

</div>



<?php $theme->display('footer') ?>
