{*
* 2007-2014 PrestaShop
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
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if isset($product->id)}
  <div id="product-combinations" class="panel product-tab">
  <script type="text/javascript">
    var msg_combination_1 = '{l s='Please choose an attribute.' mod='combinationsfile'}';
    var msg_combination_2 = '{l s='Please choose a value.' mod='combinationsfile'}';
    var msg_combination_3 = '{l s='You can only add one combination per attribute type.' mod='combinationsfile'}';
    var msg_new_combination = '{l s='New combination' mod='combinationsfile'}';
    var msg_cancel_combination = '{l s='Cancel combination' mod='combinationsfile'}';
    var attrs = new Array();
    var modifyattributegroup = "{l s='Modify this attribute combination.' js=1 mod='combinationsfile'}";
    attrs[0] = new Array(0, "---");
    {foreach from=$attributeJs key=idgrp item=group}
    attrs[{$idgrp|escape:'htmlall':'UTF-8'}] = new Array(0
      , '---'
      {foreach from=$group key=idattr item=attrname}
      , "{$idattr|escape:'htmlall':'UTF-8'}", "{$attrname|addslashes|escape:'htmlall':'UTF-8'}"
      {/foreach}
    );
    {/foreach}
    $(document).ready(function(){
      populate_attrs();
      $(".datepicker").datepicker({
        prevText: '',
        nextText: '',
        dateFormat: 'yy-mm-dd'
      });
    });
  </script>
  <input type="hidden" name="submitted_tabs[]" value="Combinations" />
  <h3>{l s='Add or modify combinations for this product' mod='combinationsfile'}</h3>
  <div class="alert alert-info">
    {l s='You can also use the [1]Product Combinations Generator[2/][/1] in order to automatically create a set of combinations.' tags=["<a class='btn btn-link bt-icon confirm_leave' href='index.php?tab=AdminAttributeGenerator&amp;id_product={$product->id}&amp;attributegenerator&amp;token={$token_generator}'>", '<i class="icon-external-link-sign">'] mod='combinationsfile'}
  </div>
  {if $combination_exists}
    <div class="alert alert-info" style="display:block">
      {l s='Some combinations already exist. If you want to generate a set of new combinations, the quantities for the existing combinations will be lost.' mod='combinationsfile'}<br/>
      {l s='You can add a single combination by clicking the "New combination" button.' mod='combinationsfile'}
    </div>
  {/if}
  {if isset($display_multishop_checkboxes) && $display_multishop_checkboxes}
    <br />
    {include file="controllers/products/multishop/check_fields.tpl" product_tab="Combinations"}
  {/if}
  <div id="add_new_combination" class="panel" style="display: none;">
  <div class="panel-heading">{l s='Add or modify combinations for this product' mod='combinationsfile'}</div>
  <div class="form-group">
    <label class="control-label col-lg-3" for="attribute_group">{l s='Attribute' mod='combinationsfile'}</label>
    <div class="col-lg-5">
      <select name="attribute_group" id="attribute_group" onchange="populate_attrs();">
        {if isset($attributes_groups)}
          {foreach from=$attributes_groups key=k item=attribute_group}
            <option value="{$attribute_group.id_attribute_group|escape:'htmlall':'UTF-8'}">{$attribute_group.name|escape:'html':'UTF-8'}&nbsp;&nbsp;</option>
          {/foreach}
        {/if}
      </select>
    </div>
  </div>
  <div class="row">
    <label class="control-label col-lg-3" for="attribute">{l s='Value' mod='combinationsfile'}</label>
    <div class="col-lg-9">
      <div class="form-group">
        <div class="col-lg-8">
          <select name="attribute" id="attribute">
            <option value="0">-</option>
          </select>
        </div>
        <div class="col-lg-4">
          <button type="button" class="btn btn-default btn-block" onclick="add_attr();"><i class="icon-plus-sign-alt"></i> {l s='Add' mod='combinationsfile'}</button>
        </div>
      </div>
      <div class="form-group">
        <div class="col-lg-8">
          <select id="product_att_list" name="attribute_combination_list[]" multiple="multiple" ></select>
        </div>
        <div class="col-lg-4">
          <button type="button" class="btn btn-default btn-block" onclick="del_attr()"><i class="icon-minus-sign-alt"></i> {l s='Delete' mod='combinationsfile'}</button>
        </div>
      </div>
    </div>
  </div>
  <hr/>
  <div class="form-group">
    <label class="control-label col-lg-3" for="attribute_reference">
				<span class="label-tooltip" data-toggle="tooltip"
              title="{l s='Special characters allowed:' mod='combinationsfile'} .-_#">
					{l s='Reference code' mod='combinationsfile'}
				</span>
    </label>
    <div class="col-lg-5">
      <input type="text" id="attribute_reference" name="attribute_reference" value="" />
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-lg-3" for="attribute_ean13">
      {l s='EAN-13 or JAN barcode' mod='combinationsfile'}
    </label>
    <div class="col-lg-3">
      <input maxlength="13" type="text" id="attribute_ean13" name="attribute_ean13" value="" />
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-lg-3" for="attribute_upc">
      {l s='UPC barcode' mod='combinationsfile'}
    </label>
    <div class="col-lg-3">
      <input maxlength="12" type="text" id="attribute_upc" name="attribute_upc" value="" />
    </div>
  </div>
  <hr/>
  <div class="form-group">
    <label class="control-label col-lg-3" for="attribute_wholesale_price">
      {include file="controllers/products/multishop/checkbox.tpl" field="attribute_wholesale_price" type="default"}
      <span class="label-tooltip" data-toggle="tooltip"
            title="{l s='Set to zero if the price does not change.' mod='combinationsfile'}">
					{l s='Wholesale price' mod='combinationsfile'}
				</span>
    </label>
    <div class="col-lg-9">
      <div class="input-group col-lg-2">
					<span class="input-group-addon">
						{if $currency->format % 2 != 0}{$currency->sign|escape:'htmlall':'UTF-8'}{/if}
            {if $currency->format % 2 == 0}{$currency->sign|escape:'htmlall':'UTF-8'}{/if}
					</span>
        <input type="text" name="attribute_wholesale_price" id="attribute_wholesale_price" value="0" onKeyUp="if (isArrowKey(event)) return ;this.value = this.value.replace(/,/g, '.');" />
      </div>
    </div>
    <span style="display:none;" id="attribute_wholesale_price_full">({l s='Overrides the wholesale price from the "Prices" tab.' mod='combinationsfile'})</span>
  </div>
  <div class="form-group">
    <label class="control-label col-lg-3" for="attribute_price_impact">
      {include file="controllers/products/multishop/checkbox.tpl" field="attribute_price_impact" type="attribute_price_impact"}
      {l s='Impact on price' mod='combinationsfile'}11111
    </label>
    <div class="col-lg-9">
      <div class="row">
        <div class="col-lg-4">
          <select name="attribute_price_impact" id="attribute_price_impact" onchange="check_impact(); calcImpactPriceTI();">
            <option value="0">{l s='None' mod='combinationsfile'}</option>
            <option value="1">{l s='Increase' mod='combinationsfile'}</option>
            <option value="-1">{l s='Decrease' mod='combinationsfile'}</option>
          </select>
        </div>
        <div id="span_impact" class="col-lg-8">
          <div class="form-group">
            <label class="control-label col-lg-1" for="attribute_price">
              {l s='of' mod='combinationsfile'}
            </label>
            <div class="input-group col-lg-5">
              <div class="input-group-addon">
                {if $currency->format % 2 != 0}{$currency->sign|escape:'htmlall':'UTF-8'}{/if}
                {if $currency->format % 2 == 0} {$currency->sign|escape:'htmlall':'UTF-8'}{/if}
                {if $country_display_tax_label}
                  {l s='(tax excl.)' mod='combinationsfile'}
                {/if}
              </div>
              <input type="hidden"  id="attribute_priceTEReal" name="attribute_price" value="0.00" />

              <input type="text" id="attribute_price" value="0.00" onkeyup="$('#attribute_priceTEReal').val(this.value.replace(/,/g, '.')); if (isArrowKey(event)) return ;this.value = this.value.replace(/,/g, '.'); calcImpactPriceTI();"/>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-lg-1" for="attribute_priceTI">
              {l s='or' mod='combinationsfile'}
            </label>
            <div class="input-group col-lg-5">
              <div class="input-group-addon" {if $tax_exclude_option}style="display:none"{/if}>
                {if $currency->format % 2 != 0}{$currency->sign|escape:'htmlall':'UTF-8'}{/if}
                {if $currency->format % 2 == 0} {$currency->sign|escape:'htmlall':'UTF-8'}{/if}
                {l s='(tax incl.)' mod='combinationsfile'}
              </div>
              <input type="text" name="attribute_priceTI" id="attribute_priceTI" value="0.00" onkeyup="if (isArrowKey(event)) return ;this.value = this.value.replace(/,/g, '.'); calcImpactPriceTE();"/>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="alert">
                {l s='The final product price will be set to' mod='combinationsfile'}
                {if $currency->format % 2 != 0}{$currency->sign|escape:'htmlall':'UTF-8'}{/if}
                <span id="attribute_new_total_price">0.00</span>
                {if $currency->format % 2 == 0}{$currency->sign|escape:'htmlall':'UTF-8'}{/if}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-lg-3" for="attribute_weight_impact">
      {include file="controllers/products/multishop/checkbox.tpl" field="attribute_weight_impact" type="attribute_weight_impact"}
      {l s='Impact on weight' mod='combinationsfile'}
    </label>
    <div class="col-lg-9">
      <div class="row">
        <div class="col-lg-4">
          <select name="attribute_weight_impact" id="attribute_weight_impact" onchange="check_weight_impact();">
            <option value="0">{l s='None' mod='combinationsfile'}</option>
            <option value="1">{l s='Increase' mod='combinationsfile'}</option>
            <option value="-1">{l s='Reduction' mod='combinationsfile'}</option>
          </select>
        </div>
        <div id="span_weight_impact" class="col-lg-8">
          <div class="row">
            <label class="control-label col-lg-1" for="attribute_weight">
              {l s='of' mod='combinationsfile'}
            </label>
            <div class="input-group col-lg-5">
              <div class="input-group-addon">
                {$ps_weight_unit|escape:'htmlall':'UTF-8'}
              </div>
              <input type="text" name="attribute_weight" id="attribute_weight" value="0.00" onKeyUp="if (isArrowKey(event)) return ;this.value = this.value.replace(/,/g, '.');" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="tr_unit_impact" class="form-group">
    <label class="control-label col-lg-3" for="attribute_unit_impact">
      {include file="controllers/products/multishop/checkbox.tpl" field="attribute_unit_impact" type="attribute_unit_impact"}
      {l s='Impact on unit price' mod='combinationsfile'}
    </label>
    <div class="col-lg-3">
      <select name="attribute_unit_impact" id="attribute_unit_impact" onchange="check_unit_impact();">
        <option value="0">{l s='None' mod='combinationsfile'}</option>
        <option value="1">{l s='Increase' mod='combinationsfile'}</option>
        <option value="-1">{l s='Reduction' mod='combinationsfile'}</option>
      </select>
    </div>
    <div class="col-lg-6">
      <div class="row">
        <label class="control-label col-lg-1" for="attribute_unity">
          {l s='of' mod='combinationsfile'}
        </label>
        <div class="input-group col-lg-5">
          <div class="input-group-addon">
            {if $currency->format % 2 != 0}{$currency->sign|escape:'htmlall':'UTF-8'}{/if}
            {if $currency->format % 2 == 0}{$currency->sign|escape:'htmlall':'UTF-8'}{/if}
             <span id="unity_third">{$field_value_unity|escape:'htmlall':'UTF-8'}</span>
          </div>
          <input type="text" name="attribute_unity" id="attribute_unity" value="0.00" onKeyUp="if (isArrowKey(event)) return ;this.value = this.value.replace(/,/g, '.');" />
        </div>
      </div>
    </div>
  </div>
  {if $ps_use_ecotax}
    <div class="form-group">
      <label class="control-label col-lg-3" for="attribute_ecotax">
        {include file="controllers/products/multishop/checkbox.tpl" field="attribute_ecotax" type="default"}
        <span class="label-tooltip" data-toggle="tooltip"
              title="{l s='Overrides the ecotax from the "Prices" tab.' mod='combinationsfile'}">
					{l s='Ecotax (tax excl.)' mod='combinationsfile'}
				</span>
      </label>
      <div class="input-group col-lg-2">
        <div class="input-group-addon">
          {if $currency->format % 2 != 0}{$currency->sign|escape:'htmlall':'UTF-8'}{/if}
          {if $currency->format % 2 == 0} {$currency->sign|escape:'htmlall':'UTF-8'}{/if}
        </div>
        <input type="text" name="attribute_ecotax" id="attribute_ecotax" value="0.00" onKeyUp="if (isArrowKey(event)) return ;this.value = this.value.replace(/,/g, '.');" />
      </div>
    </div>
  {/if}
  <div class="form-group">
    <label class="control-label col-lg-3" for="attribute_minimal_quantity">
      {include file="controllers/products/multishop/checkbox.tpl" field="attribute_minimal_quantity" type="default"}
      <span class="label-tooltip" data-toggle="tooltip"
            title="{l s='The minimum quantity to buy this product (set to 1 to disable this feature).' mod='combinationsfile'}">
					{l s='Minimum quantity' mod='combinationsfile'}
				</span>
    </label>
    <div class="col-lg-9">
      <div class="input-group col-lg-2">
        <div class="input-group-addon">&times;</div>
        <input maxlength="6" name="attribute_minimal_quantity" id="attribute_minimal_quantity" type="text" value="{$minimal_quantity|escape:'htmlall':'UTF-8'}" />
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-lg-3" for="available_date_attribute">
      {include file="controllers/products/multishop/checkbox.tpl" field="available_date_attribute" type="default"}
      <span class="label-tooltip" data-toggle="tooltip"
            title="{l s='If this product is out of stock, you can indicate when the product will be available again.' mod='combinationsfile'}">
					{l s='Available date' mod='combinationsfile'}
				</span>
    </label>
    <div class="col-lg-9">
      <div class="input-group col-lg-3">
        <input class="datepicker" id="available_date_attribute" name="available_date_attribute" value="{$available_date|escape:'htmlall':'UTF-8'}" type="text" />
        <div class="input-group-addon">
          <i class="icon-calendar-empty"></i>
        </div>
      </div>
    </div>
  </div>
  <hr/>
  <div class="form-group">
    <label class="control-label col-lg-3">{l s='Image' mod='combinationsfile'}</label>
    <div class="col-lg-9">
      {if $images|count}
        <ul id="id_image_attr" class="list-inline">
          {foreach from=$images key=k item=image}
            <li>
              <input type="checkbox" name="id_image_attr[]" value="{$image.id_image|escape:'htmlall':'UTF-8'}" id="id_image_attr_{$image.id_image|escape:'htmlall':'UTF-8'}" />
              <label for="id_image_attr_{$image.id_image|escape:'intval'}">
                <img class="img-thumbnail" src="{$smarty.const._THEME_PROD_DIR_|escape:'htmlall':'UTF-8'}{$image.obj->getExistingImgPath()|escape:'htmlall':'UTF-8'}-{$imageType|escape:'htmlall':'UTF-8'}.jpg" alt="{$image.legend|escape:'html':'UTF-8'}" title="{$image.legend|escape:'html':'UTF-8'}" />
              </label>
            </li>
          {/foreach}
        </ul>
      {else}
        <div class="alert alert-warning">{l s='You must upload an image before you can select one for your combination.' mod='combinationsfile'}</div>
      {/if}
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-lg-3" for="attribute_default">
      {include file="controllers/products/multishop/checkbox.tpl" field="attribute_default" type="attribute_default"}
      {l s='Default' mod='combinationsfile'}
    </label>
    <div class="col-lg-9">
      <p class="checkbox">
        <label for="attribute_default">
          <input type="checkbox" name="attribute_default" id="attribute_default" value="1" />
          {l s='Make this combination the default combination for this product.' mod='combinationsfile'}
        </label>
      </p>
    </div>
  </div>
  <div class="panel-footer">
			<span id="ResetSpan">
				<button type="reset" name="ResetBtn" id="ResetBtn" onclick="$('#desc-product-newCombination').click();" class="btn btn-default">
          <i class="icon-undo"></i> {l s='Cancel modification' mod='combinationsfile'}
        </button>
			</span>
  </div>

  <div id="fileUpload"></div>

  </div>
  {$list}
  <div class="panel-footer">
    <a href="{$link->getAdminLink('AdminProducts')|escape:'htmlall':'UTF-8'}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel' mod='combinationsfile'}</a>
    <button type="submit" name="submitAddproduct" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save' mod='combinationsfile'}</button>
    <button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save and stay' mod='combinationsfile'}</button>
    <a href="#" id="desc-product-newCombination" class="btn btn-default pull-right"><i class="process-icon-new"></i> <span>{l s='New combination' mod='combinationsfile'}</span></a>
  </div>
  </div>
  <input name="combinations_token" type="hidden" value="{$combinations_token|escape:'htmlall':'UTF-8'}">
{/if}
