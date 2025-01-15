<?php
/**
 * Menu Walker Class
 *
 * @package GusviraDigital
 * @version 1.0.0
 */

namespace GDP\Navigation;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Menu Walker Class
 */
class Menu_Walker extends \Walker_Nav_Menu {
    /**
     * Starts the list before the elements are added.
     */
    public function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $submenu = ($depth > 0) ? ' sub-menu' : '';
        $classes = ['relative', 'group-hover:block', ($depth === 0 ? 'hidden' : '')];
        
        if ($depth === 0) {
            $output .= "\n$indent<div class=\"absolute left-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 hidden group-hover:block\">\n";
            $output .= "\n$indent<ul class=\"py-1\">\n";
        } else {
            $output .= "\n$indent<ul class=\"ml-4\">\n";
        }
    }

    /**
     * Ends the list of after the elements are added.
     */
    public function end_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        if ($depth === 0) {
            $output .= "$indent</ul>\n";
            $output .= "$indent</div>\n";
        } else {
            $output .= "$indent</ul>\n";
        }
    }

    /**
     * Start the element output.
     */
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        
        // Add Tailwind classes based on depth
        if ($depth === 0) {
            $classes[] = 'group relative';
        }
        
        // Add active/current classes
        if (in_array('current-menu-item', $classes)) {
            $classes[] = 'text-primary-500 dark:text-primary-400';
        } else {
            $classes[] = 'text-gray-600 dark:text-gray-300';
        }
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $output .= $indent . '<li' . $class_names . '>';
        
        $atts = array();
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = !empty($item->url) ? $item->url : '';
        
        // Add Tailwind classes to link
        $link_classes = [];
        if ($depth === 0) {
            $link_classes[] = 'inline-flex items-center px-1 pt-1 text-sm font-medium hover:text-primary-500 dark:hover:text-primary-400';
        } else {
            $link_classes[] = 'block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700';
        }
        $atts['class'] = implode(' ', $link_classes);
        
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);
        
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        
        $title = apply_filters('the_title', $item->title, $item->ID);
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);
        
        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . $title . $args->link_after;
        
        // Add dropdown arrow for items with children
        if ($args->walker->has_children && $depth === 0) {
            $item_output .= '<svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>';
        }
        
        $item_output .= '</a>';
        $item_output .= $args->after;
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}