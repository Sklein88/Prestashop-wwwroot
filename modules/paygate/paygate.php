<?php
/*
* 2007-2011 PrestaShop 
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2011 PrestaShop SA
*  @version  Release: $Revision: 6798 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_CAN_LOAD_FILES_'))
	exit;

class Paygate extends PaymentModule
{
	public function __construct()
	{
		$this->name = 'paygate';
		$this->tab = 'payments_gateways';
		$this->version = '2.3';

		parent::__construct();

		$this->page = basename(__FILE__, '.php');
		$this->displayName = $this->l('Paygate');
		$this->description = $this->l('Accepts payments by credit cards (Visa, MasterCard, Amex, JCB) with PAYGATE.');
		$this->confirmUninstall = $this->l('Are you sure you want to delete your details ?');
		//이메일이 key인 경우
		//	$this->warning = $this->l('You are currently using the default Paygate e-mail address, please use your own e-mail address.');

		/* Paygate payment methods */
		$this->_internationalPaymentMethods = array(
		0 => array('file' => 'visa', 'name' => 'Visa', 'code' => 'VSA'),
		1 => array('file' => 'mastercard', 'name' => 'Mastercard', 'code' => 'MSC'),
		2 => array('file' => 'amex', 'name' => 'American Express', 'code' => 'AMX'),
		3 => array('file' => 'jcb', 'name' => 'JCB', 'code' => 'JCB'));
		/*3 => array('file' => 'maestro', 'name' => 'Maestro', 'code' => 'MAE'),
		6 => array('file' => 'visadebit', 'name' => 'Visa Debit', 'code' => 'VSD'),
		7 => array('file' => 'ewallet', 'name' => 'Moneybookers eWallet', 'code' => 'WLT'));
		1 => array('file' => 'diners', 'name' => 'Diners', 'code' => 'DIN'),*/
		
		/* Paygate countries (for iso 3 letters compatibility) */
		$this->_country = array('AF' => 'AFG', 'AL' => 'ALB', 'DZ' => 'DZA', 'AS' => 'ASM', 'AD' => 'AND', 'AO' => 'AGO', 'AI' => 'AIA', 'AQ' => 'ATA', 'AG' => 'ATG', 'AR' => 'ARG',
		'AM' => 'ARM', 'AW' => 'ABW', 'AU' => 'AUS', 'AT' => 'AUT', 'AZ' => 'AZE', 'BS' => 'BHS', 'BH' => 'BHR', 'BD' => 'BGD', 'BB' => 'BRB', 'BY' => 'BLR', 'BE' => 'BEL',
		'BZ' => 'BLZ', 'BJ' => 'BEN', 'BM' => 'BMU', 'BT' => 'BTN', 'BO' => 'BOL', 'BA' => 'BIH', 'BW' => 'BWA', 'BV' => 'BVT', 'BR' => 'BRA', 'IO' => 'IOT', 'BN' => 'BRN',
		'BG' => 'BGR', 'BF' => 'BFA', 'BI' => 'BDI', 'KH' => 'KHM', 'CM' => 'CMR', 'CA' => 'CAN', 'CV' => 'CPV', 'KY' => 'CYM', 'CF' => 'CAF', 'TD' => 'TCD', 'CL' => 'CHL',
		'CN' => 'CHN', 'CX' => 'CXR', 'CC' => 'CCK', 'CO' => 'COL', 'KM' => 'COM', 'CG' => 'COG', 'CK' => 'COK', 'CR' => 'CRI', 'CI' => 'CIV', 'HR' => 'HRV', 'CU' => 'CUB',
		'CY' => 'CYP', 'CZ' => 'CZE', 'DK' => 'DNK', 'DJ' => 'DJI', 'DM' => 'DMA', 'DO' => 'DOM', 'TP' => 'TMP', 'EC' => 'ECU', 'EG' => 'EGY', 'SV' => 'SLV', 'GQ' => 'GNQ',
		'ER' => 'ERI', 'EE' => 'EST', 'ET' => 'ETH', 'FK' => 'FLK', 'FO' => 'FRO', 'FJ' => 'FJI', 'FI' => 'FIN', 'FR' => 'FRA', 'FX' => 'FXX', 'GF' => 'GUF', 'PF' => 'PYF',
		'TF' => 'ATF', 'GA' => 'GAB', 'GM' => 'GMB', 'GE' => 'GEO', 'DE' => 'DEU', 'GH' => 'GHA', 'GI' => 'GIB', 'GR' => 'GRC', 'GL' => 'GRL', 'GD' => 'GRD', 'GP' => 'GLP',
		'GU' => 'GUM', 'GT' => 'GTM', 'GN' => 'GIN', 'GW' => 'GNB', 'GY' => 'GUY', 'HT' => 'HTI', 'HM' => 'HMD', 'HN' => 'HND', 'HK' => 'HKG', 'HU' => 'HUN', 'IS' => 'ISL',
		'IN' => 'IND', 'ID' => 'IDN', 'IR' => 'IRN', 'IQ' => 'IRQ', 'IE' => 'IRL', 'IL' => 'ISR', 'IT' => 'ITA', 'JM' => 'JAM', 'JP' => 'JPN', 'JO' => 'JOR', 'KZ' => 'KAZ',
		'KE' => 'KEN', 'KI' => 'KIR', 'KP' => 'PRK', 'KR' => 'KOR', 'KW' => 'KWT', 'KG' => 'KGZ', 'LA' => 'LAO', 'LV' => 'LVA', 'LB' => 'LBN', 'LS' => 'LSO', 'LR' => 'LBR',
		'LY' => 'LBY', 'LI' => 'LIE', 'LT' => 'LTU', 'LU' => 'LUX', 'MO' => 'MAC', 'MK' => 'MKD', 'MG' => 'MDG', 'MW' => 'MWI', 'MY' => 'MYS', 'MV' => 'MDV', 'ML' => 'MLI',
		'MT' => 'MLT', 'MH' => 'MHL', 'MQ' => 'MTQ', 'MR' => 'MRT', 'MU' => 'MUS', 'YT' => 'MYT', 'MX' => 'MEX', 'FM' => 'FSM', 'MD' => 'MDA', 'MC' => 'MCO', 'MN' => 'MNG',
		'MS' => 'MSR', 'MA' => 'MAR', 'MZ' => 'MOZ', 'MM' => 'MMR', 'NA' => 'NAM', 'NR' => 'NRU', 'NP' => 'NPL', 'NL' => 'NLD', 'AN' => 'ANT', 'NC' => 'NCL', 'NZ' => 'NZL',
		'NI' => 'NIC', 'NE' => 'NER', 'NG' => 'NGA', 'NU' => 'NIU', 'NF' => 'NFK', 'MP' => 'MNP', 'NO' => 'NOR', 'OM' => 'OMN', 'PK' => 'PAK', 'PW' => 'PLW', 'PA' => 'PAN',
		'PG' => 'PNG', 'PY' => 'PRY', 'PE' => 'PER', 'PH' => 'PHL', 'PN' => 'PCN', 'PL' => 'POL', 'PT' => 'PRT', 'PR' => 'PRI', 'QA' => 'QAT', 'RE' => 'REU', 'RO' => 'ROM',
		'RU' => 'RUS', 'RW' => 'RWA', 'KN' => 'KNA', 'LC' => 'LCA', 'VC' => 'VCT', 'WS' => 'WSM', 'SM' => 'SMR', 'ST' => 'STP', 'SA' => 'SAU', 'SN' => 'SEN', 'SC' => 'SYC',
		'SL' => 'SLE', 'SG' => 'SGP', 'SK' => 'SVK', 'SI' => 'SVN', 'SB' => 'SLB', 'SO' => 'SOM', 'ZA' => 'ZAF', 'GS' => 'SGS', 'ES' => 'ESP', 'LK' => 'LKA', 'SH' => 'SHN',
		'PM' => 'SPM', 'SD' => 'SDN', 'SR' => 'SUR', 'SJ' => 'SJM', 'SZ' => 'SWZ', 'SE' => 'SWE', 'CH' => 'CHE', 'SY' => 'SYR', 'TW' => 'TWN', 'TJ' => 'TJK', 'TZ' => 'TZA',
		'TH' => 'THA', 'TG' => 'TGO', 'TK' => 'TKL', 'TO' => 'TON', 'TT' => 'TTO', 'TN' => 'TUN', 'TR' => 'TUR', 'TM' => 'TKM', 'TC' => 'TCA', 'TV' => 'TUV', 'UG' => 'UGA',
		'UA' => 'UKR', 'AE' => 'ARE', 'GB' => 'GBR', 'US' => 'USA', 'UM' => 'UMI', 'UY' => 'URY', 'UZ' => 'UZB', 'VU' => 'VUT', 'VA' => 'VAT', 'VE' => 'VEN', 'VN' => 'VNM',
		'VG' => 'VGB', 'VI' => 'VIR', 'WF' => 'WLF', 'EH' => 'ESH', 'YE' => 'YEM', 'YU' => 'YUG', 'ZR' => 'ZAR', 'ZM' => 'ZMB', 'ZW' => 'ZWE');
	}

	public function install()
	{
		if (!parent::install() OR !$this->registerHook('payment') OR !$this->registerHook('paymentReturn'))
			return false;
				
		Configuration::updateValue('PG_K_MID', '');
		Configuration::updateValue('PG_F_MID', '');
		Configuration::updateValue('PG_SECRETKEY', '');
		Configuration::updateValue('PG_LANG', 'US');
		Configuration::updateValue('PG_PAYMETHOD_K_CARD', '');
		Configuration::updateValue('PG_PAYMETHOD_K_PHONE', '');
		Configuration::updateValue('PG_PAYMETHOD_K_RTBT', '');
		Configuration::updateValue('PG_PAYMETHOD_F_CARD', '');
		Configuration::updateValue('PG_PAYMETHOD_ALIPAY', '');		
		Configuration::updateValue('PG_PAYMETHOD_J_BTNOTICE', '');
		Configuration::updateValue('PG_PAYMETHOD_INPAY', '');
		Configuration::updateValue('PG_PAYMETHOD_CHINAPAY', '');
		return true;
	}

	public function uninstall()
	{
		if (!parent::uninstall())
			return false;

		/* Clean configuration table */
		Configuration::deleteByName('PG_K_MID');
		Configuration::deleteByName('PG_F_MID');
		Configuration::deleteByName('PG_SECRETKEY');
		Configuration::deleteByName('PG_LANG');
		Configuration::deleteByName('PG_PAYMETHOD_K_CARD');
		Configuration::deleteByName('PG_PAYMETHOD_K_PHONE');
		Configuration::deleteByName('PG_PAYMETHOD_K_RTBT');
		Configuration::deleteByName('PG_PAYMETHOD_F_CARD');
		Configuration::deleteByName('PG_PAYMETHOD_ALIPAY');
		Configuration::deleteByName('PG_PAYMETHOD_J_BTNOTICE');
		Configuration::deleteByName('PG_PAYMETHOD_INPAY');
		Configuration::deleteByName('PG_PAYMETHOD_CHINAPAY');
		return true;
	}

	public function getContent()
	{
		//$this->_html .= '<h2>'.$this->l('Paygate').'</h2>';	
		
		$this->_postProcess();
		
		$this->_setSubscription();
		
		$this->_setConfiguration();
		
		return $this->_html;
	}
	
	public function _postProcess()
	{
		global $cookie;

		$this->_html .'
		<p><img src="'.__PS_BASE_URI__.'modules/paygate/img/logo-mb.gif" alt="Paygate" /></p><br />';

		$errors = array();

		/* Update configuration variables */
		if (isset($_POST['submitPaygate']))
		{
			Configuration::updateValue('PG_K_MID', $_POST['pg_k_mid']);
			Configuration::updateValue('PG_F_MID', $_POST['pg_f_mid']);
			Configuration::updateValue('PG_SECRETKEY', $_POST['pg_secretkey']);
			Configuration::updateValue('PG_LANG', $_POST['pg_lang']);
			Configuration::updateValue('PG_PAYMETHOD_K_CARD', $_POST['pg_paymethod_k_card']);
			Configuration::updateValue('PG_PAYMETHOD_K_PHONE', $_POST['pg_paymethod_k_phone']);
			Configuration::updateValue('PG_PAYMETHOD_K_RTBT', $_POST['pg_paymethod_k_rtbt']);
			Configuration::updateValue('PG_PAYMETHOD_F_CARD', $_POST['pg_paymethod_f_card']);
			Configuration::updateValue('PG_PAYMETHOD_ALIPAY', $_POST['pg_paymethod_alipay']);
			Configuration::updateValue('PG_PAYMETHOD_J_BTNOTICE', $_POST['pg_paymethod_j_btnotice']);
			Configuration::updateValue('PG_PAYMETHOD_INPAY', $_POST['pg_paymethod_inpay']);
			Configuration::updateValue('PG_PAYMETHOD_CHINAPAY', $_POST['pg_paymethod_chinapay']);
		}

		/* Display errors */
		if (sizeof($errors))
		{
			$this->_html .= '<ul style="color: red; font-weight: bold; margin-bottom: 30px; width: 506px; background: #FFDFDF; border: 1px dashed #BBB; padding: 10px;">';
			foreach ($errors AS $error)
				$this->_html .= '<li>'.$error.'</li>';
			$this->_html .= '</ul>';
		}		
	}
	
	public function _setSubscription()
	{
		/* Display settings form */
		$this->_html .= '
		<br /><br />
		<fieldset class="width2" style="float: right; width: 440px; height: 80px; border: padding: 8px; margin-left: 12px;">
		<legend><img src="'.__PS_BASE_URI__.'modules/paygate/img/logo_small.png" alt="" />'.$this->l('Open your Paygate account').'</legend>
		'.$this->l('If you do not have your Paygate account, <br />please clicking on the following image for opening your Paygate account.').'</p>
		<p><a href="http://www.paygate.net"><img src="../modules/paygate/img/logo-sign.gif" alt="PrestaShop & Paygate" /></a><br />
		<br />
		</fieldset>
		<img src="../modules/paygate/img/paygate_global.jpg" style="float:left; margin-left:25px; margin-right:25px;" />
		</br><b>'.$this->l('This module allows you to accept payments by Paygate.').'</b><br /><br /><br />'.
		$this->l('- <b>PAYGATE</b> is offering a suite of top-grade e-commerce solutions and services with billing and collecting capability <br>'.'&nbsp;from all over the world and designed to give comprehensive online payment method for global internet shoppers.').'<br /><br />
		<p>'.$this->l('You need to configure your Paygate account before using this module.').'<br />
		<div style="clear: both;">&nbsp;</div>';
	}
	
	public function _setConfiguration()
	{
		$this->_html .= '
		<br /><br />
		<form method="post" action="'.htmlentities($_SERVER['REQUEST_URI']).'">	
			<script type="text/javascript">
				var pos_select = '.(($tab = (int)Tools::getValue('tabs')) ? $tab : '0').';
			</script>
			<input type="hidden" name="tabs" id="tabs" value="0" />
			<div id="tab-pane-1" style="width:100%;">
				 <div id="step1">
					'.$this->_setMerchantInfo().'
				</div>
			</div>			
		</form>';		
	}
	
	// _setConfiguration 에서 호출함
	public function _setMerchantInfo()
	{	
		global $cookie;

		$lang = (Tools::getValue('pg_lang', Configuration::get('PG_LANG')));
		$pg_paymethod_k_card = (Tools::getValue('pg_paymethod_k_card', Configuration::get('PG_PAYMETHOD_K_CARD')));
		$pg_paymethod_k_phone = (Tools::getValue('pg_paymethod_k_phone', Configuration::get('PG_PAYMETHOD_K_PHONE')));
		$pg_paymethod_k_rtbt = (Tools::getValue('pg_paymethod_k_rtbt', Configuration::get('PG_PAYMETHOD_K_RTBT')));
		$pg_paymethod_f_card = (Tools::getValue('pg_paymethod_f_card', Configuration::get('PG_PAYMETHOD_F_CARD')));
		$pg_paymethod_alipay = (Tools::getValue('pg_paymethod_alipay', Configuration::get('PG_PAYMETHOD_ALIPAY')));
		$pg_paymethod_j_btnotice = (Tools::getValue('pg_paymethod_j_btnotice', Configuration::get('PG_PAYMETHOD_J_BTNOTICE')));
		$pg_paymethod_inpay = (Tools::getValue('pg_paymethod_inpay', Configuration::get('PG_PAYMETHOD_INPAY')));
		$pg_paymethod_chinapay = (Tools::getValue('pg_paymethod_chinapay', Configuration::get('PG_PAYMETHOD_CHINAPAY')));
		
		$html = '
			<h2 style="clear:both;">'.$this->l('&nbsp;&nbsp;● Merchant Settings').'</h2>

			<label>● MID :</label>
			<div class="margin-form" >
				국내 : <input type="text" name="pg_k_mid" value="'.htmlentities(Tools::getValue('pg_k_mid', Configuration::get('PG_K_MID')), ENT_COMPAT, 'UTF-8').'" size="25" />&nbsp;&nbsp;
				해외 : <input type="text" name="pg_f_mid" value="'.htmlentities(Tools::getValue('pg_f_mid', Configuration::get('PG_F_MID')), ENT_COMPAT, 'UTF-8').'" size="25" />
			</div>

			<label>● SecretKey :</label>
			<div class="margin-form">
				<input type="text" name="pg_secretkey" value="'.htmlentities(Tools::getValue('pg_secretkey', Configuration::get('PG_SECRETKEY')), ENT_COMPAT, 'UTF-8').'" size="50" />
			</div>

			<label>● Language :</label>
			<div class="margin-form" style="padding-top:2px;">
				<input type="radio" name="pg_lang" value="US" '.($lang == "US" ? 'checked="checked" ' : '').'/> <label for="sandbox_mode_1" class="t">'.$this->l('English').'</label> 
				<input type="radio" name="pg_lang" value="JP" style="margin-left:15px;" '.($lang == "JP" ? 'checked="checked" ' : '').'/> <label for="sandbox_mode_1" class="t">'.$this->l('Japanese').'</label> 
				<input type="radio" name="pg_lang" value="KR" style="margin-left:15px;" '.($lang == "KR" ? 'checked="checked" ' : '').'/> <label for="sandbox_mode_1" class="t">'.$this->l('Korean').'</label> 
				<input type="radio" name="pg_lang" value="CN" style="margin-left:15px;" '.($lang == "CN" ? 'checked="checked" ' : '').'/> <label for="sandbox_mode_1" class="t">'.$this->l('Chinese').'</label> 
			</div>
			
			<label>● Select Paymethod :</label>
			<div class="margin-form" style="padding-top:2px;">
				<input type="checkbox" name="pg_paymethod_k_card" value="K_CARD" style="margin-left:0px;" '.($pg_paymethod_k_card == "K_CARD" ? 'checked="true" ' : '').'/>
				<label for="sandbox_mode_1" class="t">'.$this->l('CREDIT CARD (issued In KOREA)').'</label><br><br>
				<input type="checkbox" name="pg_paymethod_k_phone" value="K_PHONE" style="margin-left:0px;" '.($pg_paymethod_k_phone == "K_PHONE" ? 'checked="true" ' : '').'/>
				<label for="sandbox_mode_1" class="t">'.$this->l('Korea Phone').'</label><br><br>
				<input type="checkbox" name="pg_paymethod_k_rtbt" value="K_RTBT" style="margin-left:0px;" '.($pg_paymethod_k_rtbt == "K_RTBT" ? 'checked="true" ' : '').'/>
				<label for="sandbox_mode_1" class="t">'.$this->l('Korea RTBT').'</label><br><br>
				<input type="checkbox" name="pg_paymethod_f_card" value="F_CARD" style="margin-left:0px;" '.($pg_paymethod_f_card == "F_CARD" ? 'checked="true" ' : '').'/>
				<label for="sandbox_mode_1" class="t">'.$this->l('CREDIT CARD (Visa, Master, Jcb, Amex.)').'</label><br><br>
				<input type="checkbox" name="pg_paymethod_alipay" value="ALIPAY" style="margin-left:0px;" '.($pg_paymethod_alipay == "ALIPAY" ? 'checked="true" ' : '').'/>
				<label for="sandbox_mode_1" class="t">'.$this->l('ALIPAY').'</label><br><br>
				<input type="checkbox" name="pg_paymethod_j_btnotice" value="J_BTNOTICE" style="margin-left:0px;" '.($pg_paymethod_j_btnotice == "J_BTNOTICE" ? 'checked="true" ' : '').'/>
				<label for="sandbox_mode_1" class="t">'.$this->l('JAPAN BANK WIRE').'</label><br><br>
				<input type="checkbox" name="pg_paymethod_inpay" value="INPAY" style="margin-left:0px;" '.($pg_paymethod_inpay == "INPAY" ? 'checked="true" ' : '').'/>
				<label for="sandbox_mode_1" class="t">'.$this->l('INPAY').'</label><br><br>
				<input type="checkbox" name="pg_paymethod_chinapay" value="CHINAPAY" style="margin-left:0px;" '.($pg_paymethod_chinapay == "CHINAPAY" ? 'checked="true" ' : '').'/>
				<label for="sandbox_mode_1" class="t">'.$this->l('CHINAPAY').'</label><br><br>
			</div>

			<label>● 환율 변환</label>
			<div class="margin-form" style="padding-top:2px;">
				 결제 화폐에 맞는 환율 변경은 상점 제공 화폐단위를 우선시.<br> 
				상점에서 제공되지 않는 화폐단위일시에 페이게이트에서 자체변환
			</div>
		
			<p class="center"><input class="button" type="submit" name="submitPaygate" value="'.$this->l('Save settings').'" /></p>
			';		
		return $html;		
	}
	
	// USD 화폐단위를 사용하는지 확인.
	public function checkUsd(){	
		
		$currencies = Currency::getCurrencies(true,false);
		//$returnVal = false;
		$usdseq = -1;
		/*
		foreach($currencies as $value)
		{	$usdseq = $usdseq+1;
			if($value->iso_code == "USD"){
				$returnVal = true;
			}
		}*/	
	
		for($i=0; $i<count($currencies);$i++){
			$temp_currency =  new Currency((int)($i));
			if($temp_currency->iso_code == "USD"){
				$usdseq = $i;
			}
		}
		return $usdseq;
	}

	// JPY 화폐단위를 사용하는지 확인.   
	public function checkJPY(){	
		
		$currencies = Currency::getCurrencies(true,false);		
		$usdseq = -1;	
	
		for($i=1; $i<=count($currencies);$i++){
			$temp_currency =  new Currency((int)($i));			

			if($temp_currency->iso_code == "JPY"){
				$usdseq = $i;
			}
		}
		return $usdseq;
	}

		// KRW 화폐단위를 사용하는지 확인.   
	public function checkKRW(){	
		
		$currencies = Currency::getCurrencies(true,false);		
		$usdseq = -1;	
	
		for($i=1; $i<=count($currencies);$i++){
			$temp_currency =  new Currency((int)($i));			

			if($temp_currency->iso_code == "KRW"){
				$usdseq = $i;
			}
		}
		return $usdseq;
	}

	public function hookPayment($params)
	{
		//global $smarty;
		
		if (!Configuration::get('PG_K_MID') && !Configuration::get('PG_F_MID'))
			return;
		
		$pg_k_mid = (Tools::getValue('pg_k_mid', Configuration::get('PG_K_MID')));
		$pg_f_mid = (Tools::getValue('pg_f_mid', Configuration::get('PG_F_MID')));

		// 국내,해외 mid 중 값이 없으면 있는값으로 대체.
		if(!$pg_k_mid){$pg_k_mid=$pg_f_mid;}
		if(!$pg_f_mid){$pg_f_mid=$pg_k_mid;}	

		/*$smarty->assign(array(
			'this_path' => $this->_path,
			'this_path_ssl' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->name.'/'
		));
		return $this->display(__FILE__, 'payment.tpl');*/
		
		$address = new Address((int)($params['cart']->id_address_delivery));
		$countryObj = new Country((int)($address->id_country), Configuration::get('PS_LANG_DEFAULT'));
		$customer = new Customer((int)($params['cart']->id_customer));
		$currency = new Currency((int)($params['cart']->id_currency));
		
		
		$ref = (int)($params['cart']->id).'_'.date('YmdHis').'_'.$params['cart']->secure_key;
		$linkBuf = Configuration::get('PG_SECRETKEY'). "?mid=" . $pg_k_mid ."&ref=" . $ref ."&cur=" .$currency->iso_code ."&amt=" .(number_format($params['cart']->getOrderTotal(), 2, '.', ''));
		$fgkey = hash("sha256", $linkBuf);

		$goodoption1 = (int)($params['cart']->id);
		$goodoption2 = (int)($this->id);
		$goodoption3 = $customer->secure_key;
		
		// 결제 정보를 인서트
		$statusurl = (Configuration::get('PS_SSL_ENABLED') ? "https" : "http")."://".$_SERVER['HTTP_HOST'].__PS_BASE_URI__."modules/".$this->name."/controllers/validation.php";
		// 결제후에 고객에게 보여지는 페이지
		$returnurl = (Configuration::get('PS_SSL_ENABLED') ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].__PS_BASE_URI__.'order-confirmation.php?id_cart='.$goodoption1.'&id_module='.$goodoption2.'&key='.$goodoption3;

		//$order = new Order((int)($params['cart']->id));

		$req_currency = $currency->iso_code;
		$req_amt = number_format($params['cart']->getOrderTotal(), 2, '.', '');
		
		$usd_amt = $req_amt;
		$usd_currency = $req_currency;
		$jpy_amt = $req_amt;
		$jpy_currency = $req_currency;
		$won_amt = $req_amt;
		$won_currency = $req_currency;		
		
		if(paygate::checkUsd()>0){
			$usd_amt = Tools::convertPriceFull($params['cart']->getOrderTotal(),$currency,new Currency((int)(paygate::checkUsd())));
			$usd_currency = 'USD';
		}

		//일본 계좌이체를 위한 셋팅
		if(paygate::checkJPY()>0){
			$jpy_amt = round(Tools::convertPriceFull($params['cart']->getOrderTotal(),$currency,new Currency((int)(paygate::checkJPY()))));
			$jpy_currency = 'JPY';
		}
		
		// 계좌이체
		if(paygate::checkKRW()>0){
			$won_amt = Tools::convertPriceFull($params['cart']->getOrderTotal(),$currency,new Currency((int)(paygate::checkKRW())));
			$won_currency = 'WON';
		}

		/*echo
		'<script>
			alert("'.$params['cart']->getOrderTotal().'");
			alert("'.$currency_test->iso_code.'");
			alert("'.$convertPrice2.'");
		</script>
		';
		*/

		$pg_paymethod_k_card = (Tools::getValue('pg_paymethod_k_card', Configuration::get('PG_PAYMETHOD_K_CARD')));
		$pg_paymethod_k_phone = (Tools::getValue('pg_paymethod_k_phone', Configuration::get('PG_PAYMETHOD_K_PHONE')));
		$pg_paymethod_k_rtbt = (Tools::getValue('pg_paymethod_k_rtbt', Configuration::get('PG_PAYMETHOD_K_RTBT')));
		$pg_paymethod_f_card = (Tools::getValue('pg_paymethod_f_card', Configuration::get('PG_PAYMETHOD_F_CARD')));
		$pg_paymethod_alipay = (Tools::getValue('pg_paymethod_alipay', Configuration::get('PG_PAYMETHOD_ALIPAY')));
		$pg_paymethod_j_btnotice = (Tools::getValue('pg_paymethod_j_btnotice', Configuration::get('PG_PAYMETHOD_J_BTNOTICE')));
		$pg_paymethod_inpay = (Tools::getValue('pg_paymethod_inpay', Configuration::get('PG_PAYMETHOD_INPAY')));
		$pg_paymethod_chinapay = (Tools::getValue('pg_paymethod_chinapay', Configuration::get('PG_PAYMETHOD_CHINAPAY')));

		$cart = new Cart((int)$params['cart']->id);
		$products = $cart->getProducts();		

		$goodname = '';

		foreach ($products as $product) // products refer to the cart details
		{		
			$goodname = $goodname.$product['name'].'x'.(int)$product['quantity'].', ';			
		}
		
		if(strlen($goodname)>100){
			$goodname = substr($goodname,0,100);
		}
		
		$html = '
<script language="javascript" src="https://api.paygate.net/ajax/common/OpenPayAPI.js" charset="UTF-8"> </script>

<script type="text/javascript">				
	
	var isProssing = "off";
	function payWithPaygate() {
		if(isProssing=="on"){
			alert("Payment is already in progress.");
			return;
		}

		f_paramInit();

		f_disabled();

		var doc_radio = document.getElementsByName("ra_select");
		var check_button;
		for(var i=0; i<doc_radio.length;i++){
			if(doc_radio[i].checked == true){
				check_button = i;
			}
		}
		var sel_paymethod = doc_radio[check_button].value;

		f_choseRadio(sel_paymethod);

		if(sel_paymethod=="7"){
			if(document.getElementById("goodcurrency").value!="JPY"){						
				alert("JPY(currency information) has not been set on the online shopping mall.");
				return;
			}
			if(document.getElementById("banksendername").value==""){
				alert("送金者名は必須です。");
				return;
			}
		}
		
		document.getElementById("paymethod").value = sel_paymethod;
		document.getElementById("PGIOscreen").style.display = "block";
		document.getElementById("PGIOscreen").style.backGroudColor = "gray";
		isProssing = "on";

		//alert(getPGIOElement("payresultcode"));
		doTransaction(document.PGIOForm);
		//testPay();
	}

	function f_disabled(){
		var id_array = ["ra_sel1","ra_sel2","ra_sel3","ra_sel4","ra_sel5","ra_sel6","ra_sel7","ra_sel8"];
		for(var i=0; i<id_array.length;i++){
			if(document.getElementById(id_array[i])){
				document.getElementById(id_array[i]).disabled=true;
			}
		}
	}

	function f_enabled(){
		var id_array = ["ra_sel1","ra_sel2","ra_sel3","ra_sel4","ra_sel5","ra_sel6","ra_sel7","ra_sel8"];
		for(var i=0; i<id_array.length;i++){
			if(document.getElementById(id_array[i])){
				document.getElementById(id_array[i]).disabled=false;
			}
		}		
	}

	function f_choseRadio(sel_paymethod){
		if(sel_paymethod=="card"){
			document.getElementById("mid").value = "'.$pg_k_mid.'";
			document.getElementById("unitprice").value = "'.$won_amt.'";
			document.getElementById("goodcurrency").value = "'.$won_currency.'";
			document.getElementById("langcode").value = "KR";
			document.getElementById("paymethod_name").value = "PG_K_CARD";

		}else if(sel_paymethod=="801"){
			document.getElementById("mid").value = "'.$pg_k_mid.'";
			document.getElementById("unitprice").value = "'.$won_amt.'";
			document.getElementById("goodcurrency").value = "'.$won_currency.'";
			document.getElementById("langcode").value = "KR";
			document.getElementById("receipttotel").value = "";
			document.getElementById("paymethod_name").value = "PG_K_PHONE";

		}else if(sel_paymethod=="4"){
			document.getElementById("mid").value = "'.$pg_k_mid.'";
			document.getElementById("unitprice").value = "'.$won_amt.'";
			document.getElementById("goodcurrency").value = "'.$won_currency.'";
			document.getElementById("langcode").value = "KR";
			document.getElementById("paymethod_name").value = "PG_K_RTBT";

		}else if(sel_paymethod=="104") {
			document.getElementById("mid").value = "'.$pg_f_mid.'";
			document.getElementById("unitprice").value = "'.$usd_amt.'";
			document.getElementById("goodcurrency").value = "'.$usd_currency.'";
			document.getElementById("langcode").value = "US";
			document.getElementById("paymethod_name").value = "PG_F_CARD";

		}else if(sel_paymethod=="106") {
			document.getElementById("mid").value = "'.$pg_f_mid.'";
			document.getElementById("unitprice").value = "'.$usd_amt.'";
			document.getElementById("goodcurrency").value = "'.$usd_currency.'";
			document.getElementById("langcode").value = "CN";
			document.getElementById("paymethod_name").value = "PG_ALIPAY";

		}else if(sel_paymethod=="7") {
			document.getElementById("mid").value = "'.$pg_f_mid.'";
			document.getElementById("paymethod_name").value = "PG_J_BANKWIRE";
			document.getElementById("langcode").value = "JP";
			document.getElementById("bankcode").value = "PG";
			document.getElementById("unitprice").value = "'.$jpy_amt.'";
			document.getElementById("goodcurrency").value = "'.$jpy_currency.'";
			document.getElementById("banksendername").value = document.getElementById("i_banksendername").value;
			document.getElementById("bankexpyear").value = document.getElementById("i_bankexpyear").value;
			document.getElementById("bankexpmonth").value = document.getElementById("i_bankexpmonth").value;
			document.getElementById("bankexpday").value = document.getElementById("i_bankexpday").value;

		}else if(sel_paymethod=="112") {
			document.getElementById("mid").value = "'.$pg_f_mid.'";
			document.getElementById("langcode").value = "US";
			document.getElementById("paymethod_name").value = "PG_INPAY";

		}else if(sel_paymethod=="113") {
			document.getElementById("mid").value = "'.$pg_f_mid.'";
			document.getElementById("unitprice").value = "'.$usd_amt.'";
			document.getElementById("goodcurrency").value = "'.$usd_currency.'";
			document.getElementById("langcode").value = "CN";
			document.getElementById("paymethod_name").value = "PG_CHINAPAY";

		}

		if(sel_paymethod!="7") {
			if(document.getElementById("div_j_btnotice")){
				document.getElementById("div_j_btnotice").style.display="none";
			}
		}

	}

	function f_paramInit(){
	
		document.PGIOForm.replycode.value = "";
		document.PGIOForm.replyMsg.value = "";
		document.PGIOForm.tid.value = "";	
		document.getElementById("bankcode").value = "";
		document.PGIOForm.unitprice.value="'.$req_amt.'";
		document.PGIOForm.goodcurrency.value="'.$req_currency.'"; 	
				
		setPGIOElement("profile_no", "");
		setPGIOElement("cardnumber", "");
		setPGIOElement("cardexpiremonth", "");
		setPGIOElement("cardexpireyear", "");
		setPGIOElement("cardsecretnumber", "");
		setPGIOElement("cardtype", "");
		setPGIOElement("orgcardtype", "");
		setPGIOElement("cardcode_name", "");
		setPGIOElement("payresultmsg", "");
		setPGIOElement("payresultcode", "");	
		setPGIOElement("trnsctn_st", "");
		
		setPGIOElement("mrchnt_no", "");
		setPGIOElement("mem_no", "");
		setPGIOElement("mem_nm", "");
		setPGIOElement("pay_rslt", "");
		setPGIOElement("pay_rslt_msg", "");
		setPGIOElement("trnsctn_dt", "");
		setPGIOElement("acquire_reqst_dt", "");
		setPGIOElement("sttl_dt", "");
		setPGIOElement("cncl_dt", "");
		setPGIOElement("rmt_from_bnk_cd", "");
		setPGIOElement("rmt_from_accnt_no", "");
		setPGIOElement("crd_tp", "");
		setPGIOElement("crd_no", "");
		setPGIOElement("cardnumber", "");
		setPGIOElement("crd_expr_mm", "");
		setPGIOElement("crd_expr_yyyy", "");
		setPGIOElement("crd_instll_cnt", "");
		setPGIOElement("crd_apprvl_no", "");
		setPGIOElement("usr_id", "");
		setPGIOElement("msg_rcptr", "");
		setPGIOElement("deal_crd_cmpny", "");
		setPGIOElement("memberredirecturl", "");
		setPGIOElement("MemberRedirectURL", "");
		setPGIOElement("bankcode", "");
		setPGIOElement("bankcode_name", "");
		setPGIOElement("bankcodename", "");
		setPGIOElement("bankaccount", "");
		setPGIOElement("trnsctn_st_nm", "");
		setPGIOElement("hashresult", "");
	}

	function getPGIOresult() {
		isProssing = "off";
		var replycode = getPGIOElement("replycode");
		var replyMsg = getPGIOElement("replyMsg");
		var cardnumber = getPGIOElement("cardnumber");

		if(replycode == "0000"){
			var frm = document.PGIOForm;
			frm.submit();
		}else{
			alert(replyMsg+" ["+replycode+"]");
			f_enabled();

			for(var i=0; i<document.getElementsByName("ra_select").length;i++){
				document.getElementsByName("ra_select")[i].checked = false;
			}

			document.getElementById("PGIOscreen").innerHTML = "";
			document.getElementById("PGIOscreen").style.display = "none";

			if(document.getElementById("div_j_btnotice")){
				document.getElementById("div_j_btnotice").style.display="none";
			}
			document.getElementById("banksendername").value = "";
			document.getElementById("bankexpyear").value = "";
			document.getElementById("bankexpmonth").value = "";
			document.getElementById("bankexpday").value = "";
			document.getElementById("i_banksendername").value = "";
			document.getElementById("i_bankexpyear").value = "";
			document.getElementById("i_bankexpmonth").value = "";
			document.getElementById("i_bankexpday").value = "";

			var id_array = ["banksendername","i_banksendername","bankexpyear","i_bankexpyear","bankexpmonth","i_bankexpmonth","bankexpday","i_bankexpday"];
			for(var i=0; i<id_array.length;i++){
				if(document.getElementById(id_array[i])){
					document.getElementById(id_array[i]).value="";
				}
			}
		}				
	}

	function testPay(){
		document.PGIOForm.replycode.value="0000";
		document.PGIOForm.replyMsg.value="SUCCESS";					
		getPGIOresult();
	}


</script>
		
		<p class="payment_module">	
			<div><img src="'.__PS_BASE_URI__.'modules/paygate/img/paygateLogo.png" alt="Pay with Paygate" /></div>
		';


	  if($pg_paymethod_k_card == "K_CARD"){
		 $html = $html.
			 '<input type="radio" id="ra_sel1" name="ra_select" value="card" onClick="javascript:payWithPaygate();">&nbsp;CREDIT CARD (issued In KOREA)
			<BR><BR>';
	  }

	  if($pg_paymethod_k_phone == "K_PHONE"){
		 $html = $html.
			 '<input type="radio" id="ra_sel2" name="ra_select" value="801" onClick="javascript:payWithPaygate();">&nbsp;Korea PHONE
			<BR><BR>';
	  }

	  if($pg_paymethod_k_rtbt == "K_RTBT"){
		 $html = $html.
			 '<input type="radio" id="ra_sel3" name="ra_select" value="4" onClick="javascript:payWithPaygate();">&nbsp;Korea RTBT
			<BR><BR>';
	  }	

	  if($pg_paymethod_f_card == "F_CARD"){
		 $html = $html.
			 '<input type="radio" id="ra_sel4" name="ra_select" value="104" onClick="javascript:payWithPaygate();">&nbsp;CREDIT CARD (Visa, Master, Jcb, Amex.)				
			<BR><BR>';
	  }

	  if($pg_paymethod_alipay == "ALIPAY"){
		  $html = $html.		
		   '<input type="radio" id="ra_sel5" name="ra_select" value="106" onClick="javascript:payWithPaygate();">&nbsp;ALIPAY				
			<BR><BR>';
	  }

	  if($pg_paymethod_j_btnotice == "J_BTNOTICE"){
		 $html = $html.
			 '<input type="radio" id="ra_sel6" name="ra_select" value="7" onClick=javascript:document.getElementById("div_j_btnotice").style.display="block";>&nbsp;JAPAN BANK WIRE				
			  <div id="div_j_btnotice" style="margin:10px;display:none">
				<table border="0" cellspacing="2">
					<tr>
						<th>
							<font color=red>＊</font> &nbsp;&nbsp;&nbsp;送金者名 : 
						</th>
						<td colspan="2">&nbsp;
							<input type="text" name="i_banksendername" id="i_banksendername" value="" />
						</td>
					</tr>
					<tr>
						<th>
							<font color=red>＊</font> 入金予定日: 
						</th>
						<td>&nbsp;
							<select name="i_bankexpyear" id="i_bankexpyear"> 
								<option value=""></option>
								<option value="2014">2014</option>
								<option value="2015">2015</option>
								<option value="2016">2016</option>
							</select>&nbsp;
							<select name="i_bankexpmonth" id="i_bankexpmonth">
								<option value=""></option>
								<option value="01">01</option>
								<option value="02">02</option>
								<option value="03">03</option>
								<option value="04">04</option>
								<option value="05">05</option>
								<option value="06">06</option>
								<option value="07">07</option>
								<option value="08">08</option>
								<option value="09">09</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
							</select>&nbsp;
							<select name="i_bankexpday" id="i_bankexpday">
								<option value=""></option>
								<option value="01">01</option>
								<option value="02">02</option>
								<option value="03">03</option>
								<option value="04">04</option>
								<option value="05">05</option>
								<option value="06">06</option>
								<option value="07">07</option>
								<option value="08">08</option>
								<option value="09">09</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
								<option value="13">13</option>
								<option value="14">14</option>
								<option value="15">15</option>
								<option value="16">16</option>
								<option value="17">17</option>
								<option value="18">18</option>
								<option value="19">19</option>
								<option value="20">20</option>
								<option value="21">21</option>
								<option value="22">22</option>
								<option value="23">23</option>
								<option value="24">24</option>
								<option value="25">25</option>
								<option value="26">26</option>
								<option value="27">27</option>
								<option value="28">28</option>
								<option value="29">29</option>
								<option value="30">30</option>
								<option value="31">31</option>
							</select>								
						</td>
						<td>&nbsp;&nbsp;&nbsp;
							<input type=button onClick="javascript:payWithPaygate();" value="Next &raquo;">
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right">(予定日以外にも入金可能）
						</td>
						<td>&nbsp;</td>
					</tr>
				</table>
			  </div>				  
			 <BR><BR>';			
	  }

	  if($pg_paymethod_inpay == "INPAY"){
		  $html = $html.
		  '<input type="radio" id="ra_sel7" name="ra_select" value="112" onClick="javascript:payWithPaygate();">&nbsp;INPAY
		  <BR><BR>';
	  }

	  //jykim 2014-04-03 : CUP mobile 지원안됨
      require_once(_PS_TOOL_DIR_.'mobile_Detect/Mobile_Detect.php');
      $mobile_detect = new Mobile_Detect();

      if($pg_paymethod_chinapay == "CHINAPAY" && !$mobile_detect->isMobile()){
	  	  $html = $html.
		  '<input type="radio" id="ra_sel8" name="ra_select" value="113" onClick="javascript:payWithPaygate();">&nbsp;CHINAPAY';
	  }

	  $html = $html.'
		</p>

		<div id="PGIOscreen"></div>
		<hr>

		<form name="PGIOForm" action="'.$statusurl.'" method="post">
		<input type="hidden" name="cardcode_name" id="cardcode_name" value="" />
		<input type="hidden" name="org_cardtype" id="org_cardtype" value="" />
		<input type="hidden" name="linkBuf" id="linkBuf" value="'.$linkBuf.'" />
		<input type="hidden" name="mid" id="mid" value="'.$pg_k_mid.'" />
		<input type="hidden" name="paymethod" id="paymethod" value="104" />
		<input type="hidden" name="unitprice" id="unitprice" value="'.$req_amt.'" />
		<input type="hidden" name="goodcurrency" id="goodcurrency" value="'.$req_currency.'" />
		<input type="hidden" name="ref" id="ref" value="'.$ref.'" />
		<input type="hidden" name="cur" id="cur" value="'.$currency->iso_code.'" />
		<input type="hidden" name="tid" id="tid" value="" />
		<input type="hidden" name="mb_serial_no" id="mb_serial_no" value="'.(int)($params['cart']->id).'" />
		<input type="hidden" name="receipttoname" id="receipttoname" value="'.$address->firstname.' '.$address->lastname.'" />
		<input type="hidden" name="receipttoemail" id="receipttoemail" value="'.$customer->email.'" />
		<input type="hidden" name="receipttotel" id="receipttotel" value="'.(!empty($address->phone_mobile) ? $address->phone_mobile : $address->phone).'" />
		<input type="hidden" name="replycode" id="replycode" value="" />
		<input type="hidden" name="replyMsg" id="replyMsg" value="" />
		<input type="hidden" name="amt" id="amt" value="'.(number_format($params['cart']->getOrderTotal(), 2, '.', '')).'" />						
		<input type="hidden" name="goodname" id="goodname" value="'.$goodname.'" />
		<input type="hidden" name="langcode" id="langcode" value="'.Configuration::get('PG_LANG').'" /> 
		<input type="hidden" name="fgkey" id="fgkey" value="'.$fgkey.'" />
		<input type="hidden" name="bankcode" id="bankcode" value="" />
		<input type="hidden" name="bankexpyear" id="bankexpyear" value="" />
		<input type="hidden" name="bankexpmonth" id="bankexpmonth" value="" />
		<input type="hidden" name="bankexpday" id="bankexpday" value="" />
		<input type="hidden" name="banksendername" id="banksendername" value="" />
		<input type="hidden" name="goodoption1" id="goodoption1" value="'.$goodoption1.'" />
		<input type="hidden" name="goodoption2" id="goodoption2" value="'.$goodoption2.'" />
		<input type="hidden" name="goodoption3" id="goodoption3" value="'.$goodoption3.'" />
		<input type="hidden" name="paymethod_name" id="paymethod_name" value="PG_CARD" />
		<input type="hidden" name="returnurl" id="returnurl" value="'.$returnurl.'" />
		</form>
	';
		return $html;
	}

	public function hookPaymentReturn($params)
	{
		//echo'<script>alert("call hookpaymentreturn!!!");</script>';
		//if (!$this->active)
		//	return ;
		return $this->display(__FILE__, 'controllers/paygate_confirm.tpl');
	}
}

