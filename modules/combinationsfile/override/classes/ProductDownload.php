<?php

class ProductDownload extends ProductDownloadCore
{
  public $id_attribute = 0;

  public static $definition = array('table' => 'product_download','primary' => 'id_product_download','fields' => array('id_product' => 			array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true), 'display_filename' => 		array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 255), 'filename' => 				array('type' => self::TYPE_STRING, 'validate' => 'isSha1', 'size' => 255), 'date_add' => 				array('type' => self::TYPE_DATE, 'validate' => 'isDate'), 'date_expiration' => 		array('type' => self::TYPE_DATE, 'validate' => 'isDate'), 'nb_days_accessible' => 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'size' => 10),'nb_downloadable' => 		array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'size' => 10),'active' => 				array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),'is_shareable' => 			array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),'id_attribute' => 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'size' => 10),),);

  public static function getIdWithAttribute($id_product, $id_attribute)
  {
  
    if (!ProductDownload::isFeatureActive())
      return false;
    if (array_key_exists("$id_product-$id_attribute", self::$_productIds))
      return self::$_productIds["$id_product-$id_attribute"];
    self::$_productIds[$id_product.'-'.$id_attribute] = (int)Db::getInstance()->getValue('
		SELECT `id_product_download`
		FROM `'._DB_PREFIX_.'product_download`
		WHERE `id_product` = '.(int)$id_product.'
		AND `id_attribute` = '.(int)$id_attribute.'
		AND `active` = 1
		ORDER BY `id_product_download` DESC');

    return self::$_productIds[$id_product.'-'.$id_attribute];
  }


  public static function getFilenameFromAttribute($id_product, $id_attribute)
  {
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
			SELECT `filename`
			FROM `'._DB_PREFIX_.'product_download`
			WHERE `id_product` = '.(int)$id_product.'
			AND (`id_attribute` = '.(int)$id_attribute.'
			OR `id_attribute` = 0)
			AND `active` = 1
			ORDER BY `id_attribute` DESC
		');
  }

  public static function getIdFromIdProduct($id_product, $active=true)
  {

    $id_attribute = 0;
    if(Tools::getValue('virtual_id_attribute_combination')){
      $id_attribute = (int)Tools::getValue('virtual_id_attribute_combination');
    }

    return self::getIdWithAttribute($id_product, $id_attribute);

    if (!ProductDownload::isFeatureActive())
      return false;
    if (array_key_exists((int)$id_product, self::$_productIds))
      return self::$_productIds[$id_product];
    self::$_productIds[$id_product] = (int)Db::getInstance()->getValue('
		SELECT `id_product_download`
		FROM `'._DB_PREFIX_.'product_download`
		WHERE `id_product` = '.(int)$id_product.'
		AND `active` = 1
		ORDER BY `id_product_download` DESC');

    return self::$_productIds[$id_product];
  }
}

