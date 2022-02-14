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
			$selectOption="$selectOption>$value</option>";
			echo $selectOption;
		}
	}

?>
