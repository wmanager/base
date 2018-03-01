(function ($) {
	$.extend($.validator.messages, {
		required: "Required field.",
		remote: "Check this field.",
		email: "Enter a valid email address.",
		url: "Enter a valid web address.",
		date: "Enter a valid date.",
		dateISO: "Enter a valid date (ISO).",
		number: "Enter a valid number.",
		digits: "Enter only numbers.",
		creditcard: "Enter a valid credit card number.",
		equalTo: "The value does not match.",
		accept: "Enter a value with a valid extension.",
		maxlength: $.validator.format("Do not enter more of {0} characters."),
		minlength: $.validator.format("Enter at least {0} characters."),
		rangelength: $.validator.format("Enter a value between {0} and {1} characters."),
		range: $.validator.format("Enter a value between {0} and {1}."),
		max: $.validator.format("Enter a value less than or equal to {0}."),
		min: $.validator.format("Enter a value greater than or equal to {0}.")
	});
}(jQuery));