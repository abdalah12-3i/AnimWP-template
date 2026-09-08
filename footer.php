<footer class="footer">
    <div class="contenedor navegacion">
        <!-- روابط القائمة السفلية -->
        <div class="enlaces">
            <?php
                $args = array(
                    'theme_location'  => 'main-menu',
                    'container'       => 'nav',
                    'container_class' => 'menu'
                );
                wp_nav_menu($args);
            ?>
        </div>

        <!-- حقوق الموقع -->
        <div class="copyright" style="text-align: center; margin-top: 15px; font-size: 14px; opacity: 0.8;">
            <p>جميع الحقوق محفوظة &copy; <?php echo date('Y'); ?> <a href="<?php echo esc_url(home_url('/')); ?>" style="color: inherit; text-decoration: none; font-weight: bold;"><?php bloginfo('name'); ?></a></p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
