<?php defined('HABARI_PATH') or die('No direct script access.') ?>
<?php $theme->display('header') ?>

<div id="coreContent">

    <div class="post hentry single" id="post-<?php echo $post->id ?>">
      <div class="postContent">
        <h2 class="entry-title"><?php echo $post->title_out ?></h2>
        <div class="entry-content">
            <?php echo $post->content_out ?>
        </div>
      </div>
   </div>

    <?php if ($loggedin): ?>
        <p><a class="post-edit-link" href="<?php echo $post->editlink ?>" title="<?php _e('Edit post') ?>"><?php _e('Edit this entry.') ?></a></p>
    <?php endif ?>

</div>

<?php $theme->display('footer') ?>
