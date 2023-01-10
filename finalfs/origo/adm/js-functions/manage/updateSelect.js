function updateSelect(id, array)
{
	var select = document.getElementById(id);
	if (select.options != null)
	{
		var length = select.options.length;
		for (i = length-1; i >= 0; i--)
		{
			select.options[i] = null;
		}
	}
	array.forEach(function(item)
	{
		var newOption = document.createElement("option");
		newOption.text = item.toString();
		select.add(newOption);
	});
}
