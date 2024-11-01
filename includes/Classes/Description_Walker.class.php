<?php
namespace SidebarMenu\Classes;
class Description_Walker extends \Walker_Nav_Menu
{

    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 )
    {
        $classes     = empty ( $item->classes ) ? array () : (array) $item->classes;
        $class_names = join(
            ' '
        ,   apply_filters(
                'nav_menu_css_class'
            ,   array_filter( $classes ), $item
            )
        );
        ! empty ( $class_names )
            and $class_names = ' class="'. esc_attr( $class_names ) . '"';
        $output .= "<li id='menu-item-$item->ID' $class_names>";
        $attributes  = '';
        ! empty( $item->attr_title )
            and $attributes .= ' title="'  . esc_attr( $item->attr_title ) .'"';
        ! empty( $item->target )
            and $attributes .= ' target="' . esc_attr( $item->target     ) .'"';
        ! empty( $item->xfn )
            and $attributes .= ' rel="'    . esc_attr( $item->xfn        ) .'"';
        ! empty( $item->url )
            and $attributes .= ' href="'   . esc_attr( $item->url        ) .'" rel="nofollow noreferrer noopener"';

        $description = ( ! empty ( $item->description ) and 0 == $depth )
            ? '<small class="nav_desc">' . esc_attr( $item->description ) . '</small>' : '';
        $title = apply_filters( 'the_title', $item->title, $item->ID );
        $item_output = $args->before
            . "<a $attributes>"
            . $args->link_before
            . $title
            . $args->link_after
            . $description
            . '</a> '
            . $args->after;
            
        $output .= apply_filters(
            'walker_nav_menu_start_el'
        ,   $item_output
        ,   $item
        ,   $depth
        ,   $args
        );
    }
}