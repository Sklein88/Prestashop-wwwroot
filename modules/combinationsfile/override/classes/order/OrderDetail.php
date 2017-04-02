<?php
/*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class OrderDetail extends OrderDetailCore
{
  public static function getDownloadFromHash($hash, $filename)
  {
    if ($hash == '') return false;
    $sql = 'SELECT *
		FROM `'._DB_PREFIX_.'order_detail` od
		LEFT JOIN `'._DB_PREFIX_.'product_download` pd ON (od.`product_id`=pd.`id_product`)
		WHERE od.`download_hash` = \''.pSQL(($hash)).'\'
		AND pd.`filename` = \''.pSQL(($filename)).'\'
		AND pd.`active` = 1';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);
  }
  protected function setVirtualProductInformation($product)
  {
    // Add some informations for virtual products
    $this->download_deadline = '0000-00-00 00:00:00';
    $this->download_hash = null;

    if ($id_product_download = ProductDownload::getIdWithAttribute((int )$product['id_product'], (int)$product['id_product_attribute']))
    {
      $productDownload = new ProductDownload((int)($id_product_download));
      $this->download_deadline = $productDownload->getDeadLine();
      $this->download_hash = $productDownload->getHash();

      unset($productDownload);
    }
  }
}