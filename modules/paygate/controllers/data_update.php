<?php
include(dirname(__FILE__).'/../../../config/config.inc.php');
include(dirname(__FILE__).'/../paygate.php');

$tid = $_POST['tid'];
$transactionstatus = $_POST['transactionstatus'];

//$tid = 'paygatekr_2014-1-14.134292309';
//$transactionstatus = '304212';

if($transactionstatus == '304212' || $transactionstatus == '304712') { // 304212 는 일본계좌 입금완료, // 304712 는 인페이쪽 입금완료
	$id_order = Db::getInstance()->getValue('SELECT a.`id_order` FROM `'._DB_PREFIX_.'orders` a, `'._DB_PREFIX_.'order_payment` b where a.`reference` = b.`order_reference` and b.`transaction_id`=\''.$tid.'\'');

	$history = new OrderHistory();
	$history->changeIdOrderState(Configuration::get('PS_OS_PAYMENT'), $id_order);
	$history->id_order=$id_order;
	$history->id_order_state=2;
	$history->add();
}

echo '<VERIFYRECEIVED>RCVD</VERIFYRECEIVED>';

/* 거래 검증
$data = "0000devbasic_2013-1-7.134027940112341000KRW";
$salt = "123!";
$hashresult = hash('sha256',$salt.$data);
echo $hashresult;
*/



