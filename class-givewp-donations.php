<?php 
/**
 * PHP class to handle modifications to the GiveWP forms for the 
 * Harmony Foundation needs.
 * 
 * Version: 1.6.5
 * Date: 2021-03-14
 */

namespace HarmonyFund;
use Give\Helpers\Form\Template as FormTemplateUtils;

class HarmonyFundGiveWPDonations {

  
    public function __construct() {
      add_action( 'init', array($this, 'hf_enqueue_scripts'), 200);
      add_action( 'give_embed_head', array($this, 'hf_enqueue_scripts'), 10);
      add_filter( 'give_form_content_output', array($this, 'hf_give_form_content_output'), 10, 3);
      add_action( 'give_before_donation_levels', array($this, 'hf_add_elements_to_givewp_form_amount_section'), 10, 2); 
      add_action ('give_donation_form_after_user_info', array($this, 'hf_give_donation_form_payment_section_top'), 10, 1);
      add_action( 'give_donation_form_after_submit', array($this, 'hf_add_elements_to_givewp_form_payment_section'), 99999, 2); 
      // add_filter('give_donation_form_submit_button', array($this,'hf_give_donation_form_submit_button'), 10, 3);
      // add_action( 'give_donation_form_before_submit', array($this, 'hf_give_donation_form_before_submit'), 10, 2);
      // add_action( 'give_donation_form_after_submit', array($this, 'hf_give_donation_form_after_submit'), 10, 2);

      add_action( 'give_pre_form_output', array($this, 'hf_give_payment_mode_before_gateways'), 9999);
      // add_action( 'give_paypal-commerce_cc_form', array($this, 'hf_give_donation_form_before_submit'), 1, 2);
      // add_action( 'give_paypal-commerce_cc_form', array($this, 'hf_give_donation_form_after_submit'), 10, 2);

      add_action( 'wp_enqueue_scripts', array($this, 'hf_change_paypal_localize'), 1000, 1);
    } // end function construct

    /**
     * Add the JS and CSS scripts necessary for the forms
     */
    public function hf_enqueue_scripts() {
      wp_enqueue_style( 'hf-givewp', get_stylesheet_directory_uri() . '/harmonyfund-givewp.css', [ 'give-styles' ], '1.6.5b');
      wp_enqueue_script( 'hf-givewp', get_stylesheet_directory_uri() . '/harmonyfund-givewp.js', array( 'jquery' ), '1.6.5' , true);
    }

    /**
     * This is for the legacy donation form and will output a featured image
     * as well as wrap the form in a box.
     */
    public function hf_give_form_content_output($output, $form_id, $args) {

      if(give_is_setting_enabled( give_get_meta( $form_id, '_give_display_content', true ))) {
        // make sure it's not null 
        if(strpos($output, 'give_pre_form-content"></div>') === false){

          $hf_output  = '<div id="hf-give-form-content-wrap">';
          $hf_output .= '<div class="hf-give-form-featured-image" style="background-image: url(\'' . wp_get_attachment_url( get_post_thumbnail_id($form_id) ) . '\');">' . get_the_post_thumbnail($form_id) . '</div>';
          
          $hf_output .= '<div class="hf-content-wrap">' . $output . '</div>';

          $hf_output .= '</div>'; // #hf-give-form-content-wrap
          return $hf_output;
        }
      }     
    } // end function

