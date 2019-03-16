<?php
/*
 * Plugin Name: ZeroPad
 * Plugin URI: https://github.com/miyabi-satoh/ZeroPad
 * Description: Zero-padded when only %post_id% is used for permalink.
 * Version: 20190316
 * Author: miyabi-satoh
 * Author URI: https://github.com/miyabi-satoh
 * License: GPLv3
 *
 *  ZeroPad - Zero-padded when only %post_id% is used for permalink.
 *  Copyright 2019 miyabi-satoh
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

define('ZEROPAD_FMT', '%04d');

function zeropad_add( $url, $post, $leavename=false ) {
  $permalink = get_option( 'permalink_structure' );
  if ( strpos( str_replace( '%post_id%', '', $permalink), '%' ) !== false ) {
    return $url;
  }
  if ( is_object($post) ) {
    if ( $post->post_type == 'post') {
      $new_url = home_url( str_replace( '%post_id%', sprintf(ZEROPAD_FMT, $post->ID), $permalink) );
      // error_log("[$post->ID]:$url");
      // error_log("[$post->ID]:$permalink");
      // error_log("[$post->ID]:$new_url".PHP_EOL);
      return $new_url;
    }
    else {
      return $url;
    }
  }
  else if ( $post ) {
    $new_url = home_url( str_replace( '%post_id%', sprintf(ZEROPAD_FMT, $post), $permalink) );
    // error_log("[$post]:$url");
    // error_log("[$post]:$permalink");
    // error_log("[$post]:$new_url".PHP_EOL);
    return $new_url;
  }
  // error_log("[$post]/[$url]".PHP_EOL);
  return $url;
}

function zeropad_remove( $post_rewrite ) {
  $new_rules = array();
  foreach ($post_rewrite as $key => $value) {
    if ( strpos( $value, 'p=$matches[1]' ) !== false) {
        $new_rules[str_replace( '([0-9]+)', '0+([0-9]+)', $key )] = $value;
    }
  }
  return $new_rules + $post_rewrite;
}

add_filter( 'the_permalink', 'zeropad_add', 10, 2);
add_filter( 'post_link', 'zeropad_add', 10, 3);
add_filter( 'post_rewrite_rules', 'zeropad_remove');
