document.getElementById('registration-box').onsubmit = function() {
	var rq = new XMLHttpRequest();

	rq.open('POST', 'register', true);
	rq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

	rq.onreadystatechange = function() {
		if(rq.readyState === 4) {
			if(rq.status < 400) {
				var data = JSON.parse(rq.responseText);

				if(data.success) {
					// Tell the user to check his/her e-mail:
					alert('All went well!');
				} else {
					// Clear any existing errors:
					var i;
					var existingErrors = document.querySelectorAll('.field-errors');

					for(i = 0; i < existingErrors.length; i++) {
						existingErrors[i].parentNode.removeChild(existingErrors[i]);
					}

					// Show the errors:
					if(data.errors) {
						var fieldErrors = {};

						for(i = 0; i < data.errors.length; i++) {
							var error = data.errors[i];

							if(error.relatedElement) {
								var errorItem = document.createElement('li');
								errorItem.innerHTML = error.message;

								if(fieldErrors.hasOwnProperty('field-' + error.relatedElement)) {
									fieldErrors['field-' + error.relatedElement].appendChild(errorItem);
								} else {
									var errorList = document.createElement('ul');
									errorList.className = 'field-errors';
									errorList.appendChild(errorItem);
									fieldErrors['field-' + error.relatedElement] = errorList;

									document.getElementById(error.relatedElement).parentNode.appendChild(errorList);
								}
							} else {
								
							}
						}
					} else {
						alert('There was an unknown problem during registration. Please try again.');
					}
				}
			} else {
				// Something went wrong! Redirect to the registration page, but pre-fill the e-mail field, too:
				window.location.href = 'register?e-mail=' + encodeURIComponent(document.getElementById('e-mail').value);
			}
		}
	};

	rq.send('ajax=1&' +
		    'e-mail=' + encodeURIComponent(document.getElementById('e-mail').value) + '&' +
		    'password=' + encodeURIComponent(document.getElementById('password').value) + '&' +
		    'repassword=' + encodeURIComponent(document.getElementById('repassword').value));

	return false;
};
