<?php /* Smarty version Smarty-3.1.19, created on 2017-03-21 17:51:22
         compiled from "C:\inetpub\wwwroot\modules\combinationsfile\views\templates\admin\combinations_file\fileUpload.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1896958a4e981b2a609-37355042%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0f0e57c17e07669089b47e7e309154c01cf1b224' => 
    array (
      0 => 'C:\\inetpub\\wwwroot\\modules\\combinationsfile\\views\\templates\\admin\\combinations_file\\fileUpload.tpl',
      1 => 1490130820,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1896958a4e981b2a609-37355042',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58a4e981d402d6_21265012',
  'variables' => 
  array (
    'product' => 0,
    'download_product_file_missing' => 0,
    'virtual_id_attribute' => 0,
    'download_dir_writable' => 0,
    'is_file' => 0,
    'virtual_product_combination_file_uploader' => 0,
    'currentIndex' => 0,
    'token' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a4e981d402d6_21265012')) {function content_58a4e981d402d6_21265012($_smarty_tpl) {?><div class="is_virtual_good">
  <input type="checkbox" id="is_virtual_good_combination" name="is_virtual_good_combination" value="true" <?php if ($_smarty_tpl->tpl_vars['product']->value->is_virtual&&$_smarty_tpl->tpl_vars['product']->value->productDownload->active) {?>checked="checked"<?php }?> />
  <label for="is_virtual_good_combination" class="t bold"><?php echo smartyTranslate(array('s'=>'Is this a virtual product?','mod'=>'combinationsfile'),$_smarty_tpl);?>
</label>
</div>
<div id="virtual_good_combination" <?php if (!$_smarty_tpl->tpl_vars['product']->value->productDownload->id||$_smarty_tpl->tpl_vars['product']->value->productDownload->active) {?>style="display:none"<?php }?> >
  <div class="form-group" style="display: none">
    <label class="control-label col-lg-3"><?php echo smartyTranslate(array('s'=>'Does this product have an associated file?','mod'=>'combinationsfile'),$_smarty_tpl);?>
</label>
    <div class="col-lg-2">
				<span class="switch prestashop-switch">
					<input type="radio" name="is_virtual_file_combination" id="is_virtual_file_combination_on" value="1" checked="checked" />
					<label for="is_virtual_file_combination_on"><?php echo smartyTranslate(array('s'=>'Yes','mod'=>'combinationsfile'),$_smarty_tpl);?>
</label>
					<input type="radio" name="is_virtual_file_combination" id="is_virtual_file_combination_off" value="0" />
					<label for="is_virtual_file_combination_off"><?php echo smartyTranslate(array('s'=>'No','mod'=>'combinationsfile'),$_smarty_tpl);?>
</label>
					<a class="slide-button btn"></a>
				</span>
    </div>
  </div>

  <div id="is_virtual_file_product_combination" style="display:none;">
    <?php if ($_smarty_tpl->tpl_vars['download_product_file_missing']->value) {?>
      <div class="form-group">
        <div class="col-lg-push-3 col-lg-9">
          <div class="alert alert-danger" id="file_missing">
            <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['download_product_file_missing']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
 :<br/>
            <strong><?php echo smartyTranslate(array('s'=>sprintf('Server file name : %s',$_smarty_tpl->tpl_vars['product']->value->productDownload->filename),'mod'=>'combinationsfile'),$_smarty_tpl);?>
</strong>
          </div>
        </div>
      </div>
    <?php }?>

    <input name="virtual_id_attribute_combination" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['virtual_id_attribute']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" type="hidden">
    <?php if (!$_smarty_tpl->tpl_vars['download_dir_writable']->value) {?>
      <div class="form-group">
        <div class="col-lg-push-3 col-lg-9">
          <div class="alert alert-danger">
            <?php echo smartyTranslate(array('s'=>'Your download repository is not writable.','mod'=>'combinationsfile'),$_smarty_tpl);?>

          </div>
        </div>
      </div>
    <?php }?>
    
    <?php if ($_smarty_tpl->tpl_vars['product']->value->productDownload->id) {?>
      <input type="hidden" id="virtual_product_id_combination" name="virtual_product_id_combination" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['product']->value->productDownload->id, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" />
    <?php }?>

    <div class="form-group"<?php if ($_smarty_tpl->tpl_vars['is_file']->value) {?> style="display:none"<?php }?>>
      <label id="virtual_product_file_label_combination" for="virtual_product_file_combination" class="control-label col-lg-3"><?php echo smartyTranslate(array('s'=>'File','mod'=>'combinationsfile'),$_smarty_tpl);?>
</label>
      <div class="col-lg-5">
        <?php echo $_smarty_tpl->tpl_vars['virtual_product_combination_file_uploader']->value;?>

      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-lg-3 required"><?php echo smartyTranslate(array('s'=>'Filename','mod'=>'combinationsfile'),$_smarty_tpl);?>
</label>
      <div class="col-lg-5">
        <input type="text" id="virtual_product_name_combination" name="virtual_product_name_combination" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value->productDownload->display_filename, ENT_QUOTES, 'UTF-8', true);?>
" />
        <p class="help-block"><?php echo smartyTranslate(array('s'=>'The full filename with its extension (e.g. Book.pdf)','mod'=>'combinationsfile'),$_smarty_tpl);?>
</p>
      </div>
    </div>

    <?php if ($_smarty_tpl->tpl_vars['is_file']->value) {?>
      <input type="hidden" id="virtual_product_filename_combination" name="virtual_product_filename_combination" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['product']->value->productDownload->filename, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" />
      <div class="form-group">
        <label class="control-label col-lg-3"><?php echo smartyTranslate(array('s'=>'Link to the file:','mod'=>'combinationsfile'),$_smarty_tpl);?>
</label>
        <div class="col-lg-5">
          <a href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['product']->value->productDownload->getTextLink(true), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" class="btn btn-default"><i class="icon-download"></i> <?php echo smartyTranslate(array('s'=>'Download file','mod'=>'combinationsfile'),$_smarty_tpl);?>
</a>
          <a href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['currentIndex']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
&amp;deleteVirtualProduct=true&amp;updateproduct&amp;token=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['token']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
&amp;id_product=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['product']->value->id, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
&virtual_id_attribute_combination=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['virtual_id_attribute']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" class="btn btn-default" onclick="return confirm('<?php echo smartyTranslate(array('s'=>'Do you really want to delete this file?','mod'=>'combinationsfile','js'=>1),$_smarty_tpl);?>
');"><i class="icon-trash"></i> <?php echo smartyTranslate(array('s'=>'Delete this file','mod'=>'combinationsfile'),$_smarty_tpl);?>
</a>
        </div>
      </div>
    <?php }?>

    <div class="form-group" style="display: none;">
      <label class="control-label col-lg-3"><?php echo smartyTranslate(array('s'=>'Number of allowed downloads','mod'=>'combinationsfile'),$_smarty_tpl);?>
</label>
      <div class="col-lg-3">
        <input type="text" id="virtual_product_nb_downloable_combination" name="virtual_product_nb_downloable_combination" value="<?php echo mb_convert_encoding(htmlspecialchars(htmlentities($_smarty_tpl->tpl_vars['product']->value->productDownload->nb_downloadable), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" class="" size="6" />
        <p class="help-block"><?php echo smartyTranslate(array('s'=>'Number of downloads allowed per customer. Set to 0 for unlimited downloads.','mod'=>'combinationsfile'),$_smarty_tpl);?>
</p>
      </div>
    </div>

    <div class="form-group" style="display: none;">
      <label class="control-label col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo smartyTranslate(array('s'=>'Format: YYYY-MM-DD.','mod'=>'combinationsfile'),$_smarty_tpl);?>
">
							<?php echo smartyTranslate(array('s'=>'Expiration date','mod'=>'combinationsfile'),$_smarty_tpl);?>

						</span>
      </label>
      <div class="col-lg-5">
        <input class="datepicker" type="text" id="virtual_product_expiration_date_combination" name="virtual_product_expiration_date_combination" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['product']->value->productDownload->date_expiration, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" size="11" maxlength="10" autocomplete="off" />
        <p class="help-block"><?php echo smartyTranslate(array('s'=>'If set, the file will not be downloadable after this date. Leave blank if you do not wish to attach an expiration date.','mod'=>'combinationsfile'),$_smarty_tpl);?>
</p>
      </div>
    </div>

    <div class="form-group" style="display: none;">
      <label class="control-label col-lg-3 required"><?php echo smartyTranslate(array('s'=>'Number of days','mod'=>'combinationsfile'),$_smarty_tpl);?>
</label>
      <div class="col-lg-3">
        <input type="text" id="virtual_product_nb_days_combination" name="virtual_product_nb_days_combination" value="<?php if (!$_smarty_tpl->tpl_vars['product']->value->productDownload->nb_days_accessible) {?>0<?php } else { ?><?php echo mb_convert_encoding(htmlspecialchars(htmlentities($_smarty_tpl->tpl_vars['product']->value->productDownload->nb_days_accessible), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php }?>" class="" size="4" />
        <p class="help-block"><?php echo smartyTranslate(array('s'=>'Number of days this file can be accessed by customers. Set to zero for unlimited access.','mod'=>'combinationsfile'),$_smarty_tpl);?>
)</p>
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
</script><?php }} ?>
