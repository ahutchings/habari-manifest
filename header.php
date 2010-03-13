<?php defined('HABARI_PATH') or die('No direct script access.') ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title><?php $theme->title(':', true, 'right'); ?> <?php Options::out('title') ?></title>

<!--[if gte IE 7]><!-->
  <link rel="stylesheet" href="<?php echo Site::out_url('theme') ?>/style.css" type="text/css" media="screen" charset="utf-8" />
<!-- <![endif]-->

<!--[if IE 7]>
  <link rel="stylesheet" href="<?php echo Site::out_url('theme') ?>/style_ie.css" type="text/css" media="screen" charset="utf-8" />
<![endif]-->

<!--[if IE 6]>
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo Site::out_url('theme') ?>/styles_ie6.css" />
<![endif]-->

  <link rel="alternate" type="application/atom+xml" title="<?php Options::out('title') ?> Atom Feed" href="<?php $theme->feed_alternate() ?>" />
  <?php $theme->header() ?>
</head>

<body>


<div id="siteWrapper">

  <h1 class="vcard author"><a href="<?php Site::out_url( 'habari' ); ?>/" title="Home" class="fn"><?php Options::out('title') ?></a></h1>

  <div id="mainNav">
    <ul>
      <?php $theme->list_pages() ?>
    </ul>
  </div>

  <div id="siteDescription">
    <?php Options::out('tagline') ?>
  </div>
