<?php $explodeTag = explode(",", $post['taglist']) ?>
<?php $explodeTagId = explode(",", $post['tagidlist']) ?>

<?php while ($explodeTag) {
    $currentTag = array_pop($explodeTag);
    $currentTagId = array_shift($explodeTagId) ?>
<a href="tags.php?tag_id=<?php echo $currentTagId ?>">#<?php echo $currentTag ?></a>
<?php } ?>