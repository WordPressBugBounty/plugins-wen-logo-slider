<?php

/**
* The file that defines shortcode
*
* @link       http://wensolutions.com
* @since      1.0.0
*
* @package    WEN_Logo_Slider
* @subpackage WEN_Logo_Slider/includes
*/

/**
* Shortcode class.
*
* This class contains shortcode stuff.
*
* @since      1.0.0
* @package    WEN_Logo_Slider
* @subpackage WEN_Logo_Slider/includes
* @author     WEN Solutions <info@wensolutions.com>
*/
class WEN_Logo_Slider_Shortcode {

  public function init() {

    add_shortcode( 'WLS', array( $this, 'wen_logo_slider_shortcode_callback' ) );

  }

  private function check_if_valid_slider($args){

    $output = false;
    if ( isset($args['id']) && intval( $args['id'] ) > 0  ) {

      $slider = get_post(intval($args['id']));

      if ( ! empty( $slider ) && WEN_LOGO_SLIDER_POST_TYPE_LOGO_SLIDER == $slider->post_type ) {
        $output = true;
      }
    }
    return $output;
  }

  function wen_logo_slider_shortcode_callback( $atts, $content = "" ){

    $atts = shortcode_atts( array(
      'id' => '',
      ), $atts, 'WLS' );

    $atts['id'] = absint($atts['id']);
    $is_valid_slider = $this->check_if_valid_slider($atts);
    if ( ! $is_valid_slider ) {
      return __( 'Slider not found', 'wen-logo-slider' );
    }
    ob_start();
    ?>

    <?php
    $slides = get_post_meta($atts['id'],'_wls_slides',true);
    ?>
    <?php if ( ! empty( $slides ) ): ?>

      <?php
      $slider_settings = get_post_meta($atts['id'],'wen_logo_slider_settings',true);
      $slider_settings['random_id'] = uniqid(esc_attr($atts['id']).'-');
      ?>
      <div class="wls-wrap">
      <span class="arrow-img" style="display:none"><?php echo (isset($settings['navigation_type']))? esc_html( $settings['navigation_type'] ):'arrows' ?></span>
      <?php if(isset($slider_settings['show_title']) && $slider_settings['show_title'] === '1' && isset($slider_settings['heading_size'])): ?>      
        <<?php echo esc_attr( $slider_settings['heading_size'] ); ?> class="wen-logo-slider-title"><?php echo get_post(esc_attr($atts['id']))->post_title; ?></<?php echo esc_attr( $slider_settings['heading_size'] ); ?>>
      <?php endif; ?>
      <?php do_action( 'wen_logo_slider_before_slides', $atts['id'] ); ?>
      <div id="<?php echo esc_attr( $slider_settings['random_id'] ); ?>" class="<?php echo esc_attr( apply_filters( 'wen_logo_slider_custom_class', '', $atts['id'] ) ); ?>"  <?php if(isset($slider_settings['direction'])){echo ($slider_settings['direction'] == 1)?'dir="rtl"':"";} ?>>
        

        <?php foreach ($slides as $key => $slide):
          if (empty($slide['slide_image_id'])):
           continue; endif; 
          $attachment = get_post($slide['slide_image_id']);
          if ( empty( $attachment ) ) {
            continue;
          }
          
          $image_size = (isset($slider_settings['image_size']))?$slider_settings['image_size']:'thumbnail';
          $image_info = wp_get_attachment_image_src( $attachment->ID, $image_size );
          $image_url  = array_shift($image_info);
          $link_open  = '';
          $link_close = '';
          if ( ! empty( $slide['url'] ) ) {
            $link_open = '<a href="'.esc_url( $slide['url'] ).'"';
            if ( 'yes' == $slide['slide_new_window'] ) {
              $link_open .= ' target="_blank" ';
            }
            if ( ! empty( $slide['title'] ) ) {
              $link_open .= ' title="' . esc_attr( $slide['title'] ) . '" ';
            }
            $link_open .='>';
            $link_close = '</a>';
          }

          ?>
          
          <div class="panel" > 
            <?php echo $link_open; ?>
         
            <img <?php  if(isset($slider_settings['lazy_load']) && $slider_settings['lazy_load'] === '1'){ ?> data-lazy="<?php echo esc_url( $image_url ); ?>" <?php } else{ ?> src="<?php echo esc_url( $image_url ); ?>" <?php } ?>alt="<?php echo esc_attr( $slide['title'] ); ?>" title="<?php echo esc_attr( $slide['title'] ); ?>" />
            <?php echo $link_close; ?>
            <?php if(isset($slide['title']) && $slide['title'] != ""): ?>
            <div class="ws-caption">
              <h4><?php echo esc_html( $slide['title'] ); ?></h4>
            </div>
            <?php endif; ?>
          </div>

          <?php $image_url_list[] = esc_url( $image_url ); ?>
         <?php endforeach; ?>
       </div> <!-- Slides end -->

       <?php do_action( 'wen_logo_slider_after_slides', $atts['id'] ); ?>

       </div>
       <?php echo $this->get_slider_script( $atts, $slider_settings, $image_url_list );
      endif;
      $output = ob_get_contents();
      ob_end_clean();
      return $output;
  }

