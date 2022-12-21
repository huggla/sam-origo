<?php
	function layerCategories($layers)
	{
		$layerCategories=array("Alla" => array_column($layers, 'layer_id'));
		foreach (array_filter(array_column($layers, 'categories', 'layer_id')) as $id => $pgarray)
		{
			foreach (pgArrayToPhp($pgarray) as $category)
			{
				if (!isset($layerCategories[$category]))
				{
					$layerCategories[$category]=array();
				}
				$layerCategories[$category][]=$id;
			}
		}
		return $layerCategories;
	}
?>
