<?php defined('HABARI_PATH') or die('No direct script access.') ?>
<?php $theme->display('header') ?>

<div id="coreContent">
  <div id="archives" class="single hentry">
    <h2 class="entry-title"><?php echo $post->title_out ?></h2>

    <div id="date">
      <h3>Months</h3>
      <ul>
        <?php echo $theme->get_archives() ?>
      </ul>
    </div>

    <div id="categoryTags">
      <h3>Tags</h3>
      <?php echo $theme->tag_cloud() ?>
    </div>
  </div>

</div>

<?php $theme->display('footer') ?>
