<?php //This table can be copied over into the final html ?>

<?php foreach($pictures as $picture): ?>
  <img src="<?php echo site_url() . 'images/' . $picture->pic_location; ?>" />
<?php endforeach; ?>
