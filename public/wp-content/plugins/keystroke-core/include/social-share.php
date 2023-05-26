<?php
/*
***************************************************************
*  Social sharing icons
***************************************************************
*/

if ( ! function_exists('axil_sharing_icon_links') ) {
 function axil_sharing_icon_links( ) {

  global $post;
  $axil_options = Helper::axil_get_options();

  $html = '<div class="blog-share d-flex flex-wrap align-items-center mb--80">';
  $html .= '<span class="text">'. esc_html($axil_options['axil_blog_details_social_share_label']) .'</span>';
  $html .= '<ul class="social-share d-flex">';

   // facebook
   $facebook_url = 'https://www.facebook.com/sharer/sharer.php?u='. get_the_permalink();
   $html .= '<li><a href="'. esc_url( $facebook_url ) .'" target="_blank" class="aw-facebook"><i class="fab fa-facebook-f"></i> '.esc_html__("Facebook","keystroke").'</a></li>';

   // twitter
   $twitter_url = 'https://twitter.com/share?'. esc_url(get_permalink()) .'&amp;text='. get_the_title();
   $html .= '<li><a href="'. esc_url( $twitter_url ) .'" target="_blank" class="aw-twitter"><i class="fab fa-twitter"></i> '.esc_html__("Twitter","keystroke").'</a></li>';

   // linkedin
   $linkedin_url = 'http://www.linkedin.com/shareArticle?url='. esc_url(get_permalink()) .'&amp;title='. get_the_title();
   $html .= '<li><a href="'. esc_url( $linkedin_url ) .'" target="_blank" class="aw-linkdin"><i class="fab fa-linkedin-in"></i> '.esc_html__("Linkedin","keystroke").'</a></li>';

  $html .= '</ul></div>';

  echo wp_kses_post($html);

 }
}
