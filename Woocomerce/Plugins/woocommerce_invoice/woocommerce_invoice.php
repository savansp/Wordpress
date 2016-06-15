<?php

/**
 * Plugin Name: WooCommerce Invoice
 * Description: Invoice Plugin
 * Version: 1.0
 * Author: AGC
*/
require_once 'fpdf.php';
class PDF extends FPDF
{
// Load data

function LoadData($file)
{
    // Read file lines
    $lines = file($file);
    $data = array();
    foreach($lines as $line)
        $data[] = explode(';',trim($line));
    return $data;
}
function FancyTable($header, $data)
{
    // Colors, line width and bold font
    $this->SetFillColor(0,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(0,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Header
    $w = array(110, 40, 40, 40);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(0,0,0);
    $this->SetTextColor(255,0,0);
    $this->SetFont('');
    // Data
    $fill = false;
    $c = 0;
    foreach($data as $row)
    {
        if($c % 2 == 0){
            $this->SetTextColor(0);
        }else{
            $this->SetTextColor(255);
        }
        $this->Cell($w[0],15,$row[0],'LR',0,'L',$fill);
        $this->Cell($w[1],15,$row[1],'LR',0,'L',$fill);
        $this->Cell($w[2],15,number_format($row[2]),'LR',0,'L',$fill);
        $this->Ln();
        $fill = !$fill;
        $c++;
    }
    $this->SetTextColor(0);
    // Closing line
    $this->Cell(190,0,'','T');
}
function invoiceToltal($header, $data)
{
    // Colors, line width and bold font
    //$this->SetFillColor(0,0,0);
    $this->SetTextColor(255);
    //$this->SetDrawColor(0,0,0);
    //$this->SetLineWidth(.3);
    //$this->SetFont('','B');
    // Header
    $w = array(110, 40, 40, 40);
    for($i=0;$i<count($header);$i++)
        //$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Color and font restoration
    //$this->SetFillColor(0,0,0);
    $this->SetTextColor(0);
    //$this->SetFont('');
    // Data
    //$fill = false;
    //$c = 0;
    foreach($data as $row)
    {   
        $this->Cell($w[0],8,$row[0],0,'L');
        $this->Cell($w[1],8,$row[1],0,'L');
        $this->Cell($w[2],8,$row[2],0,'L');
        $this->Ln();
        //$fill = !$fill;
        //$c++;
    }
    //$this->SetTextColor(0);
    // Closing line
    //$this->Cell(190,0,'','T');
}
}
require_once 'class-wc-gateway-invoice.php';

function WC_Gateway_Invoice_Class($methods){
	
	$methods[] = 'WC_Gateway_Invoice'; 
    return $methods;
	}
add_filter( 'woocommerce_payment_gateways', 'WC_Gateway_Invoice_Class' );

function view_pdf_action( $actions, $order ) {
$order_id = $order->get_order_number();
if( $order->payment_method == 'invoice' && file_exists(plugin_dir_path( __FILE__ ).'invoices/'.$order_id.'.pdf')){
    $actions['view_pdf'] = array(
        // adjust URL as needed
        'url'  =>  plugin_dir_url(__FILE__).'invoices/' . $order_id.'.pdf',
        'name' => __( 'View PDF', 'my-textdomain' ),
    );
}
    return $actions;
}
add_filter( 'woocommerce_my_account_my_orders_actions', 'view_pdf_action', 10, 2 );

add_action( 'woocommerce_checkout_order_processed', 'my_pdf',  1, 1  );

function my_pdf($order_id){

        global $woocommerce;
        if ( !$order_id )
        return;
       

        $order = wc_get_order( $order_id );
        //print_r($order);
        //echo $order_id;
        // Mark as on-hold (we're awaiting the cheque)
        //$order->update_status( 'on-hold', __( 'Awaiting confirmation', 'woocommerce' ) );
        
        $pdf = new PDF();
       
        $header = array('Product', 'Quantity', 'Price');
        $items = $order->get_items();
        //print_r($order);
        
        foreach($items as $singleItem){
            $data[] = array($singleItem['name'],$singleItem['qty'],$singleItem['line_total']);
        }

        $subtotal = $order->get_subtotal();
        $total = $order->get_total();
        $shipping_methods = $order->get_shipping_methods();
        foreach ($shipping_methods as $shipping_method) {
             $shippingName = $shipping_method['name'];
             $shippingCost = $shipping_method['cost'];
         } 
       
        $data2[] = array('','Subtotal',html_entity_decode(get_woocommerce_currency_symbol()).$subtotal);
        $data2[] = array('','Shipping',$shippingName.' '.html_entity_decode(get_woocommerce_currency_symbol()).$shippingCost);
        $data2[] = array('','Total',html_entity_decode(get_woocommerce_currency_symbol(), ENT_NOQUOTES, 'UTF-8').$total);
        
        //print_r($data);exit;
        $pdf->SetFont('Times','',14); 
        $pdf->AddPage();
        $pdf->Image('https://docs.woothemes.com/wc-apidocs/resources/woocommerce_logo_white.png', 10, 10, 0, 0, 'png');    // Arial bold 15
        //$pdf->Cell(30,25,'Your Invoice',1,0,'C');
        //$pdf->Ln(12);
        //$pdf->Cell(50,10,'Title',30,0,'C');
        //$pdf->Cell(0,10,'Printing line number 1',0,1);
        //$pdf->Ln(20);
        //$pdf->Cell(80);
        $pdf->Ln(2);
        $pdf->SetFont('Times','B',14); 
        $pdf->Cell(0,5,'Site Title',0,1,'R');
        $pdf->Ln(2);
        $pdf->SetFont('Times','',14);
        $pdf->Cell(0,5,'Printing line number 2',0,1,'R');
        $pdf->Ln(2);
        $pdf->Cell(0,5,'Printing line number 3',0,1,'R');
        $pdf->Ln(2);
        $pdf->Cell(0,5,'Printing line number 4',0,1,'R');
        $pdf->Ln(10);
        $pdf->SetFont('Times','B',20);
        $pdf->Cell(0,5,'INVOICE',0,3,'l');
        $pdf->Ln(10);
        $pdf->SetFont('Times','',12);
        $pdf->Cell(0,5,'Left Data 1',0,0,'L');
        $pdf->Cell(0,5,'Right Data 1',0,1,'R');
        $pdf->Cell(0,5,'Left Data 2',0,0,'L');
        $pdf->Cell(0,5,'Right Data 2',0,1,'R');
        $pdf->Cell(0,5,'Left Data 3',0,0,'L');
        $pdf->Cell(0,5,'Right Data 3',0,1,'R');
        $pdf->Cell(0,5,'Left Data 4',0,0,'L');
        $pdf->Cell(0,5,'Right Data 4',0,1,'R');
        $pdf->Ln(10);
        $pdf->SetFont('Times','',14);
        $pdf->FancyTable($header,$data);
        $pdf->Ln(10);
        $pdf->invoiceToltal($header,$data2);
        $pdf->Ln(20);
       
        //print_r($shipping_methods); 
       
        //exit;
         $pdf->SetFont('Times','',8);
        $pdf->Cell(0,5,'Oandb @ 2016 All Right Reserved',0,0,'C');

        //echo plugin_dir_path( __FILE__ ).'invoices/'.$order_id.'.pdf';
        $pdf->Output('F',plugin_dir_path( __FILE__ ).'invoices/'.$order_id.'.pdf');  
//die();
       
}

add_filter( 'woocommerce_email_attachments', 'add_files_to_email', 1, 3);

function add_files_to_email( $attachments, $status , $order ) {

    $order_id = $order->id;
    //echo 'true';print_r($order_id);exit;
    $allowed_statuses = array( 'customer_processing_order' , 'new_order');

    if( isset( $status ) && in_array ( $status, $allowed_statuses ) ) {

        $file_path = plugin_dir_path( __FILE__ ).'invoices/'.$order_id.'.pdf';
        //$file_path   = '/home/oandbcentral/public_html/learnwp/wp-content/plugins/woocommerce_invoice/invoices/121.pdf';     
        // /$file_path2 = get_template_directory() . '/43.pdf';
        $attachments[] = $file_path;

    }
    return $attachments;

}

add_filter('woocommerce_email_subject_new_order', 'change_admin_email_subject', 1, 2);

function change_admin_email_subject( $subject, $order ) {
    global $woocommerce;

    $order_id = $order->get_order_number();
    
    $allowed_statuses = array( 'customer_processing_order' , 'new_order');

    $file_path = plugin_dir_path( __FILE__ ).'invoices/'.$order_id.'.pdf';
    $file_path2 = get_template_directory() . '/43.pdf';
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    $subject = sprintf( '[%s] New Customer Order (# %s) from Name %s %s ', $blogname, $order->id, $order->billing_first_name, $order->billing_last_name );

    return $subject;
}
