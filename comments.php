<?php defined('HABARI_PATH') or die('No direct script access.') ?>

<!-- You can start editing here. -->
<div id="comments">
<?php if ($post->comments->moderated->count): ?>

  <h4 id="comments">Comments</h4>

    <?php foreach ($post->comments->moderated as $comment): ?>
    <?php
        if ($comment->url_out == '') {
            $comment_url = $comment->name_out;
        } else {
            $comment_url = '<a href="' . $comment->url_out . '" rel="external">' . $comment->name_out . '</a>';
        }
    ?>
    <div class="commentEntry">
      <?php echo $theme->get_avatar($comment) ?>
        <div class="commentContent" id="comment-<?php echo $comment->id ?>">
        <?php if ($comment->status == Comment::STATUS_UNAPPROVED) : ?>
            <em>Your comment is awaiting moderation.</em>
        <?php endif; ?>

        <?php echo $comment->content_out ?>

       <div class="commentMeta">
        posted by <cite><?php echo $comment_url ?></cite> on <a href="#comment-<?php echo $comment->id ?>" title=""><?php $comment->date->out('m.d.y') ?></a> at <?php $comment->date->out('h:m a') ?>
      </div>
     </div>
    </div>

    <?php endforeach; /* end for each comment */ ?>
<?php endif; ?>

<?php if ($post->info->comments_disabled): ?>
        <!-- If comments are closed. -->
        <div class="nocomments">Comments are closed.</div>
<?php endif ?>


<?php if (!$post->info->comments_disabled): ?>

<form action="<?php URL::out('submit_feedback', array('id' => $post->id)) ?>" method="post" id="commentform">


  <div class="leaveComment">
    <?php if ($loggedin) : ?>

    <p class="loggedin">Logged in as <a href="<?php URL::out('user_profile', array('page' => 'user', 'user' => $user->username)) ?>"><?php echo $user->username ?></a>. <a href="<?php URL::out('user', 'page=logout') ?>" title="Log out of this account">Logout &raquo;</a></p>

    <?php endif; ?>

    <fieldset>
      <legend><span>Leave a Comment</span></legend>
      <div class="commentForm">
        <label>Name: <em><?php if (Options::get('comments_require_id')): ?>Required<?php endif ?></em> <input type="text" name="name" id="name" value="<?php echo $commenter_name ?>" /></label>
        <label>Email: <em><?php if (Options::get('comments_require_id')): ?>Required, not published<?php else: ?>Not published<?php endif ?></em> <input type="text" name="email" id="email" value="<?php echo $commenter_email ?>" /></label>
        <label>Homepage: <input type="text" name="url" id="url" value="<?php echo $commenter_url ?>" /></label>
        <label>Comment:
        <textarea name="content" id="content" cols="50" rows="20"></textarea></label>
        <input type="submit" value="Post Comment" />
      </div>
    </fieldset>
  </div>

</form>

<?php endif; // if you delete this the sky will fall on your head ?>
</div>
