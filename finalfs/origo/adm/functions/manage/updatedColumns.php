<?php
	function updatedColumns($table, $updatePosts)
	{
		$commonColumns=array(
			pkColumnOfTable($table)	=> $updatePosts['update'.ucfirst(pkColumnOfTable($table))],
			'info'						=> $updatePosts['updateInfo']
		);
		if ($table == 'maps')
		{
			$tableColumns=array(
				'featureinfooptions'	=> $updatePosts['updateFeatureinfooptions'],
				'center'				=> $updatePosts['updateCenter'],
				'zoom'					=> $updatePosts['updateZoom'],
				'footer'				=> $updatePosts['updateFooter'],
				'tilegrid'				=> $updatePosts['updateTilegrid'],
				'layers'				=> '{'.$updatePosts['updateLayers'].'}',
				'groups'				=> '{'.$updatePosts['updateGroups'].'}',
				'controls'				=> '{'.$updatePosts['updateControls'].'}',
				'proj4defs'				=> '{'.$updatePosts['updateProj4defs'].'}'
			);
		}
		elseif ($table == 'groups')
		{
			$tableColumns=array(
				'title'					=> $updatePosts['updateTitle'],
				'abstract'				=> $updatePosts['updateAbstract'],
				'expanded'				=> $updatePosts['updateExpanded'],
				'layers'				=> '{'.$updatePosts['updateLayers'].'}',
				'groups'				=> '{'.$updatePosts['updateGroups'].'}'
			);
		}
		elseif ($table == 'controls')
		{
			$tableColumns=array(
				'options'				=> $updatePosts['updateOptions']
			);
		}
		elseif ($table == 'footers')
		{
			$tableColumns=array(
				'img'					=> $updatePosts['updateImg'],
				'url'					=> $updatePosts['updateUrl'],
				'text'					=> $updatePosts['updateText']
			);
		}
		elseif ($table == 'tilegrids')
		{
			$tableColumns=array(
				'tilesize'				=> $updatePosts['updateTilesize']
			);
		}
		elseif ($table == 'proj4defs')
		{
			$tableColumns=array(
				'projection'			=> $updatePosts['updateProjection'],
				'alias'					=> $updatePosts['updateAlias']
			);
		}
		elseif ($table == 'services')
		{
			$tableColumns=array(
				'base_url'				=> $updatePosts['updateBase_url']
			);
		}
		elseif ($table == 'sources')
		{
			$tableColumns=array(
				'service'				=> $updatePosts['updateService'],
				'with_geometry'			=> $updatePosts['updateWith_geometry'],
				'fi_point_tolerance'	=> $updatePosts['updateFi_point_tolerance'],
				'ttl'					=> $updatePosts['updateTtl'],
				'tilegrid'				=> $updatePosts['updateTilegrid']
			);
		}
		elseif ($table == 'layers')
		{
			$tableColumns=array(
				'attributes'			=> $updatePosts['updateAttributes'],
				'editable'				=> $updatePosts['updateEditable'],
				'allowededitoperations'	=> $updatePosts['updateAllowededitoperations'],
				'tiled'					=> $updatePosts['updateTiled'],
				'style_config'			=> $updatePosts['updateStyle_config'],
				'maxscale'				=> $updatePosts['updateMaxscale'],
				'minscale'				=> $updatePosts['updateMinscale'],
				'clusterstyle'			=> $updatePosts['updateClusterstyle'],
				'clusteroptions'		=> $updatePosts['updateClusteroptions'],
				'title'					=> $updatePosts['updateTitle'],
				'abstract'				=> $updatePosts['updateAbstract'],
				'source'				=> $updatePosts['updateSource'],
				'type'					=> $updatePosts['updateType'],
				'queryable'				=> $updatePosts['updateQueryable'],
				'visible'				=> $updatePosts['updateVisible'],
				'icon'					=> $updatePosts['updateIcon'],
				'icon_extended'			=> $updatePosts['updateIcon_extended'],
				'style_filter'			=> $updatePosts['updateStylefilter'],
				'opacity'				=> $updatePosts['updateOpacity'],
				'featureinfolayer'		=> $updatePosts['updateFeatureinfolayer'],
				'format'				=> $updatePosts['updateFormat'],
				'attribution'			=> $updatePosts['updateAttribution'],
				'layertype'				=> $updatePosts['updateLayertype'],
				'swiper'				=> $updatePosts['updateSwiper'],
				'categories'			=> '{'.$updatePosts['updateCategories'].'}',
				'layers'				=> '{'.$updatePosts['updateLayers'].'}',
				'adusers'				=> '{'.$updatePosts['updateAdusers'].'}',
				'adgroups'				=> '{'.$updatePosts['updateAdgroups'].'}'
			);
		}
		return array_merge($commonColumns, $tableColumns);
	}
?>
