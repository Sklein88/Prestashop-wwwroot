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

class AdminProductsController extends AdminProductsControllerCore
{

  public function __construct()
  {
    parent::__construct();
    $this->_use_found_rows = true;
    $this->_group = 'GROUP BY sa.id_product';
  }

  public function processDeleteVirtualProduct()
  {
    if (!($id_product_download = ProductDownload::getIdFromIdProduct((int)Tools::getValue('id_product'))))
      $this->errors[] = Tools::displayError('Cannot retrieve file');
    else
    {
      $product_download = new ProductDownload((int)$id_product_download);

      if (!$product_download->deleteFile((int)$id_product_download))
        $this->errors[] = Tools::displayError('Cannot delete file');
      else{
        if( Tools::getValue('virtual_id_attribute_combination') ){
          $this->redirect_after = self::$currentIndex.'&id_product='.(int)Tools::getValue('id_product').'&updateproduct&key_tab=Combinations&conf=1&token='.$this ->token;
        }
        else{
          $this->redirect_after = self::$currentIndex.'&id_product='.(int)Tools::getValue('id_product').'&updateproduct&key_tab=VirtualProduct&conf=1&token='.$this ->token;
        }
      }
     }

    $this->display = 'edit';
    $this->tab_display = 'VirtualProduct';
  }

  public function updateDownloadProduct($product, $edit = 0)
  {
    if (Tools::getValue('is_virtual_good_combination') == 'true')
    {
      if (Tools::getValue('virtual_product_expiration_date_combination') && !Validate::isDate(Tools::getValue('virtual_product_expiration_date_combination')))
      {
        if
        (!Tools::getValue('virtual_product_exp iration_date_combination'))
        {
          $this->errors[] = Tools::displayError('This field expiration date attribute is required.');
          return false;
        }
      }
      if (isset($_FILES['virtual_product_file_uploader_combination']) && $_FILES['virtual_product_file_uploader_combination']['size'] > 0)
      {
        $virtual_product_filename = ProductDownload::getNewFilename();
        $helper = new HelperUploader('virtual_product_file_uploader_combination');
        $files = $helper->setMaxFiles(Tools::getOctets(ini_get('upload_max_filesize')))->setSavePath(_PS_DOWNLOAD_DIR_) ->upload($_FILES['virtual_product_file_uploader_combination'],$virtual_product_filename);
      }
      else
        $virtual_product_filename = Tools::getValue('virtual_product_filename_combination', ProductDownload::getNewFilename());

        //Save thumbail
        $image_uploader = new HelperImageUploader('virtual_product_file_uploader_combination');
        $image_uploader->setAcceptTypes(array('jpeg', 'gif', 'png', 'jpg'))->setMaxSize($this->max_image_size);
        $files = $image_uploader->process();

        addImagesForeach($files, array('testtes'), $product);

      if ($edit == 1)
      {
        $id_product_download =(int)ProductDownload::getIdWithAttribute((int)$product->id, Tools::getValue('virtual_id_attribute_combination'));
      if (!$id_product_download)
        $id_product_download =(int)Tools::getValue('virtual_product_id_combination');
      }
      else
        $id_product_download = Tools::getValue('virtual_product_id_combination');
      $is_shareable = Tools::getValue('virtual_product_is_shareable_combination');
        $virtual_product_name = Tools::getValue('virtual_product_name_combination');
        $virtual_product_nb_days = Tools::getValue('virtual_product_nb_days_combination');
        $virtual_product_nb_downloable = Tools::getValue('virtual_product_nb_downloable_combination');
        $virtual_product_expiration_date = Tools::getValue('virtual_product_expiration_date_combination');
        $virtual_id_attribute =Tools::getValue('virtual_id_attribute_combination');
        if ($virtual_product_filename)
            $filename = $virtual_product_filename;
        $download = new ProductDownload((int)$id_product_download);
        $download->id_product = (int)$product ->id;
        $download->display_filename = $virtual_product_name;
        $download->filename = $filename;
        $download->date_add = date('Y-m-d H:i:s');
        $download->date_expiration = $virtual_product_expiration_date ?
            $virtual_product_expiration_date.' 23:59:59' : '';
        $download->nb_days_accessible = (int)$virtual_product_nb_days;
        $download->nb_downloadable = (int)$virtual_product_nb_downloable;
        $download->active = 1;
        $download->id_attribute = $virtual_id_attribute;
      $download->is_shareable = (int)$is_shareable;
      if ($download->save())
        return true;
    }

    if ((int)Tools::getValue('is_virtual_file') == 1)
    {
      if (isset($_FILES['virtual_product_file_uploader']) && $_FILES['virtual_product_file_uploader']['size'] > 0)
      {
        $virtual_product_filename = ProductDownload::getNewFilename();
        $helper = new HelperUploader('virtual_product_file_uploader');
        $files = $helper->setMaxFiles(Tools::getOctets(ini_get('upload_max_filesize')))
          ->setSavePath(_PS_DOWNLOAD_DIR_)->upload($_FILES['virtual_product_file_uploader'], $virtual_product_filename);
      }
      else
        $virtual_product_filename = Tools::getValue('virtual_product_filename', ProductDownload::getNewFilename());

      $product->setDefaultAttribute(0);//reset cache_default_attribute
      if (Tools::getValue('virtual_product_expiration_date') && !Validate::isDate(Tools::getValue('virtual_product_expiration_date')))
        if (!Tools::getValue('virtual_product_expiration_date'))
        {
          $this->errors[] = Tools::displayError('The expiration-date attribute is required.');
          return false;
        }

      // Trick's
      if ($edit == 1)
      {
        $id_product_download = (int)ProductDownload::getIdFromIdProduct((int)$product->id);
        if (!$id_product_download)
          $id_product_download = (int)Tools::getValue('virtual_product_id');
      }
      else
        $id_product_download = Tools::getValue('virtual_product_id');

      $is_shareable = Tools::getValue('virtual_product_is_shareable');
      $virtual_product_name = Tools::getValue('virtual_product_name');
      $virtual_product_nb_days = Tools::getValue('virtual_product_nb_days');
      $virtual_product_nb_downloable = Tools::getValue('virtual_product_nb_downloable');
      $virtual_product_expiration_date = Tools::getValue('virtual_product_expiration_date');

      $download = new ProductDownload((int)$id_product_download);
      $download->id_product = (int)$product->id;
      $download->display_filename = $virtual_product_name;
      $download->filename = $virtual_product_filename;
      $download->date_add = date('Y-m-d H:i:s');
      $download->date_expiration = $virtual_product_expiration_date ? $virtual_product_expiration_date.' 23:59:59' : '';
      $download->nb_days_accessible = (int)$virtual_product_nb_days;
      $download->nb_downloadable = (int)$virtual_product_nb_downloable;
      $download->active = 1;
      $download->is_shareable = (int)$is_shareable;

      if ($download->save())
        return true;
    }
    else
    {
      /* unactive download product if checkbox not checked */
      if ($edit == 1)
      {
        $id_product_download = (int)ProductDownload::getIdFromIdProduct((int)$product->id);
        if (!$id_product_download)
          $id_product_download = (int)Tools::getValue('virtual_product_id');
      }
      else
        $id_product_download = ProductDownload::getIdFromIdProduct($product->id);

      if (!empty($id_product_download))
      {
        $product_download = new ProductDownload((int)$id_product_download);
        $product_download->date_expiration = date('Y-m-d H:i:s', time() - 1);
        $product_download->active = 0;
        return $product_download->save();
      }
    }
    return false;
  }

