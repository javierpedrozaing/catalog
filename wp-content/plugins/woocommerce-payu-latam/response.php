<?php
require_once '../../../wp-blog-header.php';
require_once './payu-latam.php';
get_header('shop');


if(isset($_REQUEST['signature'])){
	$signature = $_REQUEST['signature'];
} else {
	$signature = $_REQUEST['firma'];
}

if(isset($_REQUEST['merchantId'])){
	$merchantId = $_REQUEST['merchantId'];
} else {
	$merchantId = $_REQUEST['usuario_id'];
}
if(isset($_REQUEST['referenceCode'])){
	$referenceCode = $_REQUEST['referenceCode'];
} else {
	$referenceCode = $_REQUEST['ref_venta'];
}
if(isset($_REQUEST['TX_VALUE'])){
	$value = $_REQUEST['TX_VALUE'];
} else {
	$value = $_REQUEST['valor'];
}
if(isset($_REQUEST['currency'])){
	$currency = $_REQUEST['currency'];
} else {
	$currency = $_REQUEST['moneda'];
}
if(isset($_REQUEST['transactionState'])){
	$transactionState = $_REQUEST['transactionState'];
} else {
	$transactionState = $_REQUEST['estado'];
}

$value = number_format($value, 1, '.', '');

$payu = new WC_Payu_Latam;
$api_key = $payu->get_api_key();
$signature_local = $api_key . '~' . $merchantId . '~' . $referenceCode . '~' . $value . '~' . $currency . '~' . $transactionState;
$signature_md5 = md5($signature_local);

if(isset($_REQUEST['polResponseCode'])){
	$polResponseCode = $_REQUEST['polResponseCode'];
} else {
	$polResponseCode = $_REQUEST['codigo_respuesta_pol'];
}

$agradecimiento = '';
//$order = new WC_Order($referenceCode);
$number = 1000000;
$order = new WC_Order($referenceCode - $number);
if($transactionState == 6 && $polResponseCode == 5){
	$estadoTx = "Transacci&oacute;n fallida";
	$order->update_status( 'Failed' );
} else if($transactionState == 6 && $polResponseCode == 4){
	$estadoTx = "Transacci&oacute;n rechazada";
	$order->update_status( 'Rejected' );
} else if($transactionState == 12 && $polResponseCode == 9994){
	$estadoTx = "Pendiente, Por favor revisar si el d&eacute;bito fue realizado en el Banco";
	$order->update_status( 'On hold' );
} else if($transactionState == 4 && $polResponseCode == 1){
	$estadoTx = "Transacci&oacute;n aprobada";
	$agradecimiento = '¡Gracias por tu compra!';
	$order->update_status( 'completed' ); 
} else{
	if(isset($_REQUEST['message'])){
		$estadoTx=$_REQUEST['message'];
	} else {
		$estadoTx=$_REQUEST['mensaje'];
	}
}

if(isset($_REQUEST['transactionId'])){
	$transactionId = $_REQUEST['transactionId'];
} else {
	$transactionId = $_REQUEST['transaccion_id'];
}
if(isset($_REQUEST['reference_pol'])){
	$reference_pol = $_REQUEST['reference_pol'];
} else {
	$reference_pol = $_REQUEST['ref_pol'];
}
if(isset($_REQUEST['pseBank'])){
	$pseBank = $_REQUEST['pseBank'];
} else {
	$pseBank = $_REQUEST['banco_pse'];
}
$cus = $_REQUEST['cus'];
if(isset($_REQUEST['description'])){
	$description = $_REQUEST['description'];
} else {
	$description = $_REQUEST['descripcion'];
}
if(isset($_REQUEST['lapPaymentMethod'])){
	$lapPaymentMethod = $_REQUEST['lapPaymentMethod'];
} else {
	$lapPaymentMethod = $_REQUEST['medio_pago_lap'];
}

#$result =  search_plan_by_name($description, $transactionState);

if (strtoupper($signature) == strtoupper($signature_md5)) {
?>
	<section class="response-section">
		<span class="sub-title">Gracias por tu compra.</span>
		<h2>Datos de la Transacción</h2>
		<ul class="pay_details">
			<li>
				<p>
					<span>Estado de la transacción</span>
					<span><?php echo $estadoTx; ?></span>	
				</p>
			</li>
			<li>
				<p>
					<span>ID de la transacci&oacute;n</span>
					<span><?php echo $transactionId; ?></span>
				</p>
			</li>
			<li>
				<p>
					<span>Referencia de la venta</span>
					<span><?php echo $reference_pol; ?></span>	
				</p>
			</li>
			<li>
				<p>
					<span>Referencia de la transacci&oacute;n</span>
					<span><?php echo $referenceCode; ?></span>	
				</p>
			</li>
			<li>
				<p>
					<span>Valor total</span>
					<span>$<?php echo $value; ?> </span>	
				</p>
			</li>
			<li>
				<p>
					<span>Moneda</span>
					<span><?php echo $currency; ?></span>
				</p>
			</li>
			<li>
				<p>
					<span>Descripción</span>
					<span><?php echo $description; ?></span>	
				</p>
			</li>
			<li>
				<p>
					<span>Entidad</span>
					<span><?php echo $lapPaymentMethod; ?></span>	
				</p>
			</li>
		</ul>
		<p> <strong><?php echo $result; ?> </strong> </p>
	</section>
<?php
} else {
	echo '<h1><center>La petici&oacute;n es incorrecta! Hay un error en la firma digital.</center></h1>';
}
get_footer('shop');
?>