  function get_slider_script( $args, $settings,$image_url_list ){
    $arrow_prev_pos = '0 0';
    $arrow_next_pos = '30px 0';
    if(isset($settings['navigation_type'])):
      $nav_type = $settings['navigation_type'];
      $bg_path = WEN_LOGO_SLIDER_URL.'/admin/images/nav/';

      //$bg_css = "background: url('".$bg_path.$nav_type.".png');";
      $background_img = $bg_path.$nav_type.".png";

      
      if (isset($settings['direction']) && $settings['direction'] === '1') { // Right to Left
        $arrow_prev_pos = '30px 0';
        $arrow_next_pos = ' 0 0';
      }
    endif;
    
    ob_start();
    ?>
    <style type="text/css">
    <?php if(isset($settings['enable_navigation_arrow']) && $settings['enable_navigation_arrow'] === '1'): ?>
        .wls-wrap{padding: 0 15px}
    <?php endif; ?> 

    </style>    
    <script type="text/javascript">
      jQuery( document ).ready(function($) {
        document.addEventListener("touchstart", function(){}, true);
        jQuery.fn.randomize = function (selector) {
          var $elems = selector ? $(this).find(selector) : $(this).children(),
              $parents = $elems.parent();

          $parents.each(function () {
              $(this).children(selector).sort(function (childA, childB) {
                  // * Prevent last slide from being reordered
                  if($(childB).index() !== $(this).children(selector).length - 1) {
                      return Math.round(Math.random()) - 0.5;
                  }
              }.bind(this)).detach().appendTo(this);
          });

          return this;
        };

        var randomId = "<?php echo $random = $settings['random_id']; ?>";
        $("#"+randomId).on('init', function(event, slick) {
            $('.wls-wrap').show();
        });
        var logoSlider = $("#"+randomId);
        <?php if(isset($settings['enable_random_order']) && $settings['enable_random_order'] === '1'): ?>   
          logoSlider =  $("#"+randomId).randomize();   
        <?php endif; ?>

        var slides = (logoSlider[0].innerHTML);
        //slides = slides.replace(/\s/g,"");
        slides = slides.replace(/(\r\n|\n|\r)/gm,"");
        var lazy_load = "<?php echo (isset($settings['lazy_load']) && $settings['lazy_load'] === '1') ? 1 : 0 ?>";
        logoSlider.slick({ 
        slidesToShow: <?php  echo esc_attr( $settings['images_per_slide'] ); ?>,
        slidesToScroll: 1,
        
        <?php echo (isset($settings['scroll']) && $settings['scroll'] ==='True')? 'infinite: true,':'infinite: false,';  ?>

        pauseOnHover: <?php echo (isset($settings['hover']) && $settings['hover'] === '1')? 'true':'false'; ?>,
        variableWidth: <?php echo esc_attr( apply_filters( 'wen_logo_sider_enable_variable_width',  'false',  $args['id'] ) ); ?>,
        autoplaySpeed: <?php echo esc_attr($settings['slider_delay'] ) * 1000 ; ?>,
        speed: <?php echo esc_attr($settings['transition_time']) * 1000 ; ?>,

        <?php echo (isset($settings['lazy_load']) && $settings['lazy_load'] === '1')? "lazyLoad: 'ondemand',":""; ?>
        
        rtl: <?php echo (isset($settings['direction']) && $settings['direction'] === '1')? 'true':'false'; ?>,

        <?php if ( isset($settings['enable_navigation_arrow'] ) && $settings['enable_navigation_arrow'] === '1' ): ?>
          <?php if(isset($settings['hide_nav_arrow_mob']) && $settings['hide_nav_arrow_mob'] == '1'): ?>
              <?php if ( wp_is_mobile() ) : ?>
                <?php $navigation_arrow = false ?>
                arrows: false,
              <?php else: ?>
                <?php $navigation_arrow = true ?>
                arrows: true,
              <?php endif; ?>
          <?php else: ?>
            <?php $navigation_arrow = true ?>
            arrows: true,
          <?php endif; ?>
        <?php else: ?>
          <?php $navigation_arrow = false ?>
          arrows: false,
        <?php endif; ?>
        
        asNavFor: null,
        <?php if( isset( $settings['navigation_type'] ) && $navigation_arrow ): ?>
        prevArrow: '<button type="button" data-role="none" class="slick-prev" aria-label="Previous" tabindex="0" role="button" style="background-position:'+"<?php echo esc_attr( $arrow_prev_pos ); ?>"+'; background-image:url('+ "<?php echo esc_url( WEN_LOGO_SLIDER_URL ).'/admin/images/nav/'.esc_attr( $settings['navigation_type'] ). '.png'; ?>" +');margin:0;padding:0">Previous</button>',
        nextArrow: '<button type="button" data-role="none" class="slick-next" aria-label="Next" tabindex="0" role="button" style="background-position:'+"<?php echo esc_attr( $arrow_next_pos ); ?>"+'; background-image:url('+ "<?php echo esc_url( WEN_LOGO_SLIDER_URL ).'/admin/images/nav/'.esc_attr( $settings['navigation_type'] ). '.png'; ?>" +');margin:0;padding:0">Next</button>',
        <?php endif; ?>
          
        autoplay: <?php if(isset($settings['auto_play']))echo ($settings['auto_play'] === '1')? 'true':'false';else echo 'true' ?>,
        draggable: <?php echo (isset($settings['mouse_dragging']) && $settings['mouse_dragging'] === '1')? 'true':'false'; ?>,
        centerMode: <?php echo (isset($settings['center_mode']) && $settings['center_mode'] === '1')? 'true':'false'; ?>,
        centerPadding: '<?php echo esc_attr( apply_filters( 'wen_logo_sider_center_padding',  '0px', $args['id'] ) ); ?>',
        useCSS: true,
        swipeToSlide: true,
        dots:  <?php echo (isset($settings['pagination']) && $settings['pagination'] === '1')? 'true':'false'; ?>,
        <?php if(isset($settings['pagination']) && $settings['pagination'] === '1'): ?>
        customPaging: function(slider, i) {

          var pagintionType = '<?php echo (isset($settings['pagination_type']))?esc_attr( $settings['pagination_type'] ):""; ?>';          
          
          <?php if(isset($settings['pagination_type']) && $settings['pagination_type'] == 'thumb'): ?>
          
            var image_url_list = new Array();
            var regex = /<img.*?src="(.*?)"/g;
            if (lazy_load == '1') {                          
              regex = /<img.*?data-lazy="(.*?)"/g;
            }
            
            var m;
            while (m = regex.exec(slides)) {
              image_url_list.push( m[1] );
            }

            <?php
          endif;
          ?>
          
          var returnVal = "";
          if(pagintionType == 'numeric'){
            returnVal =   i + 1;
            returnVal = '<span class="wls-pagination-number">' + returnVal + '</span>';
          }
            
          else if(pagintionType == 'thumb')
            returnVal = '<span class="wls-pagination-image">' + '<img src="'+image_url_list[i]+'" width="20" height="20" />' + '</span>';
          else
            returnVal = '';
          
          return returnVal;
        },
        <?php endif; ?>
        <?php if(isset($settings['wls_enable_mobile_resolution']) && $settings['wls_enable_mobile_resolution'] === '1'): ?>
          responsive: [
            <?php foreach($settings['res'] as $k => $v): ?>
                {
                  breakpoint: <?php echo esc_attr( str_replace("_", "", $k) ) ?>,
                  settings: {
                    slidesToShow: <?php echo esc_attr( $v ); ?>,
                    slidesToScroll: <?php echo esc_attr( $v ); ?>,
                  }
                },
            <?php endforeach; ?>          
          ]
        <?php endif; ?>
        });
        
        <?php if(isset($settings['caption']) && $settings['caption'] !="No caption"): ?>       
          
          // Caption Position
          <?php if(isset($settings['caption']) && $settings['caption'] =="Top"): ?>          
            $('div.panel>div.ws-caption').css({'top':0});            
          <?php else: ?>
             $('div.panel>div.ws-caption').css({'bottom':0});             
          <?php endif; ?>

          // caption Effect
          <?php if(isset($settings['caption_effect']) &&  $settings['caption_effect'] =="Slide Toggle"): ?>          
            $('#'+randomId+' div.panel').hover(
              function(){$('.ws-caption',this).slideDown('slow');},
              function(){$('.ws-caption',this).slideUp('slow');}
            );
          <?php else: ?>
             $('#'+randomId+' .slick-track  div.panel').hover(
              function(){$('.ws-caption',this).fadeIn('slow');},
              function(){$('.ws-caption',this).fadeOut('slow');}
            );
          <?php endif; ?>            
        <?php endif; ?>
        <?php if(isset($settings['center_mode']) && $settings['center_mode'] =="1"): ?> 
          $('.slick-track').css({'padding-top': '2%','padding-bottom':'2%'});
        <?php endif; ?>
      });
    </script>

  <?php
  $output = ob_get_contents();
  ob_end_clean();
  return $output;
  }

}
