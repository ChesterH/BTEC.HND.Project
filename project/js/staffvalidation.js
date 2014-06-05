// Form validation
// Management centre form validation
// Edit product form validation
function productForm(form)
{
	// Check for empty form fields
	if(form.product.value == "")
	{
		alert("Please enter a name for the product.");
		return false;
	}
	if(form.details.value == "")
	{
		alert("Please enter the product's details.");
		return false;
	}
	if(form.price.value == "")
	{
		alert("Please enter the product's price.");
		return false;
	}
	if(form.link.value == "")
	{
		alert("Please enter the product's manufacturer's link.");
		return false;
	}
	// If no errors were detected, allow the form data to be passed to the server
	return true;
}
// Add and edit parent category form validation
function parentCategoryForm(form)
{
	if(form.parentCategory.value == "")
	{
		alert("Please enter a name for the parent category.");
		return false;
	}
	if(form.description.value == "")
	{
		alert("Please enter a description of the parent category.");
		return false;
	}
	return true;
}
// Add and edit sub-category form validation
function subCategoryForm(form)
{
	if(form.subCategory.value == "")
	{
		alert("Please enter a name for the sub-category.");
		return false;
	}
	if(form.description.value == "")
	{
		alert("Please enter a description of the sub-category.");
		return false;
	}
	return true;
}
// Technician centre form validation
// Add project form validation
function projectAdd(form)
{
	if(form.memberEmail.value == "")
	{
		alert("Please enter the client's email.");
		return false;
	}
	if(form.details.value == "")
	{
		alert("Please enter the project's details.");
		return false;
	}
	if(form.eDC.value == "")
	{
		alert("Please enter the project's estimated date of completion.");
		return false;
	}
	return true;
}
// Edit project form validation
function projectEdit(form)
{
	if(form.details.value == "")
	{
		alert("Please enter the project's details.");
		return false;
	}
	if(form.eDC.value == "")
	{
		alert("Please enter the project's estimated date of completion.");
		return false;
	}
	return true;
}