onchange = function(e) {
	var target = (e && e.target) || (window.event && window.event.srcElement);

	if(target.className === 'done-box' && target.value !== '-1') {
		var rq = new XMLHttpRequest();

		rq.open('POST', 'api/state.php', true);
		rq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

		rq.onreadystatechange = function() {
			if(rq.readyState === 4) {
				if(rq.status < 400) {
					var data = JSON.parse(rq.responseText);

					if(data.logged_in === false) {
						// The user's session (presumably) expired; show the login page:
						window.location.href = 'login';
						return;
					}

					if(!data.success) {
						// What's going on?
						alert('There was an error updating the box; please try again.');
					}
				} else {
					// This shouldn't happen, really.
					alert('There was an error updating the box; please try again.');
				}
			}
		};

		rq.send('id=' + target.value + '&done=' + +target.checked);
	}
};

onclick = function(e) {
	var target = (e && e.target) || (window.event && window.event.srcElement);

	if(target.className === 'delete' ) {
		var rq = new XMLHttpRequest();

		rq.open('POST', 'api/delete.php', true);
		rq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

		rq.onreadystatechange = function() {
			if(rq.readyState === 4) {
				if(rq.status < 400) {
					var data = JSON.parse(rq.responseText);

					if(data.logged_in === false) {
						// The user's session (presumably) expired; show the login page:
						window.location.href = 'login';
						return;
					}

					if(data.success) {
						// Remove the item:
						target.parentNode.parentNode.removeChild(target.parentNode);
					} else {
						// What's going on?
						alert('There was an error deleting the item; please try again.');
					}
				} else {
					// This shouldn't happen, really.
					alert('There was an error deleting the item; please try again.');
				}
			}
		};
        
		rq.send('id=' + ttarget.href.split('=')[1]);

		return false;
	}
};

document.body.className = 'js';

document.getElementById('content').onsubmit = function() {
	var newItem = document.getElementsByTagName('li');
	newItem = newItem[newItem.length - 1]; // Sorry for using the same variable, but it's good to avoid nodes hanging around because of Internet Explorer, mostly.

	var newValue = newItem.lastChild;
	var doneBox = newItem.firstChild;
	var value = newValue.value;
	var done = doneBox.checked;

	if(value) {
		var rq = new XMLHttpRequest();

		rq.open('POST', 'api/add.php', true);
		rq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

		rq.onreadystatechange = function() {
			if(rq.readyState === 4) {
				if(rq.status < 400) {
					var data = JSON.parse(rq.responseText);

					if(data.logged_in === false) {
						// The user's session (presumably) expired; show the login page:
						window.location.href = 'login';
						return;
					}

					// Add the item to the end of the list:
					var todoItems = document.getElementById('todo-items');
					var newElement = document.createElement('li');
					var deleteLink = document.createElement('a');
					var newDoneBox = document.createElement('input');
					var newContent = document.createElement('span');

					deleteLink.className = 'delete';
					deleteLink.href = 'delete?id=' + data.id;
					deleteLink.appendChild(document.createTextNode('Delete'));

					newDoneBox.type = 'checkbox';
					newDoneBox.name = 'done[]';
					newDoneBox.value = data.id;
					newDoneBox.checked = done;
					newDoneBox.className = 'done-box';

					newContent.className = 'content';
					newContent.appendChild(document.createTextNode(value));

					newElement.appendChild(deleteLink);
					newElement.appendChild(document.createTextNode(' '));
					newElement.appendChild(newDoneBox);
					newElement.appendChild(document.createTextNode(' '));
					newElement.appendChild(newContent);

					todoItems.insertBefore(newElement, newItem);
				} else {
					// This shouldn't happen, really.
					alert('There was an error adding the item; please try again.');
				}
			}
		};

		rq.send('text=' + encodeURIComponent(value) + '&done=' + +done);

		// Reset the form, so there's some feedback (and to avoid double-submissions):
		newValue.value = '';
		doneBox.checked = false;
	}

	return false;
};
