<?php

	function headForm($table, $inheritPosts)
	{
		echo '<form class="headForm" method="post">';
		$sId='';
		$target=rtrim(key($table), 's');
		$sName=$target.'Id';
		$selected=null;
		if (isset($inheritPosts[$target.'Id']))
		{
			$selected=$inheritPosts[$target.'Id'];
		}
		if ($target == 'layer')
		{
			echo "<select class=\"headSelect\" id=\"layerCategories\" name=\"layerCategory\" onchange='updateSelect(\"layerSelect\", window[this.value]);'></select><br>";
			$sId='id="layerSelect"';
		}
		else
		{
			if (isset($inheritPosts['layerCategory']))
			{
				echo '<input type="hidden" name="layerCategory" value="'.$inheritPosts['layerCategory'].'">';
			}
			if ($target == 'map' || $target == 'group')
			{
				//echo '<input type="hidden" name="groupLevels" value="1">';
				if ($target == 'group')
				{
					if (isset($inheritPosts['mapId']) || isset($inheritPosts['groupId']))
					{
						$sName='groupIds';
					}
					if (isset($inheritPosts['groupIds']))
					{
						$groupIdsArray=explode(',', $inheritPosts['groupIds']);
						if (!isset($inheritPosts['mapId']) && isset($groupIdsArray[0]))
						{
							$selected=$groupIdsArray[0];
						}
					}
				}
			}
		}
		echo   "<select $sId onchange=\"this.form.submit()\" class=\"headSelect\" name=\"$sName\">";
		//selectOptions2($target.'s', $configTables[$table], $selected);
		printSelectOptions(array_merge(array(""),array_column(current($table), pkColumnOfTable(key($table)))), $selected);
		echo   '</select>';
		echo   '<button type="submit" class="headButton" name="'.$target.'Button" value="get">';
		echo     'Hämta';
		echo   '</button>';
		echo '</form><br>';
		echo '<form class="headForm" method="post">';
		echo   '<input class="headInput" type="text" name="'.$target.'IdNew">';
		printHiddenInputs($inheritPosts);
		echo   '<button type="submit" class="headButton" name="'.$target.'Button" value="create">';
		echo     'Skapa';
		echo   '</button>';
		echo '</form>';
	}
?>
