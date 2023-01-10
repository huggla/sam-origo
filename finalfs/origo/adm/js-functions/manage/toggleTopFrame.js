function toggleTopFrame(type)
{
	var x = document.getElementById("topFrame");
	if (x.style.display === "none")
	{
		x.style.display = "block";
	}
	else if (topFrame === type)
	{
		x.style.display = "none";
	}
	topFrame = type;
}
