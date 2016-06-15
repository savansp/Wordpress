<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Cheque Payment Gateway.
 *
 * Provides a Cheque Payment Gateway, mainly for testing purposes.
 *
 * @class 		WC_Gateway_Invoice
 * @extends		WC_Payment_Gateway
 */
class WC_Gateway_Invoice extends WC_Payment_Gateway {

    /**
     * Constructor for the gateway.
     */
	public function __construct() {
		$this->id                 = 'invoice';
		$this->icon               = apply_filters('woocommerce_invoice_icon', '');
		$this->has_fields         = false;
		$this->method_title       = __( 'Invoice', 'woocommerce' );
		$this->method_description = __( 'Allows invoice payments.', 'woocommerce' );

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables
		$this->title        = $this->get_option( 'title' );
		$this->logo  = $this->get_option( 'logo' );
		$this->pdf_title  = $this->get_option( 'pdf_title' );
		$this->footer_description  = $this->get_option( 'footer_description' );
		
		//$this->instructions = $this->get_option( 'instructions', $this->description );
		// Actions
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
    	add_action( 'woocommerce_thankyou_cheque', array( $this, 'thankyou_page' ) );

    	// Customer Emails
    	add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
    }

    /**
     * Initialise Gateway Settings Form Fields.
     */
    public function init_form_fields() {

    	$this->form_fields = array(
			'enabled' => array(
				'title'   => __( 'Enable/Disable', 'woocommerce' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable Invoice Payment', 'woocommerce' ),
				'default' => 'yes'
			),
			'title' => array(
				'title'       => __( 'Title', 'woocommerce' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
				'default'     => 'Invoice Payment',
				'desc_tip'    => true,
			),
			'pdf_title' => array(
				'title'       => __( 'PDF Title', 'woocommerce' ),
				'type'        => 'text',
				'description' => __( 'This controls the PDF title which the user sees in PDF invoice.', 'woocommerce' ),
				'default'     => 'Woo',
				'desc_tip'    => true,
			),
			'footer_description' => array(
				'title'       => __( 'Footer Descriotion', 'woocommerce' ),
				'type'        => 'textarea',
				'description' => __( 'PDF descpription section.', 'woocommerce' ),
				'default'     => '',
				'desc_tip'    => true,
			),
			'logo' => array(
				'title'       => __( 'Logo', 'woocommerce' ),
				'type'        => 'file',
				'description' => __( 'Logo.', 'woocommerce' ),
				//'default'     => __( '', 'woocommerce' ),
				'desc_tip'    => true,
			),
		);
    }

    /**
     * Output for the order received page.
     */
	public function thankyou_page() {
		if ( $this->instructions )
        	echo wpautop( wptexturize( $this->instructions ) );
	}

    /**
     * Add content to the WC emails.
     *
     * @access public
     * @param WC_Order $order
     * @param bool $sent_to_admin
     * @param bool $plain_text
     */
	public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
        if ( $this->instructions && ! $sent_to_admin && 'invoice' === $order->payment_method && $order->has_status( 'on-hold' ) ) {
			echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
		}
	}

    /**
     * Process the payment and return the result.
     *
     * @param int $order_id
     * @return array
     */
	public function process_payment( $order_id ) {

		$order = wc_get_order( $order_id );

		// Mark as on-hold (we're awaiting the cheque)
		$order->update_status( 'on-hold', __( 'Awaiting confirmation', 'woocommerce' ) );
		
		$pdf = new PDF();
		
		$header = array('Item', 'Qty', 'Total');
		$items = $order->get_items();
		foreach($items as $singleItem){
			$data[] = array($singleItem['name'],$singleItem['qty'],$singleItem['line_total']);
		}
		$pdf->SetFont('helvetica','',16);
		$pdf->AddPage();
		$pdf->Cell(0,5,$this->pdf_title,1,0,'C');
		$pdf->Ln(12);
		$pdf->FancyTable($header,$data);
		$pdf->Ln(2);
		$pdf->Cell(0,5,$this->footer_description,1,0,'C');
		//echo plugin_dir_path( __FILE__ ).'invoices/'.$order_id.'.pdf';
		//$pdf->Output('F',plugin_dir_path( __FILE__ ).'invoices/'.$order_id.'.pdf');
		
		// Reduce stock levels
		$order->reduce_order_stock();

		// Remove cart
		WC()->cart->empty_cart();

		// Return thankyou redirect
		return array(
			'result' 	=> 'success',
			'redirect'	=> $this->get_return_url( $order )
		);
	}
}
