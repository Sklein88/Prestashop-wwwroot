<?php /* Smarty version Smarty-3.1.19, created on 2017-02-13 03:17:19
         compiled from "/var/www/prestashop/dashboard/themes/default/template/content.tpl" */ ?>
<?php /*%%SmartyHeaderCode:102731472758a16b8fd45737-41862157%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2a0779f4c3c279d68f8b474ca7f935cf37822295' => 
    array (
      0 => '/var/www/prestashop/dashboard/themes/default/template/content.tpl',
      1 => 1460113476,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '102731472758a16b8fd45737-41862157',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58a16b8fd50012_68112249',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a16b8fd50012_68112249')) {function content_58a16b8fd50012_68112249($_smarty_tpl) {?>
<div id="ajax_confirmation" class="alert alert-success hide"></div>

<div id="ajaxBox" style="display:none"></div>


<div class="row">
	<div class="col-lg-12">
		<?php if (isset($_smarty_tpl->tpl_vars['content']->value)) {?>
			<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		<?php }?>
	</div>
</div><?php }} ?>
