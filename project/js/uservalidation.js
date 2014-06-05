// Form validation
// Login form validation
function login(form)
{
	// Check for empty form fields
	if(form.email.value == "")
	{
		alert("Please enter your email.");
		return false;
	}
	var mailFormat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;  
	if(!form.email.value.match(mailFormat))
	{
		alert("The email address you provided does not match the format for an email address.");
		return false;
	}
	if(form.password.value == "")
	{
		alert("Please enter your password.");
		return false;
	}
	// If no errors were detected, allow the form data to be passed to the server
	return true;
}
// Registration form validation
function registration(form)
{
	if(form.first_name.value == "")
	{
		alert("Please enter your first name.");
		return false;
	}
	if(form.last_name.value == "")
	{
		alert("Please enter your last name.");
		return false;
	}
	// Check if both the first (male) and second (female) options were selected
	if(form.gender[0].checked == false && form.gender[1].checked == false)
	{
		alert("Please provide your gender.");
		return false;
	}
	if(form.email.value == "")
	{
		alert("Please enter your email.");
		return false;
	}
	// Check the standard email format
	var mailFormat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;  
	if(!form.email.value.match(mailFormat))
	{
		alert("The email address you provided does not match the format for an email address.");
		return false;
	}
	if(form.password1.value == "")
	{
		alert("Please enter your password for the first field.");
		return false;
	}
	if(form.password2.value == "")
	{
		alert("Please enter your password for the second field.");
		return false;
	}
	if(form.password1.value.length < 4 || form.password2.value.length < 4)
	{
		alert("Please use a password at least 4 characters in length.");
		return false;
	}
	if(form.password1.value != form.password2.value)
	{
		alert("The passwords you entered do not match.");
		return false;
	}
	if(form.date_of_birth_day.value == "")
	{
		alert("Please enter the day for your date of birth.");
		return false;
	}
	if(form.date_of_birth_month.value == "")
	{
		alert("Please enter the month for your date of birth.");
		return false;
	}
	if(form.date_of_birth_year.value == "")
	{
		alert("Please enter the year for your date of birth.");
		return false;
	}
	if(form.tel_num.value == "")
	{
		alert("Please enter your telephone number or type 'n/a'.");
		return false;
	}
	var numFormat = /^(?:[-\s]?\d){7}$/;
	if(!form.tel_num.value.match(numFormat))
	{
		alert("The telephone number you provided does not match the required format.");
		return false;
	}
	// Check for the validity of the date
	// Acquire the year for more concise statements
	year = form.date_of_birth_year.value;
	// Ensure the day cannot exceed 30 for April, June, November and September
	if(form.date_of_birth_day.value > 30 && (form.date_of_birth_month.value == 4 || form.date_of_birth_month.value == 6 || form.date_of_birth_month.value == 9 || form.date_of_birth_month.value == 11))
	{
		alert("The day cannot exceed 30 for the month you selected for your date of birth.");
		return false;
	}
	// Ensure the day cannot exceed 28 if the month is February and the year is not a leap year.
	if(form.date_of_birth_day.value > 28 && form.date_of_birth_month.value == 2 && ((year % 4 != 0) || ((year % 100 === 0) && (year % 400 != 0))))
	{
		alert("The day cannot exceed 28 if the month selected was February and the year selected was not a leap year.");
		return false;
	}
	// Ensure the day cannot exceed 29 if the month is February and the year is a leap year.
	if(form.date_of_birth_day.value > 29 && form.date_of_birth_month.value == 2 && ((year % 4 === 0) || ((year % 100 === 0) && (year % 400 === 0))))
	{
		alert("The day cannot exceed 29 if the month selected was February and the year selected was a leap year.");
		return false;
	}
	// If no errors were detected, allow the form data to be passed to the server
	return true;
}
// Contact form validation
function contact(form)
{
	// Check for empty form fields
	if(form.name.value == "")
	{
		alert("Please enter your name.");
		return false;
	}
	if(form.email.value == "")
	{
		alert("Please enter your email.");
		return false;
	}
	var mailFormat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;  
	if(!form.email.value.match(mailFormat))
	{
		alert("The email address you provided does not match the format for an email address.");
		return false;
	}
	if(form.message.value == "")
	{
		alert("Please enter a message.");
		return false;
	}
	return true;
}
// User profile form validation
function profile(form)
{
	if(form.first_name.value == "")
	{
		alert("Please enter your first name.");
		return false;
	}
	if(form.last_name.value == "")
	{
		alert("Please enter your last name.");
		return false;
	}
	if(form.tel_num.value == "")
	{
		alert("Please enter your telephone number or type 'n/a'.");
		return false;
	}
	var numFormat = /^(?:[-\s]?\d){7}$/;
	if(!form.tel_num.value.match(numFormat))
	{
		alert("The telephone number you provided does not match the required format.");
		return false;
	}
	return true;
}
// Email update form validation
function emailUpdate(form)
{
	if(form.email.value == "")
	{
		alert("Please enter your email.");
		return false;
	}
	// Check the standard email format
	var mailFormat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;  
	if(!form.email.value.match(mailFormat))
	{
		alert("The email address you provided does not match the format for an email address.");
		return false;
	}
	return true;
}
// Password change form validation
function passwordChange(form)
{
	if(form.currentPassword.value == "")
	{
		alert("Please enter your current password.");
		return false;
	}
	if(form.newPassword1.value == "")
	{
		alert("Please enter your password for the first field.");
		return false;
	}
	if(form.newPassword2.value == "")
	{
		alert("Please enter your password for the second field.");
		return false;
	}
	if(form.newPassword1.value.length < 4 || form.newPassword2.value.length < 4)
	{
		alert("Please use a password at least 4 characters in length.");
		return false;
	}
	return true;
}
// Password reset request form validation
function resetRequest(form)
{
	if(form.email.value == "")
	{
		alert("Please enter your email.");
		return false;
	}
	var mailFormat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;  
	if(!form.email.value.match(mailFormat))
	{
		alert("The email address you provided does not match the format for an email address.");
		return false;
	}
	return true;
}
// Password reset form validation
function resetPassword(form)
{
	if(form.newPassword1.value == "")
	{
		alert("Please enter your password for the first field.");
		return false;
	}
	if(form.newPassword2.value == "")
	{
		alert("Please enter your password for the second field.");
		return false;
	}
	if(form.newPassword1.value.length < 4 || form.newPassword2.value.length < 4)
	{
		alert("Please use a password at least 4 characters in length.");
		return false;
	}
	return true;
}