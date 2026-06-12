<?php
/**
 * Template part for displaying posts in a card format
 *
 * @package Eghtesadran
 */
?>
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden group hover:border-primary/50 transition-colors">
    <a href="<?php the_permalink(); ?>" class="block relative aspect-[16/10] overflow-hidden">
        <?php if ( has_post_thumbnail() ) : ?>
            <img src="<?php echo esc_url( get_the_post_thumbnail_url( null, 'medium_large' ) ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
        <?php else : ?>
            <div class="w-full h-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center transition-transform duration-700 group-hover:scale-105">
                <i data-lucide="image" class="w-10 h-10 text-slate-300 dark:text-slate-500"></i>
            </div>
        <?php endif; ?>
    </a>
    <div class="p-5">
        <div class="flex items-center mb-3 text-[10px] font-bold text-slate-400 dark:text-slate-500">
            <span class="flex items-center gap-1"><i data-lucide="clock" class="w-3 h-3"></i> <?php echo get_the_date(); ?></span>
        </div>
        <h3 class="text-base font-extrabold text-slate-800 dark:text-slate-200 group-hover:text-primary dark:group-hover:text-red-400 transition-colors mb-3 leading-snug">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed text-justify line-clamp-3">
            <?php echo wp_trim_words( get_the_excerpt(), 25 ); ?>
        </p>
    </div>
</div>