  public function initFormAttributes($product)
  {
    $data = $this->createTemplate($this->tpl_form);
    if (!Combination::isFeatureActive())
      $this->displayWarning($this->l('This feature has been disabled. ').
        ' <a href="index.php?tab=AdminPerformance&token='.Tools::getAdminTokenLite('AdminPerformance').'#featuresDetachables">'.$this->l('Performances').'</a>');
    else if (Validate::isLoadedObject($product))
    {
      if ($this->product_exists_in_shop)
      {
        if (false)
        {
          $data->assign('product', $product);
          $this->displayWarning($this->l('A virtual product cannot have combinations.'));
        }
        else
        {
          $attribute_js = array();
          $attributes = Attribute::getAttributes($this->context->language->id, true);
          foreach ($attributes as $k => $attribute)
            $attribute_js[$attribute['id_attribute_group']][$attribute['id_attribute']] = $attribute['name'];
          $currency = $this->context->currency;
          $data->assign('attributeJs', $attribute_js);
          $data->assign('attributes_groups', AttributeGroup::getAttributesGroups($this->context->language->id));

          $data->assign('currency', $currency);

          $images = Image::getImages($this->context->language->id, $product->id);

          $data->assign('tax_exclude_option', Tax::excludeTaxeOption());
          $data->assign('ps_weight_unit', Configuration::get('PS_WEIGHT_UNIT'));

          $data->assign('ps_use_ecotax', Configuration::get('PS_USE_ECOTAX'));
          $data->assign('field_value_unity', $this->getFieldValue($product, 'unity'));

          $data->assign('reasons', $reasons = StockMvtReason::getStockMvtReasons($this->context->language->id));
          $data->assign('ps_stock_mvt_reason_default', $ps_stock_mvt_reason_default = Configuration::get('PS_STOCK_MVT_REASON_DEFAULT'));
          $data->assign('minimal_quantity', $this->getFieldValue($product, 'minimal_quantity') ? $this->getFieldValue($product, 'minimal_quantity') : 1);
          $data->assign('available_date', ($this->getFieldValue($product, 'available_date') != 0) ? Tools::stripslashes(htmlentities($this->getFieldValue($product, 'available_date'), $this->context->language->id)) : '0000-00-00');

          $i = 0;
          $type = ImageType::getByNameNType('%', 'products', 'height');
          if (isset($type['name']))
            $data->assign('imageType', $type['name']);
          else
            $data->assign('imageType', ImageType::getFormatedName('small'));
          $data->assign('imageWidth', (isset($image_type['width']) ? (int)($image_type['width']) : 64) + 25);
          foreach ($images as $k => $image)
          {
            $images[$k]['obj'] = new Image($image['id_image']);
            ++$i;
          }
          $data->assign('images', $images);

          $data->assign($this->tpl_form_vars);
          $data->assign(array(
            'list' => $this->renderListAttributes($product, $currency),
            'product' => $product,
            'id_category' => $product->getDefaultCategory(),
            'token_generator' => Tools::getAdminTokenLite('AdminAttributeGenerator'),
            'combinations_token' => Tools::getAdminTokenLite('AdminCombinationsFile'),
            'combination_exists' => (Shop::isFeatureActive() && (Shop::getContextShopGroup()->share_stock) && count(AttributeGroup::getAttributesGroups($this->context->language->id)) > 0 && $product->hasAttributes())
          ));
        }

      }
      else
        $this->displayWarning($this->l('You must save the product in this shop before adding combinations.'));
    }
    else
    {
      $data->assign('product', $product);
      $this->displayWarning($this->l('You must save this product before adding combinations.'));
    }

    $this->tpl_form_vars['custom_form'] = $data->fetch();
  }
}
