<?php

/**
 * The About Page
 * 
 * @since 1.0
 */

get_header();
?>
<article class="the-content py-5">
    <?php get_template_part('templates/pages/page', $post->post_name); ?>
</article>
<?php get_footer() ?>