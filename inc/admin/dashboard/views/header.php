<div class="wrap">
    <div class="about-wrap">
        <h1>Welcome to Basel dashboard</h1>

        <div class="about-text">
            
            Thank you for purchasing our premium eCommerce theme - Basel. Here you are able
            to start creating your awesome web store by importing our dummy content and theme options.

        </div>

        <div class="basel-theme-badge">
            <img src="<?php echo BASEL_ASSETS_IMAGES; ?>/basel-badge.jpg">
            <span><?php
                $theme_version = explode( '.', basel_get_theme_info( 'Version' ) );
                echo esc_html( $theme_version[0] . '.' . $theme_version[1] );
            ?></span> 
        </div>

        <p class="redux-actions">
            <a href="https://xtemos.com/documentation/basel/" target="_blank" class="btn-docs button">Docs</a>
            <a href="https://www.youtube.com/playlist?list=PLMw6W4rAaOgKl9369klNEnO85oAwLiLxM" target="_blank" class="btn-videos button">Video tutorials</a>
            <a href="https://themeforest.net/downloads" class="btn-rate button" target="_blank">Rate our theme</a>
            <a href="https://xtemos.com/forums/forum/basel-premium-template/" class="btn-support button button-primary" target="_blank">Support forum</a>
        </p>
    </div>

    <div class="basel-wrap-content">
                            
        <h2 class="nav-tab-wrapper">
            <?php foreach ($this->get_tabs() as $tab => $title): ?>
                <a class="nav-tab <?php if( $this->get_current_tab() == $tab ) echo 'nav-tab-active'; ?>" href="<?php echo esc_url( $this->tab_url( $tab ) ); ?>"><?php echo esc_html( $title ); ?></a> 
            <?php endforeach ?>
        </h2>

    
