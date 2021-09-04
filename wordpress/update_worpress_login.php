<?php

// V 20191228
// <1>
// /var/www/wordpress/wp-content/themes/puca/page-templates/themes/fashion/parts/offcanvas-menu.php
// <div class="wmlogin" style="margin-top:2rem;padding-left:1.5rem;"> xxx </div>
<style>
.wmlogin {
    display: flex;
    justify-content: space-around;
    align-items: center;
    height: 5rem;
    padding-left: 1.5rem;
    background: #ef92ef;
}
.wm-item {
    border: 1px solid #fff;
    border-radius: 2px;
    padding: 0.4rem 2rem;
}
.wm-item a {
    color: #fff;
}
</style>
$woo_sp_forms_login_title = get_option('woo_sp_forms_login_title');
$woo_sp_forms_logout_title = get_option('woo_sp_forms_logout_title');
$woo_sp_forms_reg_title = get_option('woo_sp_forms_reg_title');
$woo_sp_forms_ma_title = get_option('woo_sp_forms_ma_title');
$woo_sp_forms_login_link = get_option('woo_sp_forms_login_link');
$woo_sp_forms_reg_link = get_option('woo_sp_forms_reg_link');
$woo_sp_forms_ma_link = get_option('woo_sp_forms_ma_link');

$str = '';

if (is_user_logged_in()) {

    if ($woo_sp_forms_ma_link == 'y') {

        $str .= '<div class="wm-item"><a href="'.get_permalink(get_option('woocommerce_myaccount_page_id')).'">'. __($woo_sp_forms_ma_title, 'woocommerce') .'</a></div>';
    }
    if ($woo_sp_forms_login_link == 'y') {
        
        $str .= '<div class="wm-item"><a href="'.wp_logout_url(home_url()).'">'. __($woo_sp_forms_logout_title, 'woocommerce') .'</a></div>';
    }
} else {

    if ($woo_sp_forms_login_link == 'y') {

        $str .= '<div class="wm-item"><a href="#" id="woo_sp_login">'.__($woo_sp_forms_login_title, 'woocommerce').'</a></div>';
    }
    if ($woo_sp_forms_reg_link == 'y') {

        $str .= '<div class="wm-item"><a href="#" id="woo_sp_sign_up">'.__($woo_sp_forms_reg_title, 'woocommerce').'</a></div>';
    }
}

echo $str;

// <2>
// /var/www/wordpress/wp-content/plugins/woocommerce-social-login/templates/global/social-login.php
<div class="wc-social-login form-row-wide" style="display: flex; flex-direction: column;">
<div class="wm-social-login">
    <?php foreach ( $providers as $provider ) : ?>
        <?php printf( '<a href="%1$s" class="button-social-login button-social-login-%2$s"><span class="si si-%2$s"></span>%3$s</a> ', esc_url( $provider->get_auth_url( $return_url ) ), esc_attr( $provider->get_id() ), esc_html( $provider->get_login_button_text() ) ); ?>
    <?php endforeach; ?>
</div>

// <3>
// /var/www/wordpress/wp-content/plugins/woocommerce-social-login/includes/frontend/class-wc-social-login-frontend.php 177
woocommerce_social_login_buttons('');return;

// <4>
// /var/www/wordpress/wp-content/plugins/woo-sp-forms/templates
/login-form.php
<style>.wm-social-login a { color: #fff !important;}</style>
/registration-form.php line 58
// do_action( 'woocommerce_register_form_end' ); 
do_action( 'woocommerce_login_form_end' );
