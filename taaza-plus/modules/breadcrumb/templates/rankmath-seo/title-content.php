<section class="main-title-section-wrapper <?php echo esc_attr( $wrapper_classes );?>">
    <div class="main-title-section-container">
        <div class="container">
            <div class="main-title-section"><?php echo taaza_breadcrumb_title();?></div>
            <?php echo taaza_breadcrumbs( array( 'text' => $home, 'link' => $home_link ), $delimiter );?>
        </div>
    </div>
    <div class="main-title-section-bg"></div>
</section>