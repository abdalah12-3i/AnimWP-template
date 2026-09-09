<?php 
/**
 * قالب الصفحات العامة - نقابة Soul Reapers
 */
get_header(); 
?>

<main class="contenedor seccion">
    <?php while (have_posts()) : the_post(); ?>
        <article class="page-content-box" style="background: var(--gris-medio); border: 1px solid var(--gris-claro); padding: 3rem; border-radius: 0.8rem;">
            <h1 style="color: var(--Primario); margin-bottom: 2rem; border-bottom: 2px solid var(--gris-claro); padding-bottom: 1rem;">
                <?php the_title(); ?>
            </h1>
            
            <div class="entry-content" style="color: #ddd; font-size: 1.5rem; line-height: 2;">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
