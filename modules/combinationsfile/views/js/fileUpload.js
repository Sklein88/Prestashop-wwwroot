    var combinationsFile = {
      fileUpload : function( url, id_product ){
        var id_attribute = URLToArray(url)['id_product_attribute'];
        $.ajax({
          type: 'POST',
          url: 'index.php',
          data: 'id_attribute='+id_attribute+'&id_product='+id_product+'&ajax=1&controller=AdminCombinationsFile&action=fileUpload&token=' + $('input[name=combinations_token]').val(),
          success: function(data)	{
            $("#fileUpload").html(data);
          },
          error: function() {alert('ERROR: unable to add the product');}
        });
      }
    }

  function URLToArray(url) {
    var request = {};
    var pairs = url.substring(url.indexOf('?') + 1).split('&');
    for (var i = 0; i < pairs.length; i++) {
      var pair = pairs[i].split('=');
      request[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
    }
    return request;
  }
