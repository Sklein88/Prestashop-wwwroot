<?php

/**
 * Created by PhpStorm.
 * User: maskc_000
 * Date: 29.10.13
 * Time: 18:21
 */
class combinationsfile extends Module
{

  public function __construct()
  {
    $this->name = 'combinationsfile';
    $this->tab = 'front_office_features';
    $this->version = '1.4.4';
    $this->author = 'MyPrestaModules';
    $this->need_instance = 0;
    $this->module_key = "bf2df145f6ed3d190876525d49d42a42";
    $this->_shopId = Context::getContext()->shop->id;
    $this->_languageId = Context::getContext()->language->id;
    parent::__construct(); // The parent construct is required for translations
    $this->displayName = $this->l('Virtual product combinations with associated file');
    $this->description = $this->l('Generate new combinations for your PrestaShop virtual products. Easily upload associated files and let your customers obtain a full range of information on the products they are interested in.');
  }

  public function install()
  {
    if (!parent::install() || !$this->registerHook('backOfficeHeader')) {
      return false;
    }
    $sql = "
        ALTER TABLE `" . _DB_PREFIX_ . "product_download`
        ADD COLUMN `id_attribute` INT(11) NOT NULL
        AFTER `is_shareable`;
        ";
    Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql);

    $this->_checkIndex();
    $this->_createTab();

    $this->_overriteFile('controllers/admin/templates/products', 'combinations.tpl', 0);
    $this->_overriteFile('controllers/admin/templates/products', 'virtualproduct.tpl', 0);
    if (version_compare(_PS_VERSION_, '1.6.0.8') >= 0 && version_compare(_PS_VERSION_, '1.6.0.11') < 0) {
      $this->_overriteFile('controllers/admin/templates/products/helpers/form', 'form.tpl', 0);
    } else if (version_compare(_PS_VERSION_, '1.6.0.11') >= 0 && version_compare(_PS_VERSION_, '1.6.1.2') < 0) {
      $this->_overriteFile('controllers/admin/templates/products/helpers/form', 'form.tpl', 1);
    }
    else if (version_compare(_PS_VERSION_, '1.6.1.2') >= 0) {
      $this->_overriteFile('controllers/admin/templates/products/helpers/form', 'form.tpl', 2);
    }
    if (file_exists(_PS_ROOT_DIR_ . "/cache/class_index.php")) {
      unlink(_PS_ROOT_DIR_ . "/cache/class_index.php");
    }
    return true;
  }

  private function _createTab()
  {
    $tab = new Tab();
    $tab->active = 1;
    $tab->class_name = 'AdminCombinationsFile';
    $tab->name = array();
    foreach (Language::getLanguages(true) as $lang)
      $tab->name[$lang['id_lang']] = 'CombinationsFile';
    $tab->id_parent = -1;
    $tab->module = $this->name;
    $tab->add();
  }

  private function _removeTab()
  {
    $id_tab = (int)Tab::getIdFromClassName('AdminCombinationsFile');
    if ($id_tab)
    {
      $tab = new Tab($id_tab);
      $tab->delete();
    }
  }

  private function _checkIndex()
  {
    // Check index
    $sql = 'SHOW INDEX FROM '._DB_PREFIX_.'product_download WHERE KEY_NAME = "id_product"';
    $res = Db::getInstance()->executes($sql);

    if( $res ){
      $sql = 'ALTER TABLE '._DB_PREFIX_.'product_download DROP INDEX `id_product`';
      Db::getInstance()->execute($sql);
    }
  }

  public function hookBackOfficeHeader()
  {
    if (Tools::getValue('id_product')) {
      return "<script type='text/javascript' src='" . __PS_BASE_URI__ . "modules/combinationsfile/views/js/fileUpload.js'></script>";
    }
  }

  public function uninstall()
  {
    $sql = "
      ALTER TABLE `" . _DB_PREFIX_ . "product_download`
      DROP COLUMN `id_attribute`;
      ";
    Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql);

    $this->_removeTab();

    $this->_overrideUnlink('controllers/admin/templates/products/combinations.tpl');
    $this->_overrideUnlink('controllers/admin/templates/products/virtualproduct.tpl');

    if (file_exists(_PS_ROOT_DIR_ . "/cache/class_index.php")) {
      unlink(_PS_ROOT_DIR_ . "/cache/class_index.php");
    }
    if (version_compare(_PS_VERSION_, '1.6.0.8') >= 0) {
      if (file_exists(_PS_ROOT_DIR_ . "/override/controllers/admin/templates/products/helpers/form/form.tpl")) {
        unlink(_PS_ROOT_DIR_ . "/override/controllers/admin/templates/products/helpers/form/form.tpl");
      }
    }

    if (parent::uninstall()) {
      return true;
    }
    return false;
  }


  public function fileUpload($id_attribute, $token)
  {
    $product = new Product(Tools::getValue('id_product'), true, $this->_languageId, $this->_shopId);
    $product_download = new ProductDownload();
    if ($id_product_download = $product_download->getIdWithAttribute(Tools::getValue('id_product'), $id_attribute))
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

    if (defined('_PS_ADMIN_DIR_'))
      define('_PS_BO_ALL_THEMES_DIR_', _PS_ADMIN_DIR_ . '/themes/');

    $virtual_product_combination_file_uploader = new HelperUploaderCore('virtual_product_file_uploader_combination');
    $virtual_product_combination_file_uploader->setTemplateDirectory('views/templates/hook');
    $virtual_product_combination_file_uploader->setMultiple(false)->setUrl("index.php?controller=AdminProducts&token=" . $token . '&ajax=1&id_product=' . (int)$product->id . '&action=AddVirtualProductFile')->setMaxFiles(Tools::getOctets(ini_get('upload_max_filesize')))->setTemplate('upload_virtual_product.tpl');

    $this->context->smarty->assign(
      array(
        'product' => $product,
        'download_dir_writable' => ProductDownload::checkWritableDir(),
        'is_file' => $product->productDownload->checkFile(),
        'product_downloaded' => $product->productDownload->id && !empty($product->productDownload->display_filename),
        'virtual_id_attribute' => $id_attribute,
        'currentIndex' => '?controller=AdminProducts',
        'token' => $token,
        'download_product_file_missing' => $msg,
        'virtual_product_combination_file_uploader' => $virtual_product_combination_file_uploader->render()
      )
    );

    return $this->display(__FILE__, 'views/templates/front/fileUpload.tpl');
  }

  private function _overriteFile($path_overrite, $file, $n)
  {
    $filepath = _PS_ROOT_DIR_ . "/override/" . $path_overrite . "/" . $file;

    if (file_exists($filepath)) {
      return false;
    } else {
      $path = explode("/", $path_overrite);
      $dir = _PS_ROOT_DIR_ . "/override";

      foreach ($path as $url) {
        $dir .= '/' . $url;
        if (!file_exists($dir) && !is_dir($dir)) {
          @mkdir($dir, 0775);
        }
      }
    }
    if (!$n) {
      $fileData = Tools::file_get_contents(dirname(__FILE__) . '/views/templates/admin/' . $file);
    } elseif($n==1) {
      $fileData = Tools::file_get_contents(dirname(__FILE__) . '/views/templates/admin/form16011.tpl');
    }
    elseif($n==2) {
      $fileData = Tools::file_get_contents(dirname(__FILE__) . '/views/templates/admin/form1612.tpl');
    }

    file_put_contents($filepath, $fileData);
  }

  private function _overrideUnlink($path)
  {
    $filepath = _PS_ROOT_DIR_ . "/override/" . $path;

    if (file_exists($filepath)) {
      unlink($filepath);
    }
  }
}