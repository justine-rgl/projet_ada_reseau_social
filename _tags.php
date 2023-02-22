<?php 
    // split des tags en fonction des virgules
    $explodeTag = explode(",", $post['taglist']);
    $explodeTagId = explode(",", $post['tagidlist']);

    // ?
    while ($explodeTag) {
        $currentTag = array_pop($explodeTag);
        $currentTagId = array_shift($explodeTagId)
?>
    <a href="tags.php?tag_id=<?php echo $currentTagId ?>">#<?php echo $currentTag ?></a>
    <?php } ?>