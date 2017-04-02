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
class OrderHistory extends OrderHistoryCore
{
  /*
    * module: combinationsfile
    * date: 2017-02-11 15:55:48
    * version: 1.4.4
    */
    public function changeIdOrderState($new_order_state, $id_order, $use_existing_payment = false)
  {
    if (!$new_order_state || !$id_order)
      return;
    if (!is_object($id_order) && is_numeric($id_order))
      $order = new Order((int)$id_order);
    elseif (is_object($id_order))
      $order = $id_order;
    else
      return;
    ShopUrl::cacheMainDomainForShop($order->id_shop);
    $new_os = new OrderState((int)$new_order_state, $order->id_lang);
    $old_os = $order->getCurrentOrderState();
    $is_validated = $this->isValidated();
    if (in_array($new_os->id, array(Configuration::get('PS_OS_PAYMENT'), Configuration::get('PS_OS_WS_PAYMENT'))))
      Hook::exec('actionPaymentConfirmation', array('id_order' => (int)$order->id), null, false, true, false, $order->id_shop);
    Hook::exec('actionOrderStatusUpdate', array('newOrderStatus' => $new_os, 'id_order' => (int)$order->id), null, false, true, false, $order->id_shop);
    if (Validate::isLoadedObject($order) && ($new_os instanceof OrderState))
    {
      $virtual_products = $order->getVirtualProducts();
      if ($virtual_products && (!$old_os || !$old_os->logable) && $new_os && $new_os->logable)
      {
        $context = Context::getContext();
        $assign = array();
        foreach ($virtual_products as $key => $virtual_product)
        {
          $id_product_download = ProductDownload::getIdWithAttribute($virtual_product['product_id'],$virtual_product['product_attribute_id'] );
          if( !$id_product_download ){
            $id_product_download = ProductDownload::getIdFromIdProduct($virtual_product['product_id']);
          }
          $product_download = new ProductDownload($id_product_download);
          if ($product_download->display_filename != '')
          {
            $assign[$key]['name'] = $product_download->display_filename;
            $dl_link = $product_download->getTextLink(false, $virtual_product['download_hash'])
              .'&id_order='.(int)$order->id
              .'&secure_key='.$order->secure_key;
            $assign[$key]['link'] = $dl_link;
            if (isset($virtual_product['download_deadline']) && $virtual_product['download_deadline'] != '0000-00-00 00:00:00')
              $assign[$key]['deadline'] = Tools::displayDate($virtual_product['download_deadline']);
            if ($product_download->nb_downloadable != 0)
              $assign[$key]['downloadable'] = (int)$product_download->nb_downloadable;
          }
        }
        $customer = new Customer((int)$order->id_customer);
        $links = '<ul>';
        foreach($assign as $product)
        {
          $links .= '<li>';
          $links .= '<a href="'.$product['link'].'">'.Tools::htmlentitiesUTF8($product['name']).'</a>';
          if (isset($product['deadline']))
            $links .= '&nbsp;'.Tools::htmlentitiesUTF8(Tools::displayError('expires on', false)).'&nbsp;'.$product['deadline'];
          if (isset($product['downloadable']))
            $links .= '&nbsp;'.Tools::htmlentitiesUTF8(sprintf(Tools::displayError('downloadable %d time(s)', false), (int)$product['downloadable']));
          $links .= '</li>';
        }
        $links .= '</ul>';
        $data = array(
          '{lastname}' => $customer->lastname,
          '{firstname}' => $customer->firstname,
          '{id_order}' => (int)$order->id,
          '{order_name}' => $order->getUniqReference(),
          '{nbProducts}' => count($virtual_products),
          '{virtualProducts}' => $links
        );
        if (!empty($assign))
          Mail::Send((int)$order->id_lang, 'download_product', Mail::l('Virtual product to download', $order->id_lang), $data, $customer->email, $customer->firstname.' '.$customer->lastname,
            null, null, null, null, _PS_MAIL_DIR_, false, (int)$order->id_shop);
      }
      $manager = null;
      if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT'))
        $manager = StockManagerFactory::getManager();
      $errorOrCanceledStatuses = array(Configuration::get('PS_OS_ERROR'), Configuration::get('PS_OS_CANCELED'));
      if (Validate::isLoadedObject($old_os))
        foreach ($order->getProductsDetail() as $product)
        {
          if ($new_os->logable && !$old_os->logable)
          {
            ProductSale::addProductSale($product['product_id'], $product['product_quantity']);
            if (!Pack::isPack($product['product_id']) &&
              in_array($old_os->id, $errorOrCanceledStatuses) &&
              !StockAvailable::dependsOnStock($product['id_product'], (int)$order->id_shop))
              StockAvailable::updateQuantity($product['product_id'], $product['product_attribute_id'], -(int)$product['product_quantity'], $order->id_shop);
          }
          elseif (!$new_os->logable && $old_os->logable)
          {
            ProductSale::removeProductSale($product['product_id'], $product['product_quantity']);
            if (!Pack::isPack($product['product_id']) &&
              in_array($new_os->id, $errorOrCanceledStatuses) &&
              !StockAvailable::dependsOnStock($product['id_product']))
              StockAvailable::updateQuantity($product['product_id'], $product['product_attribute_id'], (int)$product['product_quantity'], $order->id_shop);
          }
          elseif (!$new_os->logable && !$old_os->logable &&
            in_array($new_os->id, $errorOrCanceledStatuses) &&
            !in_array($old_os->id, $errorOrCanceledStatuses) &&
            !StockAvailable::dependsOnStock($product['id_product']))
            StockAvailable::updateQuantity($product['product_id'], $product['product_attribute_id'], (int)$product['product_quantity'], $order->id_shop);
          if ($new_os->shipped == 1 && $old_os->shipped == 0 &&
            Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') &&
            Warehouse::exists($product['id_warehouse']) &&
            $manager != null &&
            ((int)$product['advanced_stock_management'] == 1 || Pack::usesAdvancedStockManagement($product['product_id'])))
          {
            $warehouse = new Warehouse($product['id_warehouse']);
            $manager->removeProduct(
              $product['product_id'],
              $product['product_attribute_id'],
              $warehouse,
              $product['product_quantity'],
              Configuration::get('PS_STOCK_CUSTOMER_ORDER_REASON'),
              true,
              (int)$order->id
            );
          }
          elseif ($new_os->shipped == 0 && $old_os->shipped == 1 &&
            Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') &&
            Warehouse::exists($product['id_warehouse']) &&
            $manager != null &&
            ((int)$product['advanced_stock_management'] == 1 || Pack::usesAdvancedStockManagement($product['product_id'])))
          {
            if (Pack::isPack($product['product_id']))
            {
              $pack_products = Pack::getItems($product['product_id'], Configuration::get('PS_LANG_DEFAULT', null, null, $order->id_shop));
              foreach ($pack_products as $pack_product)
              {
                if ($pack_product->advanced_stock_management == 1)
                {
                  $mvts = StockMvt::getNegativeStockMvts($order->id, $pack_product->id, 0, $pack_product->pack_quantity * $product['product_quantity']);
                  foreach ($mvts as $mvt)
                  {
                    $manager->addProduct(
                      $pack_product->id,
                      0,
                      new Warehouse($mvt['id_warehouse']),
                      $mvt['physical_quantity'],
                      null,
                      $mvt['price_te'],
                      true
                    );
                  }
                  if (!StockAvailable::dependsOnStock($product['id_product']))
                    StockAvailable::updateQuantity($pack_product->id, 0, (int)$pack_product->pack_quantity * $product['product_quantity'], $order->id_shop);
                }
              }
            }
            else
            {
              $mvts = StockMvt::getNegativeStockMvts($order->id, $product['product_id'], $product['product_attribute_id'], $product['product_quantity']);
              foreach ($mvts as $mvt)
              {
                $manager->addProduct(
                  $product['product_id'],
                  $product['product_attribute_id'],
                  new Warehouse($mvt['id_warehouse']),
                  $mvt['physical_quantity'],
                  null,
                  $mvt['price_te'],
                  true
                );
              }
            }
          }
        }
    }
    $this->id_order_state = (int)$new_order_state;
    if (!Validate::isLoadedObject($new_os) || !Validate::isLoadedObject($order))
      die(Tools::displayError('Invalid new order state'));
    $order->current_state = $this->id_order_state;
    $order->valid = $new_os->logable;
    $order->update();
    if ($new_os->invoice && !$order->invoice_number)
      $order->setInvoice($use_existing_payment);
    if ($new_os->paid == 1)
    {
      $invoices = $order->getInvoicesCollection();
      if ($order->total_paid != 0)
        $payment_method = Module::getInstanceByName($order->module);
      foreach ($invoices as $invoice)
      {
        $rest_paid = $invoice->getRestPaid();
        if ($rest_paid > 0)
        {
          $payment = new OrderPayment();
          $payment->order_reference = $order->reference;
          $payment->id_currency = $order->id_currency;
          $payment->amount = $rest_paid;
          if ($order->total_paid != 0)
            $payment->payment_method = $payment_method->displayName;
          else
            $payment->payment_method = null;
          if ($payment->id_currency == $order->id_currency)
            $order->total_paid_real += $payment->amount;
          else
            $order->total_paid_real += Tools::ps_round(Tools::convertPrice($payment->amount, $payment->id_currency, false), 2);
          $order->save();
          $payment->conversion_rate = 1;
          $payment->save();
          Db::getInstance()->execute('
					INSERT INTO `'._DB_PREFIX_.'order_invoice_payment`
					VALUES('.(int)$invoice->id.', '.(int)$payment->id.', '.(int)$order->id.')');
        }
      }
    }
    if ($new_os->delivery)
      $order->setDelivery();
    Hook::exec('actionOrderStatusPostUpdate', array('newOrderStatus' => $new_os,'id_order' => (int)$order->id,), null, false, true, false, $order->id_shop);
    ShopUrl::resetMainDomainCache();
  }
}