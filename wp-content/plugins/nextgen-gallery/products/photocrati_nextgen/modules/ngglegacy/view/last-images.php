<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?><?php if (!empty ($gallery)) : ?>
<!-- Thumbnails -->
 <ul class="galery">
<?php foreach ( $images as $image ) : ?>
	<li><a class="gallery" rel="group" href="<?php echo $image->imageURL ?>"><img title="<?php echo $image->alttext ?>" alt="<?php echo $image->alttext ?>" src="<?php echo $image->imageURL ?>" /></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>