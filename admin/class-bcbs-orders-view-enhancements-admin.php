<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://redacted.store
 * @since      1.0.0
 *
 * @package    Bcbs_Orders_View_Enhancements
 * @subpackage Bcbs_Orders_View_Enhancements/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bcbs_Orders_View_Enhancements
 * @subpackage Bcbs_Orders_View_Enhancements/admin
 * @author     ******************
 */
class Bcbs_Orders_View_Enhancements_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bcbs_Orders_View_Enhancements_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bcbs_Orders_View_Enhancements_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bcbs-orders-view-enhancements-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bcbs_Orders_View_Enhancements_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bcbs_Orders_View_Enhancements_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bcbs-orders-view-enhancements-admin.js', array( 'jquery' ), $this->version, false );

	}

    /**
     *  Appends extra columns
     */
    function bcbs_wc_order_extra_columns($defaults)
    {

        $checkbox = $defaults['cb'];  // save the multi-select checkbox
        unset( $defaults['cb'] );   // remove it from the columns list

        $newcolumns = array(
            "cb" => $checkbox,
            "tracking_num"    => esc_html__('Tracking #', 'redacted'),
            "delivery_signature"    => esc_html__('No Sig.', 'redacted'),
        );
        $newcolumns = array_merge( $newcolumns, $defaults );

        $custinfo = array(
            "customer_info" => '<span class="customer_info_head tips" data-tip="' . esc_html__( 'Cust. Info', 'redacted' ) . '">' . esc_html__( 'Cust. Info', 'redacted' ) . '</span>'
        );

        $this->array_insert( $newcolumns, "order_items", $custinfo );

        return $newcolumns;

    }

    /**
     * Populate custom wc order columns with the content
     */
    function bcbs_wc_order_extra_columns_content($column)
    {
        global $post;

        $order_id = $post->ID;

        switch ($column)
        {
            case "tracking_num":

                $tracking_num = strtoupper( get_post_meta( $post->ID, '_aftership_tracking_number', true ) );
                $this->bcbs_editable_tracking_field( '_aftership_tracking_number', '', '',  esc_html( $tracking_num ) );

                break;

            case "delivery_signature":
                $signature = $this->clean_string( get_post_meta( $post->ID, '_signature_required', true) );
                if ( $signature == '0' ) {
                    echo '<i class="fa fa-certificate" data-tip=""></i>';
                }
                break;

            case "customer_info":
                echo '<span class="cust-info tips" data-tip=""></span>'; //. wc_sanitize_tooltip( $the_order->customer_message ) . '">' . __( 'Yes', 'woocommerce' ) . '</span>';

                add_thickbox();
                echo "<a href=\"#TB_inline?width=300&height=350&inlineId=woocommerce-customer-notes-{$order_id}\" class=\"thickbox\">&nbsp;</a>";
                ?>

                <div style="display: none;" id="woocommerce-customer-notes-<?php echo $order_id ?>" class="postbox ">

                    <button class="handlediv button-link" aria-expanded="true" type="button">
                        <span class="toggle-indicator" aria-hidden="true"></span>
                    </button>
                    <h3 class="hndle"><span><?php _e( 'Customer Notes', 'wc_crm' ) ?></span></h3>
                    <div class="inside" style="padding:0px;">
                        <ul class="order_notes">
                            <?php
                            $order = new WC_Order( $order_id );
                            $crm_customer = new WC_CRM_Customer( $order->get_user_id() );
                            $notes = $crm_customer->get_customer_notes();
                            if ( $notes ) {
                                foreach( $notes as $note ) {
                                    ?>
                                    <li style="padding: 0 10px;"rel="<?php echo absint( $note->comment_ID ) ; ?>">
                                        <div class="note_content <?php echo get_comment_meta($note->comment_ID, 'customer_note_type', true) ?>">
                                            <?php echo wpautop( wptexturize( wp_kses_post( $note->comment_content ) ) ); ?>
                                        </div>
                                        <p class="meta">
                                            <abbr class="exact-date" title="<?php echo $note->comment_date_gmt; ?> GMT"><?php printf( __( 'added %s ago', 'wc_crm' ), human_time_diff( strtotime( $note->comment_date_gmt ), current_time( 'timestamp', 1 ) ) ); ?></abbr>
                                            <?php if ( $note->comment_author !== __( 'WooCommerce', 'wc_crm' ) ) printf( ' ' . __( 'by %s', 'wc_crm' ), $note->comment_author ); ?>
                                            <a href="#" class="delete_customer_note"><?php _e( 'Delete note', 'wc_crm' ); ?></a>
                                            <?php wp_nonce_field( 'deleteCustomerNote', "bcbs_delete_customer_note_" . absint( $note->comment_ID ) ."_nonce" ); ?>
                                            <input class="customer_id" type="hidden" name="customer_id" value="<?php echo $order->get_user_id() ?>" />
                                        </p>
                                    </li>
                                    <?php
                                }
                            } else {
                                echo '<li>' . __( 'There are no notes yet.', 'wc_crm' ) . '</li>';
                            } ?>
                        </ul>
                        <div class="add_note">
                            <h4><?php _e( 'Add note', 'wc_crm' ) ?></h4>
                            <p>
                                <textarea rows="5" cols="20" class="input-text" id="add_order_note" name="order_note" type="text"></textarea>
                            </p>
                            <p>
                                <select name="customer_note_type" id="customer_note_type">
                                    <option value="private"><?php _e( 'Private note', 'woocommerce' ); ?></option>
                                    <option value="customer"><?php _e( 'Note to customer', 'woocommerce' ); ?></option>
                                    <option value="agent"><?php _e( 'Note to agent', 'wc_crm' ); ?></option>
                                    <option value="all"><?php _e( 'Note to agent & customer', 'wc_crm' ); ?></option>
                                </select>
                                <a class="add_note_customer button" href="#"><?php _e( 'Add', 'wc_crm' ) ?></a>

                            </p>
                            <input class="customer_id" type="hidden" name="customer_id" value="<?php echo $order->get_user_id() ?>" />

                            <?php wp_nonce_field( 'addCustomerNote', "bcbs_add_customer_note_nonce" ); ?>


                        </div>
                    </div>
                </div>

                <?php
                break;
        }
    }

    /**
     * Returns a link to tracking information provided by Aftership
     * @param $tracking_num string Tracking number for the link
     * @return string Generated A tag for display
     */
    function get_aftership_tracking_link( $tracking_num ) {
        $tracking_num = esc_html( $tracking_num );
        if ( ! $tracking_num ) {
            return '';
        }
        $encoded_tnum = urlencode( esc_html( $tracking_num ) );
        return "<a href='https://track.aftership.com/canada-post/{$encoded_tnum}' target='_blank'>{$tracking_num}</a>";
    }

    /**
     * Displays an AJAX editable field representing an order's tracking number
     * @param $id int Field ID
     * @param $label string Field Label
     * @param $placeholder string Field placeholder text
     * @param $value string Field text value
     */
    function bcbs_editable_tracking_field( $id, $label, $placeholder, $value) {

        if ( ! is_admin() ) {
            //return;
        }

        $order_id = get_the_ID();

        echo "<div class='editable-tracking-field'>";

        //plain text link
        echo '<span class="tracking-link-label">' . $label . ( $value ? $this->get_aftership_tracking_link( $value ) : $placeholder ) . '</span>';

        //edit button
        echo '<i class="fa fa-pencil start-edit"></i>';
        echo '<span class="edit-fields">';

        //editable text field (hidden by default)
        woocommerce_wp_text_input(
            array(
                'id' => $id,
                'class' => 'edit-field',
                'label' => '',
                'placeholder' => '',
                'value' => esc_html( $value )
            )
        );

        //edit action icons
        echo '&nbsp;&nbsp;<i class="fa fa-check save-edit"></i>';
        //TODO: link up save button
        echo '&nbsp;&nbsp;<i class="fa fa-times cancel-edit pull-right"></i>';
        //TODO: link up cancel button
        echo '</span>'; /*end .edit-fields */

        echo "<input type='hidden' class='order_id' name='order_id' value='{$order_id}' />";

        wp_nonce_field( 'updateTrackingNumber', 'bcbs_update_tracking_nonce' );
        echo '</div>';

    }

    /**
     * Called via AJAX POST. Updates the tracking number associated with a post.
     */
    function updateTrackingNumber() {

        if ( ! empty( $_POST ) && check_admin_referer( 'updateTrackingNumber', 'bcbs_update_tracking_nonce' ) ) {
            // process form data
            $order_id = sanitize_text_field( $_POST['order_id'] );
            $tracking_num = sanitize_text_field( $_POST['tracking_num'] );

            error_log( "Updated tracking # for order: {$order_id} to: {$tracking_num}");

            //make the update
            if ( $order_id && $tracking_num ) {
                update_post_meta( $order_id, '_aftership_tracking_number', $tracking_num );
                update_post_meta( $order_id, '_aftership_tracking_provider_name', 'Canada Post' );
                update_post_meta( $order_id, '_aftership_tracking_provider', 'canada-post' );

                echo $tracking_num; // return the saved tracking number to POSTer to indicate success

                $order = new WC_Order( $order_id );
                $order->update_status( 'processing' );
                $order->update_status( 'completed' ); // proccessing -> completed state change triggers a new email

            } else {
                //oops.. give us the things we need next time
                echo 0;
            }

            die();
        }
    }

}
