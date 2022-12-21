<?php
	function printSelectOptions($optionValues, $selectedValue=null)
	{
		foreach ($optionValues as $value)
		{
			$selectOption="<option value='$value'";
			if (isset($selectedValue))
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
