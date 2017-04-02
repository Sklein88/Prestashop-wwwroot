<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.08.15
 * Time: 17:40
 */
class AdminCombinationsFileController extends ModuleAdminController
{
  public function ajaxProcessFileUpload()
  {
    $product = new Product(Tools::getValue('id_product'), true);
    $product_download = new ProductDownload();
    if ($id_product_download = $product_download->getIdWithAttribute(Tools::getValue('id_product'), Tools::getValue('id_attribute')))
      $product_download = new ProductDownload($id_product_download);
    $product->{'productDownload'} = $product_download;

    if ($product->productDownload->id && empty($product->productDownload->display_filename)) {
      $this->errors[] = Tools::displayError('A file name is required in order to associate a file');
    }

    $exists_file = realpath(_PS_DOWNLOAD_DIR_) . '/' . $product->productDownload->filename;
    if (!file_exists($exists_file)
      && !empty($product->productDownload->display_filename)
      && empty($product->cache_default_attribute)
    )
      $msg = sprintf(Tools::displayError('This file "%s" is missing'),
        $product->productDownload->display_filename);
    else
      $msg = '';

    $product->productDownload->nb_downloadable = ($product->productDownload->id > 0) ? $product->productDownload->nb_downloadable : 0;
    $product->productDownload->date_expiration = ($product->productDownload->id > 0) ? ((!empty($product->productDownload->date_expiration) && $product->productDownload->date_expiration != '0000-00-00 00:00:00') ? date('Y-m-d', strtotime($product->productDownload->date_expiration)) : '') : htmlentities(Tools::getValue('virtual_product_expiration_date_combination'), ENT_COMPAT, 'UTF-8');
    $product->productDownload->nb_days_accessible = ($product->productDownload->id > 0) ? $product->productDownload->nb_days_accessible : 0;
    $product->productDownload->is_shareable = $product->productDownload->id > 0 && $product->productDownload->is_shareable;

    if (!defined('_PS_ADMIN_DIR_'))
      define('_PS_ADMIN_DIR_', getcwd());

    if (!defined('_PS_BO_ALL_THEMES_DIR_'))
      define('_PS_BO_ALL_THEMES_DIR_', _PS_ADMIN_DIR_ . '/themes/');

    $virtual_product_combination_file_uploader = new HelperUploader('virtual_product_file_uploader_combination');
    $virtual_product_combination_file_uploader->setTemplateDirectory( dirname(__FILE__) . '/../../views/templates/hook');
    $virtual_product_combination_file_uploader->setMultiple(false)->setUrl("index.php?controller=AdminProducts&token=" . Tools::getAdminTokenLite("AdminProducts") . '&ajax=1&id_product=' . (int)$product->id . '&action=AddVirtualProductFile')->setMaxFiles(Tools::getOctets(ini_get('upload_max_filesize')))->setTemplate('upload_virtual_product.tpl');

    $data = $this->createTemplate('fileUpload.tpl');
    $data->assign(
      array(
        'product' => $product,
        'download_dir_writable' => ProductDownload::checkWritableDir(),
        'is_file' => $product->productDownload->checkFile(),
        'product_downloaded' => $product->productDownload->id && !empty($product->productDownload->display_filename),
        'virtual_id_attribute' => Tools::getValue('id_attribute'),
        'currentIndex' => '?controller=AdminProducts',
        'token' => Tools::getAdminTokenLite("AdminProducts"),
        'download_product_file_missing' => $msg,
        'virtual_product_combination_file_uploader' => $virtual_product_combination_file_uploader->render()
      )
    );

    die($data->fetch());
  }
}