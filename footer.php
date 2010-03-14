<?php defined('HABARI_PATH') or die('No direct script access.') ?>
</div>

<div id="footer">

  <!-- Footer Links -->

  <h5>Elsewhere</h5>
  <ul class="elsewhere">
    <li><a href="#">Facebook</a></li>
    <li><a href="#">Flickr</a></li>
    <li><a href="#">Last.fm</a></li>
    <li><a href="#">Deli.icio.us</a></li>
    <li><a href="#">Linkedin</a></li>
    <li><a href="#">Twitter</a></li>
    <li class="last"><a href="#">Vimeo</a></li>
  </ul>

  <!-- Search Field -->

  <div class="footerContent">
    <?php Plugins::act('theme_searchform_before') ?>
    <form method="get" id="searchform" action="<?php URL::out('display_search') ?>">
      <div id="search">
        <input type="text" value="<?php if (isset($criteria)) { echo htmlentities($criteria, ENT_COMPAT, 'UTF-8'); } ?>" name="criteria" id="criteria" />
        <input type="submit" id="searchsubmit" value="Search" />
      </div>
    </form>
    <?php Plugins::act('theme_searchform_after') ?>

    <p>&copy; <?php Options::out('title') ?>. Powered by <a href="http://habariproject.org/">Habari</a> and <a href="http://jimbarraud.com/manifest/">Manifest</a></p>
  </div>
</div>

<?php echo $theme->footer() ?>

</body>
</html>
