<?php 

function getImgsUrl($product, $id)
{
    $imgsIDs = $product->get_gallery_image_ids();
    $imgURL[] = wp_get_attachment_image_src(get_post_thumbnail_id($id), 'full')[0];
    foreach ($imgsIDs as $imgId) {
        $imgURL[] = wp_get_attachment_image_src($imgId, 'full')[0];
    }
    return $imgURL;
}