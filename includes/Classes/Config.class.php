<?php
namespace SidebarMenu\Classes;

class Config{
    
    public static function get_front_scripts() {

        $min_suffix = WP_DEBUG ? '' : '';
        $return = [
                'styles'=>  [
                    'sidebarmenu-main' => [SIDEBAR_MENU_DIR_URL.'assets/css/main'.$min_suffix.'.css', '', SIDEBAR_MENU_VER, false ],
                    'font-awesome-6-all' => [SIDEBAR_MENU_DIR_URL.'assets/font-awesome/css/all'.$min_suffix.'.css', '', SIDEBAR_MENU_VER, false ],
                    'font-awesome-4-shims' => [SIDEBAR_MENU_DIR_URL.'assets/font-awesome/css/v4-shims'.$min_suffix.'.css', '', SIDEBAR_MENU_VER, false ],
                ],
                'scripts' => [
                    'sidebarmenu-main' => [SIDEBAR_MENU_DIR_URL.'assets/js/main'.$min_suffix.'.js', array( 'jquery'), SIDEBAR_MENU_VER, false],
                    'sidebarmenu-side-icon' => [SIDEBAR_MENU_DIR_URL.'assets/js/side-icon'.$min_suffix.'.js', array( 'jquery'), SIDEBAR_MENU_VER, false],
                ]
        ];
        return $return;
    }

    public static function get_admin_scripts() {

        $min_suffix = WP_DEBUG ? '' : '';
        $return = [
                'scripts' => [
                    'sidebarmenu-admin' => [SIDEBAR_MENU_DIR_URL.'assets/js/admin'.$min_suffix.'.js', array( 'wp-color-picker'), SIDEBAR_MENU_VER, false],
                ]
        ];
        return $return;
    }
}