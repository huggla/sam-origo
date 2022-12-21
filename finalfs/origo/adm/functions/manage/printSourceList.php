<?php
	function printSourceList($layer, $sources)
	{
		$layerId=current($layer)['layer_id'];
		$layerSource=current($layer)['source'];
		echo <<<HERE
			<label for="{$layerId}Source">Källa:</label>
			<input type="text" list="sourcelist" class="bodySelect" id="{$layerId}Source" name="updateSource" value="{$layerSource}" onfocus="this.value='';" />
			<datalist id="sourcelist">
		HERE;
		printSelectOptions(array_merge(array(""), $sources), $layerSource);
		echo '</datalist>&nbsp;';
	}
?>
