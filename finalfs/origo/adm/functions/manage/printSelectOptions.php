<?php

	function printSelectOptions($optionValues, $selectedValue=false)
	{
		foreach ($optionValues as $value)
		{
			$selectOption="<option value='$value'";
			if ($selectedValue !== false)
			{
				if ($value == $selectedValue)
				{
					$selectOption="$selectOption selected";
				}
			}
			$selectOption="$selectOption>".ltrim(substr($value, strrpos($value,',')), ',')."</option>";
			echo $selectOption;
		}
	}

?>
