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
*  @license	http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
class Order extends OrderCore
{
  /*
    * module: combinationsfile
    * date: 2017-02-11 15:55:48
    * version: 1.4.4
    */
    public function getProducts($products = false, $selectedProducts = false, $selectedQty = false)
  {
    if (!$products)
      $products = $this->getProductsDetail();
    $customized_datas = Product::getAllCustomizedDatas($this->id_cart);
    $resultArray = array();
    foreach ($products as $row)
    {
      if ($selectedQty)
      {
        $row['product_quantity'] = 0;
        foreach ($selectedProducts as $key => $id_product)
          if ($row['id_order_detail'] == $id_product)
            $row['product_quantity'] = (int)($selectedQty[$key]);
        if (!$row['product_quantity'])
          continue;
      }
      $this->setProductImageInformations($row);
      $this->setProductCurrentStock($row);
      $this->setProductPrices($row);
      $this->setProductCustomizedDatas($row, $customized_datas);
      if ($row['download_hash'] && !empty($row['download_hash']))
      {
        $row['filename'] = ProductDownload::getFilenameFromAttribute((int)$row['product_id'], (int)$row['product_attribute_id']);
        $row['display_filename'] = ProductDownload::getFilenameFromFilename($row['filename']);
      }
      $row['id_address_delivery'] = $this->id_address_delivery;
      
      $resultArray[(int)$row['id_order_detail']] = $row;
    }
    if ($customized_datas)
      Product::addCustomizationPrice($resultArray, $customized_datas);
    return $resultArray;
  }
  /*
    * module: combinationsfile
    * date: 2017-02-11 15:55:48
    * version: 1.4.4
    */
    public function isVirtual($strict = true)
  {
    $products = $this->getProducts();
    if (count($products) < 1)
      return false;
    $virtual = true;
    foreach ($products as $product)
    {
      $pd = ProductDownload::getIdWithAttribute((int)($product['product_id']), (int)$product['product_attribute_id']);
      if ($pd && Validate::isUnsignedInt($pd) && $product['download_hash'] && $product['display_filename'] != '')
      {
        if ($strict === false)
          return true;
      }
      else
        $virtual &= false;
    }
    return $virtual;
  }
}