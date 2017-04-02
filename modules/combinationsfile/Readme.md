INSTALLATION INSTRUCTION 

For installation this module you need to copy folder “combinationsfile” to your modules folder. For example "www/modules/". After copying the module go to the admin panel of your shop, select tab modules->modules, click on "Front Office Features" category. Where you will find module "Virtual product combinations with associated file", press install.

If your PrestaShop version < 1.6.0.13 Open the file in “your store/js/adminproducts.js”

If your PrestaShop version >= 1.6.0.13 open the file in “your store/js/admin/products.js”
and looking for string:

editProductAttribute (url, parent){


And after this row paste:

 if(typeof combinationsFile !=="undefined"){
 combinationsFile.fileUpload(url, id_product);
 }

Also look for the lines:

else if (product_type == product_type_virtual)
{
if (has_combinations)

And replace it on follow:

else if (product_type == product_type_virtual)
{
if (false)

If your PrestaShop version 1.6.0.8, 1.6.0.9, 1.6.0.11 make also changes
In file "your store/js/admin-products.js"
If your PrestaShop version >= 1.6.0.13 make also changes
In file "your store/js/admin/products.js"
Look for the lines:

$('a[id*="Combinations"]').hide();
$('a[id*="Shipping"]').hide();

And replace it on follow:

//$('a[id*="Combinations"]').hide();
$('a[id*="Shipping"]').hide();