<?php
/**
 * ودجت أحدث الحلقات للشريط الجانبي - نقابة Soul Reapers
 */

if (!defined('ABSPATH')) die();

class AnimWP_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'animwp_widget',
            '🔥 أحدث الحلقات (نقابة Soul Reapers)', 
            array(
                'description' => 'يعرض قائمة بأحدث الحلقات المنشورة مع الصور في الشريط الجانبي'
            )
        );
    }

    // طريقة عرض الودجت في الموقع للزوار
    public function widget($args, $instance) {
        $num_posts = !empty($instance['entradas']) ? absint($instance['entradas']) : 5;
        $title     = !empty($instance['title']) ? $instance['title'] : 'حلقات اليوم';

        echo $args['before_widget'];
        ?>
            <h4 class="text-white widget-custom-title" style="border-right: 3px solid var(--Primario); padding-right: 10px; margin-bottom: 15px;">
                <?php echo esc_html($title); ?>
            </h4>
            
            <ul class="sidebar-episodes-list">
                <?php
                    // تم إصلاح الخطأ الإملائي: posts_per_page
                    $query_args = array(
                        'post_type'      => 'animwp_capitulos',
                        'posts_per_page' => $num_posts,
                        'post_status'    => 'publish'
                    );

                    $entradas = new WP_Query($query_args);
                    if ($entradas->have_posts()):
                        while ($entradas->have_posts()): $entradas->the_post();
                            $cover = function_exists('get_field') ? get_field('cover') : '';
                            if (empty($cover) && has_post_thumbnail()) {
                                $cover = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
                            }
                ?>
                    <li style="margin-bottom: 10px;">
                        <!-- تم إضافة الرابط ليتمكن الزائر من الدخول للحلقة -->
                        <a href="<?php the_permalink(); ?>" class="card-entrada" style="display: flex; align-items: center; gap: 12px; background: var(--gris-oscuro); padding: 8px; border-radius: 6px; text-decoration: none; border: 1px solid var(--gris-claro);">
                            <div class="imagen_sidebar" style="width: 50px; height: 50px; flex-shrink: 0; border-radius: 4px; overflow: hidden; background: #000;">
                                <?php if (!empty($cover)): ?>
                                    <img src="<?php echo esc_url($cover); ?>" alt="<?php the_title_attribute(); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <div style="display: flex; align-items: center; justify-content: center; height: 100%; font-size: 10px; color: #777;">حلقة</div>
                                <?php endif; ?>
                            </div>
                            <h5 class="text-white" style="margin: 0; font-size: 13px; font-weight: 600; line-height: 1.4;">
                                <?php the_title(); ?>
                            </h5>
                        </a>
                    </li>
                <?php
                        endwhile;
                        wp_reset_postdata();
                    else:
                ?>
                    <li style="color: #888; font-size: 12px;">لا توجد حلقات معروضة حالياً.</li>
                <?php endif; ?>
            </ul>
        <?php
        echo $args['after_widget'];
    }

    // نموذج إعدادات الودجت داخل لوحة التحكم
    public function form($instance) {
        $title    = !empty($instance['title']) ? $instance['title'] : 'حلقات اليوم';
        $entradas = !empty($instance['entradas']) ? absint($instance['entradas']) : 5;
        ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">عنوان الودجت:</label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('entradas')); ?>">عدد الحلقات المراد عرضها:</label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('entradas')); ?>" name="<?php echo esc_attr($this->get_field_name('entradas')); ?>" type="number" min="1" max="20" value="<?php echo esc_attr($entradas); ?>">
            </p>
        <?php
    }

    // حفظ التعديلات
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title']    = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['entradas'] = (!empty($new_instance['entradas'])) ? absint($new_instance['entradas']) : 5;
        return $instance;
    }
} 

function animwp_registrar_widget() {
    register_widget('AnimWP_Widget');
}
add_action('widgets_init', 'animwp_registrar_widget');
