<div class="is_virtual_good">
  <input type="checkbox" id="is_virtual_good_combination" name="is_virtual_good_combination" value="true" {if $product->is_virtual && $product->productDownload->active}checked="checked"{/if} />
  <label for="is_virtual_good_combination" class="t bold">{l s='Is this a virtual product?' mod='combinationsfile'}</label>
</div>
<div id="virtual_good_combination" {if !$product->productDownload->id || $product->productDownload->active}style="display:none"{/if} >
  <div class="form-group" style="display: none">
    <label class="control-label col-lg-3">{l s='Does this product have an associated file?' mod='combinationsfile'}</label>
    <div class="col-lg-2">
				<span class="switch prestashop-switch">
					<input type="radio" name="is_virtual_file_combination" id="is_virtual_file_combination_on" value="1" checked="checked" />
					<label for="is_virtual_file_combination_on">{l s='Yes' mod='combinationsfile'}</label>
					<input type="radio" name="is_virtual_file_combination" id="is_virtual_file_combination_off" value="0" />
					<label for="is_virtual_file_combination_off">{l s='No' mod='combinationsfile'}</label>
					<a class="slide-button btn"></a>
				</span>
    </div>
  </div>

  <div id="is_virtual_file_product_combination" style="display:none;">
    {if $download_product_file_missing}
      <div class="form-group">
        <div class="col-lg-push-3 col-lg-9">
          <div class="alert alert-danger" id="file_missing">
            {$download_product_file_missing|escape:'htmlall':'UTF-8'} :<br/>
            <strong>{l s='Server file name : %s'|sprintf:$product->productDownload->filename  mod='combinationsfile'}</strong>
          </div>
        </div>
      </div>
    {/if}

    <input name="virtual_id_attribute_combination" value="{$virtual_id_attribute|escape:'htmlall':'UTF-8'}" type="hidden">
    {if !$download_dir_writable}
      <div class="form-group">
        <div class="col-lg-push-3 col-lg-9">
          <div class="alert alert-danger">
            {l s='Your download repository is not writable.' mod='combinationsfile'}
          </div>
        </div>
      </div>
    {/if}
    {* Don't display file form if the product has combinations *}
    {if $product->productDownload->id}
      <input type="hidden" id="virtual_product_id_combination" name="virtual_product_id_combination" value="{$product->productDownload->id|escape:'htmlall':'UTF-8'}" />
    {/if}

    <div class="form-group"{if $is_file} style="display:none"{/if}>
      <label id="virtual_product_file_label_combination" for="virtual_product_file_combination" class="control-label col-lg-3">{l s='File' mod='combinationsfile'}</label>
      <div class="col-lg-5">
        {$virtual_product_combination_file_uploader}
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-lg-3 required">{l s='Filename' mod='combinationsfile'}</label>
      <div class="col-lg-5">
        <input type="text" id="virtual_product_name_combination" name="virtual_product_name_combination" value="{$product->productDownload->display_filename|escape:'html':'UTF-8'}" />
        <p class="help-block">{l s='The full filename with its extension (e.g. Book.pdf)' mod='combinationsfile'}</p>
      </div>
    </div>

    {if $is_file}
      <input type="hidden" id="virtual_product_filename_combination" name="virtual_product_filename_combination" value="{$product->productDownload->filename|escape:'htmlall':'UTF-8'}" />
      <div class="form-group">
        <label class="control-label col-lg-3">{l s='Link to the file:' mod='combinationsfile'}</label>
        <div class="col-lg-5">
          <a href="{$product->productDownload->getTextLink(true)|escape:'htmlall':'UTF-8'}" class="btn btn-default"><i class="icon-download"></i> {l s='Download file' mod='combinationsfile'}</a>
          <a href="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;deleteVirtualProduct=true&amp;updateproduct&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;id_product={$product->id|escape:'htmlall':'UTF-8'}&virtual_id_attribute_combination={$virtual_id_attribute|escape:'htmlall':'UTF-8'}" class="btn btn-default" onclick="return confirm('{l s='Do you really want to delete this file?'  mod='combinationsfile' js=1}');"><i class="icon-trash"></i> {l s='Delete this file' mod='combinationsfile'}</a>
        </div>
      </div>
    {/if}

    <div class="form-group" style="display: none;">
      <label class="control-label col-lg-3">{l s='Number of allowed downloads' mod='combinationsfile'}</label>
      <div class="col-lg-3">
        <input type="text" id="virtual_product_nb_downloable_combination" name="virtual_product_nb_downloable_combination" value="{$product->productDownload->nb_downloadable|htmlentities|escape:'htmlall':'UTF-8'}" class="" size="6" />
        <p class="help-block">{l s='Number of downloads allowed per customer. Set to 0 for unlimited downloads.' mod='combinationsfile'}</p>
      </div>
    </div>

    <div class="form-group" style="display: none;">
      <label class="control-label col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip" title="" data-original-title="{l s='Format: YYYY-MM-DD.' mod='combinationsfile'}">
							{l s='Expiration date' mod='combinationsfile'}
						</span>
      </label>
      <div class="col-lg-5">
        <input class="datepicker" type="text" id="virtual_product_expiration_date_combination" name="virtual_product_expiration_date_combination" value="{$product->productDownload->date_expiration|escape:'htmlall':'UTF-8'}" size="11" maxlength="10" autocomplete="off" />
        <p class="help-block">{l s='If set, the file will not be downloadable after this date. Leave blank if you do not wish to attach an expiration date.' mod='combinationsfile'}</p>
      </div>
    </div>

    <div class="form-group" style="display: none;">
      <label class="control-label col-lg-3 required">{l s='Number of days' mod='combinationsfile'}</label>
      <div class="col-lg-3">
        <input type="text" id="virtual_product_nb_days_combination" name="virtual_product_nb_days_combination" value="{if !$product->productDownload->nb_days_accessible}0{else}{$product->productDownload->nb_days_accessible|htmlentities|escape:'htmlall':'UTF-8'}{/if}" class="" size="4" />
        <p class="help-block">{l s='Number of days this file can be accessed by customers. Set to zero for unlimited access.' mod='combinationsfile'})</p>
      </div>
    </div>


  </div>
</div>


<script type="text/javascript">

  $(document).ready(function(){
    if ($('#is_virtual_good_combination').prop('checked'))
    {
      $('#virtual_good_combination').show();
    }

    $('.is_virtual_good').hide();

    $('input[name=is_virtual_file_combination]').live('change', function(e) {
      if($(this).val() == '1')
      {
        $('#virtual_good_more').show();
        $('#is_virtual_file_product_combination').show();
      }
      else
      {
        $('#virtual_good_more').hide();
        $('#is_virtual_file_product_combination').hide();
      }
    });

    if ( $('input[name=is_virtual_file_combination]:checked').val() == 1)
    {
      $('#virtual_good_more').show();
      $('#is_virtual_file_product_combination').show();
    }
    else
    {
      $('#virtual_good_more').hide();
      $('#is_virtual_file_product_combination').hide();
    }
  })
</script>