<!DOCTYPE html>
<html style="width:100%;height:100%">
<head>
	<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script>
		function mouseDown(e) {
		 var shiftPressed=0;
		 var evt = e?e:window.event;
		 if (parseInt(navigator.appVersion)>3) {
		  if (document.layers && navigator.appName=="Netscape")
		    shiftPressed=(evt.modifiers-0>3);
		  else shiftPressed=evt.shiftKey;
		  if (shiftPressed) {
		   /*alert ('Shift-click is disabled.')*/
		   return false;
		  }
		 }
		 return true;
		}

		if (parseInt(navigator.appVersion)>3) {
		 document.onmousedown = mouseDown;
		 if (document.layers && navigator.appName=="Netscape") 
		  document.captureEvents(Event.MOUSEDOWN);
		}

		function myFunction() {
		  /* Get the text field */
		  var copyText = document.getElementById("selection");

		  /* Select the text field */
		  copyText.select();
		  copyText.setSelectionRange(0, 99999); /* For mobile devices */

		  /* Copy the text inside the text field */
		  navigator.clipboard.writeText(copyText.value);
		}

		function update(menu) {
		    var last_action = '';
		    var qty = 0;

		// nothing selected
		    if ($(menu).val() == null) 
		    {
		        $.each($(menu).find('option'), function(i) 
		        {
 			    qty = 0;
	        	    last_action = "nothing selected";
            		    $(this).removeAttr('selected');
			    $(menu).attr('data-sorted-values', '');
		        });
		    } 
		// at least 1 item selected
		    else 
		    {
		        $.each($(menu).find('option'), function(i) 
		        {
		            var vals = $(menu).val().join(' ');
		            var opt = $(this).text();
		            qty = $(menu).val().length;
		            if (vals.indexOf(opt) > -1) 
		            {
                // most recent selection
		                if ($(this).attr('selected') != 'selected') 
		                {
		                    last_action = "added: " + opt;
		                    $(menu).attr('data-sorted-values', $(menu).attr('data-sorted-values') + $(this).text() + ' ');
		                    $(this).attr('selected', 'selected');
		                }
		            } 
		            else 
		            {
                // most recent deletion
		                if ($(this).attr('selected') == 'selected') 
		                {
		                    last_action = "removed: " + opt;
		                    var string = $(menu).attr('data-sorted-values').replace(new RegExp(opt + ',', 'g'), '');
		                    $(menu).attr('data-sorted-values', string);
		                    $(this).removeAttr('selected');
		                }
		            }
		        });
		    }
		    $(menu).attr('data-sorted-values', $(menu).attr('data-sorted-values').replace(' ', ','));
		    $('#selection').html($(menu).attr('data-sorted-values').slice(0, -1));
		}
	</script>
	<style>
		<?php include("./styles/multiselect.css"); ?>
	</style>
</head>
<body>
<?php
	header("Cache-Control: must-revalidate, max-age=0, s-maxage=0, no-cache, no-store");

	include_once("./constants/CONNECTION_STRING.php");
	include_once("./functions/dbh.php");
	include_once("./functions/all_from_table.php");

	$dbh=dbh(CONNECTION_STRING);
	$values=all_from_table('map_configs.'.$_GET['type']);

	echo '<select onChange="update(this);" data-sorted-values="" multiple style="float:left;height:100%;margin-right:5px">';
	if ($_GET['type'] == 'proj4defs')
	{
		$idColumn='code';
	}
	else
	{
		$idColumn=rtrim($_GET['type'], 's').'_id';
	}
	foreach (array_column($values, $idColumn) as $option)
	{
		$options="<option value='$option'";
		$options="$options>$option</option>";
		echo $options;
	}
	echo '</select>';
	if ($_GET['type'] == 'controls')
	{
		$header='Kontroller';
	}
	elseif ($_GET['type'] == 'groups')
	{
		$header='Grupper';
	}
	elseif ($_GET['type'] == 'layers')
	{
		$header='Lager';
	}
	else
	{
		$header=ucfirst($_GET['type']);
	}
	echo '<h3>'.$header.'</h3>';
	echo '<textarea readonly id="selection" style="display:flex;overflow:auto;width:-webkit-fill-available"></textarea>';
	echo '<button onClick="window.location.reload();">Töm</button>&nbsp;';
	echo '<button onclick="myFunction();">Kopiera text</button>';
?>
</body>
</html>