    /**
     * Adds the various HTML elements to the GiveWP forms on the amount tab
     */
    public function hf_add_elements_to_givewp_form_amount_section($output, $form) {
      // Get the GiveWP color settings
      $templateOptions = FormTemplateUtils::getOptions();
      $primary_color        = ! empty( $templateOptions['introduction']['primary_color'] ) ? $templateOptions['introduction']['primary_color'] : '#28C77B';
      $lighter_color = $this->hf_adjust_brightness($primary_color, 25);
        ?>

      <style>
        .donation-type-selector .donation-type.selected .button-gradient {
            background: <?php echo $primary_color; ?>;
            background-image: -webkit-linear-gradient(top,#368fcc 0,<?php echo $primary_color; ?> 100%);
            background-image: -o-linear-gradient(top,#368fcc 0,<?php echo $primary_color; ?> 100%);
            background-image: linear-gradient(to bottom,<?php echo $lighter_color; ?> 0,<?php echo $primary_color; ?> 100%);
        }
        .give-viewing-form-in-iframe .choose-amount .give-donation-levels-wrap .give-donation-level-btn.give-default-level {
            background: <?php echo $primary_color; ?> !important;
            background-image: -webkit-linear-gradient(top,#368fcc 0,<?php echo $primary_color; ?> 100%) !important;
            background-image: -o-linear-gradient(top,#368fcc 0,<?php echo $primary_color; ?> 100%) !important;
            background-image: linear-gradient(to bottom,<?php echo $lighter_color; ?> 0,<?php echo $primary_color; ?> 100%) !important;
            color: #fff !important;
        }
        .give-viewing-form-in-iframe .hf-advance-btn-wrapper:hover .advance-btn {
          background: <?php echo $lighter_color; ?> !important;
        }
        .give-viewing-form-in-iframe .give-section.payment .give-fee-recovery-donors-choice label {
          background: <?php echo $primary_color; ?> 
        }
        .hf-lightbox-content .hf-donate-keep-onetime:after,
        .choose-recurring .hf-donate-keep-onetime:after {
          border-bottom: 2px solid <?php echo $primary_color; ?>;
        }
      </style>

      <h2 class="hf-give-form-title"><?php echo get_the_title( $form['id'] ); ?></h2>

      <!-- <div class="donation-type-selector">
        <button class="donation-type one-time selected" aria-current="true" aria-disabled="true">
          <div class="button-gradient"></div>
          <div class="button-text">One Time</div>
        </button>
        <button class="donation-type recurring" aria-current="false" aria-disabled="false">
          <div class="button-gradient"></div>
          <input type="checkbox" class="heart-checkbox" id="heart-checkbox" />
          <label class="heart-checkbox-label" for="heart-checkbox">
            <svg id="heart-svg" viewBox="467 392 58 57" xmlns="http://www.w3.org/2000/svg"> <g id="Group" fill="none" fill-rule="evenodd" transform="translate(467 392)"> <path d="M29.144 20.773c-.063-.13-4.227-8.67-11.44-2.59C7.63 28.795 28.94 43.256 29.143 43.394c.204-.138 21.513-14.6 11.44-25.213-7.214-6.08-11.377 2.46-11.44 2.59z" id="heart" fill="#AAB8C2"/> <circle id="main-circ" fill="#E2264D" opacity="0" cx="29.5" cy="29.5" r="1.5"/> <g id="grp7" opacity="0" transform="translate(7 6)"> <circle id="oval1" fill="#9CD8C3" cx="2" cy="6" r="2"/> <circle id="oval2" fill="#8CE8C3" cx="5" cy="2" r="2"/> </g> <g id="grp6" opacity="0" transform="translate(0 28)"> <circle id="oval1" fill="#CC8EF5" cx="2" cy="7" r="2"/> <circle id="oval2" fill="#91D2FA" cx="3" cy="2" r="2"/> </g> <g id="grp3" opacity="0" transform="translate(52 28)"> <circle id="oval2" fill="#9CD8C3" cx="2" cy="7" r="2"/> <circle id="oval1" fill="#8CE8C3" cx="4" cy="2" r="2"/> </g> <g id="grp2" opacity="0" transform="translate(44 6)"> <circle id="oval2" fill="#CC8EF5" cx="5" cy="6" r="2"/> <circle id="oval1" fill="#CC8EF5" cx="2" cy="2" r="2"/> </g> <g id="grp5" opacity="0" transform="translate(14 50)"> <circle id="oval1" fill="#91D2FA" cx="6" cy="5" r="2"/> <circle id="oval2" fill="#91D2FA" cx="2" cy="2" r="2"/> </g> <g id="grp4" opacity="0" transform="translate(35 50)"> <circle id="oval1" fill="#F48EA7" cx="6" cy="5" r="2"/> <circle id="oval2" fill="#F48EA7" cx="2" cy="2" r="2"/> </g> <g id="grp1" opacity="0" transform="translate(24)"> <circle id="oval1" fill="#9FC7FA" cx="2.5" cy="3" r="2"/> <circle id="oval2" fill="#9FC7FA" cx="7.5" cy="2" r="2"/> </g> </g> </svg>
          </label>
          <div class="button-text"><span class="hf-recurring-period-label">Monthly</span></div>
        </button>
      </div> -->

      <div class="donation-amount-period">
        <p class="one-time">You are making a one-time donation.</p>
        <p class="recurring" style="display:none;">You are making this donation each <span class="hf-recurring-period">month</span>.</p>
      </div>
      
      <!-- <div class="change-currency"><a href="#">Change Currency</a></div> -->
      <?php

    } // end function

    /**
     * Add a payment subtotal on the payment page
     */
    public function hf_give_donation_form_payment_section_top($form_id) {
      ?>
        <div class="hf-payment-totals"><?php echo give_checkout_final_total($form_id); ?>
          <div class="hf-monthly-success" style="display: none;">
            <svg id="successAnimation" class="animated" xmlns="http://www.w3.org/2000/svg" width="70" height="70" viewBox="0 0 70 70">
            <path id="successAnimationResult" fill="#D8D8D8" d="M35,60 C21.1928813,60 10,48.8071187 10,35 C10,21.1928813 21.1928813,10 35,10 C48.8071187,10 60,21.1928813 60,35 C60,48.8071187 48.8071187,60 35,60 Z M23.6332378,33.2260427 L22.3667622,34.7739573 L34.1433655,44.40936 L47.776114,27.6305926 L46.223886,26.3694074 L33.8566345,41.59064 L23.6332378,33.2260427 Z"/>
            <circle id="successAnimationCircle" cx="35" cy="35" r="24" stroke="#979797" stroke-width="2" stroke-linecap="round" fill="transparent"/>
            <polyline id="successAnimationCheck" stroke="#979797" stroke-width="2" points="23 34 34 43 47 27" fill="transparent"/>
          </svg>
        </div>
      </div>
      <?php 
    }

    /**
     * Wrap the payment button in a div so we can perform JS on it
     */
    // public function hf_give_donation_form_before_submit($form_id, $args = null) {
    //   $min_donation_amount = round(get_post_meta( $form_id, '_give_custom_amount_range_minimum', true ), 2);
    //   echo '<div class="hf-advance-btn-wrapper" data-min_donation_amount="' . $min_donation_amount . '">';

    //   echo '<div class="paypal-press-button-msg donate-msg"><div class="donate-msg-inner">Press the PayPal button to complete your donation.</div><div class="arrow-down"></div></div>';

    //   echo '<div class="stripe-press-donate-msg donate-msg"><div class="donate-msg-inner">Press Donate to complete your donation.</div><div class="arrow-down"></div></div>';
    // }
    // public function hf_give_donation_form_after_submit($form_id, $args) {
    //   echo '</div>';
    // }

    /**
     * Adds a monthly reminder propmt at the end of the form
     */
    public function hf_add_elements_to_givewp_form_payment_section($form_id, $args) {

        // get the heading and sub heading fields 
        // $hf_recurring_heading = '';
        // $hf_recurring_subheading = '';
        // $hf_recurring_image = '';
        // if(function_exists('get_field')) { 
        //   $hf_recurring_heading = get_field('recurring_donation_prompt_heading', $form_id);
        //   $hf_recurring_subheading = get_field('recurring_donation_prompt_subheading', $form_id);
        //   $image = get_field('recurring_donation_prompt_image', $form_id);
        //   if( !empty( $image ) ) { 
        //       $hf_recurring_image = '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($image['alt']) . '" />';
        //   }
        // }
        // if($hf_recurring_heading == '') {
        //   $hf_recurring_heading = 'Would you like to change your donation to a smaller, <span class="hf-recurring-period-label" style="text-transform:lowercase;">monthly</span> gift?';
        // }
        // if($hf_recurring_subheading == '') {
        //   $hf_recurring_subheading = 'Automatic, monthly donations help us feed and rescue more animals throughout the year. <span style="font-style: oblique;">(cancel anytime)</span>';
        // }
        // if($hf_recurring_image == '') {
        //   $hf_recurring_image = '<img src="' . get_stylesheet_directory_uri() . '/images/harmonyfund-givewp-recurring.jpg">';
        // }
        ?>
        <!-- </fieldset>
        </fieldset>
        </div>  --><!-- payment section -->
        <!-- <div class="give-section choose-recurring">
        <div class="hf-donate-plea">

              <div class="hf-recurring-image"><?php //echo $hf_recurring_image; ?></div>
              <div class="heading"><?php //echo $hf_recurring_heading; ?></div>
              <div class="subheading"><?php //echo $hf_recurring_subheading; ?></div>

              <p><button class="hf-donate-modify give-btn two">Donate <span class="hf-donate-new-amount two"></span>/<span class="hf-recurring-period">monthly</span></button></p>

              <p><button class="hf-donate-modify give-btn one">Donate <span class="hf-donate-new-amount one"></span>/<span class="hf-recurring-period">monthly</span></button></p>

              <p><button class="give-btn hf-donate-keep-onetime">Keep my one-time gift of <span class="hf-donate-original-amount"></span></button></p>
            </div>
          <fieldset>
            <fieldset> --> 
      
    <?php
    }
    /**
     * Computes new hex color values given a starting value and then the 
     * increase or decrease in steps.
     */
    private function hf_adjust_brightness($hex, $steps) {
      // Steps should be between -255 and 255. Negative = darker, positive = lighter
      $steps = max(-255, min(255, $steps));

      // Normalize into a six character long hex string
      $hex = str_replace('#', '', $hex);
      if (strlen($hex) == 3) {
          $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
      }

      // Split into three parts: R, G and B
      $color_parts = str_split($hex, 2);
      $return = '#';

      foreach ($color_parts as $color) {
          $color   = hexdec($color); // Convert to decimal
          $color   = max(0,min(255,$color + $steps)); // Adjust color
          $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
      }

      return $return;
    } // end function

    /**
     * Add a filter at the appropriate point in the template to 
     * modify the button labels
     */
    public function hf_give_payment_mode_before_gateways() {
            add_filter( 'give_enabled_payment_gateways', array($this, 'hf_give_enabled_payment_gateways'), 99999999999999999 , 1);
    }
    /**
     * Remove the "Donate Now" text on the buttons
     */
    public function hf_give_enabled_payment_gateways($gateways) {
      foreach ( $gateways as $key => $value ) {

        $gateways[ $key ]['checkout_label'] = str_replace('Donate with ', '', $gateways[ $key ]['checkout_label']);

      }
      return $gateways;
    }
    
    /**
     * Remove the "card" option from the PayPal settings. This filters the localization
     * options with the give > src > PaymentGateways > PayPalCommerce > PayPalCommerce.php
     * file
     */
    public function hf_change_paypal_localize() {
      $giveWPPayPalData = $GLOBALS['wp_scripts']->registered['give-paypal-commerce-js']->extra['data'];
      $GLOBALS['wp_scripts']->registered['give-paypal-commerce-js']->extra['data'] = str_replace('"disable-funding":"credit"', '"disable-funding":"credit,card"', $giveWPPayPalData);
    }
} // end class

$hf_givewp_donations = new HarmonyFundGiveWPDonations();

