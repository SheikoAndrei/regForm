(function () {
	'use strict'
	var forms = document.querySelectorAll('.needs-validation')
	Array.prototype.slice.call(forms)
		.forEach(function (form) {
			form.addEventListener('submit', function (elem) {
				elem.preventDefault();
				form.classList.add('was-validated');
				var timeout = 3000;
				var password_view = document.getElementById('password_view');
				var roller = document.getElementById('roller');
				var message = document.getElementById('message');
				var message_box = document.getElementById('message_box');




				if (password_view.checked==true) {
					password_view.click();
				}
				if (!form.checkValidity()) {
					elem.stopPropagation();
				}
				else {
					roller.style.visibility = 'visible';
					roller.classList.add('show');

					fetch('index.php', {method: 'POST',body: new FormData( document.getElementById('form') )})
					.then(response=>response.json())
					.then(data=>{
						roller.style.visibility = 'hidden';
						roller.classList.remove('show');
						message.style.visibility = 'visible';
						message.classList.add('show');
						setTimeout(() => {
							message.style.visibility = 'hidden';
							message.classList.remove('show');
							message_box.classList.remove('alert-warning');
							message_box.classList.remove('alert-success');
							}, timeout);

						if (data['ans']=='warning') {
							message_box.classList.add(data['class_alert']);
							message_box.innerHTML = data['view_text'].join('');
							document.getElementById('email').classList.add('is-invalid');
							
						}
						else if (data['ans']=='useradd') {
							document.getElementById('email').classList.remove('is-invalid');
							form.classList.remove('was-validated');
							message_box.classList.add(data['class_alert']);
							message_box.innerHTML = data['view_text'].join('');
							setTimeout(() => {
								document.getElementById('tclose').click();
								var inp = document.querySelectorAll('input');
								for (var i=0; i<inp.length; i++) { if (inp[i].getAttribute('aria-describedby')) { inp[i].value = ''; } }
							}, timeout);
						}
						else {
							message_box.classList.add('alert-warning');
							message_box.innerHTML = 'What a fuck one!';
							setTimeout(() => {
								document.getElementById('tclose').click();
								}, timeout);
							}
					})
					.catch(()=>{
						roller.style.visibility = 'hidden';
						roller.classList.remove('show');
						message.style.visibility = 'visible';
						message.classList.add('show');
						message_box.classList.add('alert-danger');
						message_box.innerHTML = 'What a fuck two!';
						setTimeout(() => {
							document.getElementById('tclose').click();
							message.style.visibility = 'hidden';
							message.classList.remove('show');
							}, timeout);
					});
				}
			}, false)
		})
})()

document.getElementById('password_random').addEventListener('click',()=>{
	var chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()+-_';
	var password = '';
	for (var i=0; i<=12; i++) {
		var rand = Math.floor(Math.random() * chars.length);
		password += chars.substring(rand, rand+1);
		}
	var idp1 = document.getElementById('password');
	var idp2 = document.getElementById('passwordr');
	idp1.value = password;
	idp2.value = password;
	if (document.getElementById('password_view').checked==false)
		{
		document.getElementById('password_view').checked = true;
		idp1.setAttribute('type','text');
		idp2.setAttribute('type','text');
		}
	});

document.getElementById('password_view').addEventListener('click',()=>{
	var idp1 = document.getElementById('password');
	var idp2 = document.getElementById('passwordr');
	if (idp1.getAttribute('type')=='password') {
		idp1.setAttribute('type','text');
		idp2.setAttribute('type','text');
	} else {
		idp1.setAttribute('type','password');
		idp2.setAttribute('type','password');
	}
	});

document.getElementById('email_required').addEventListener('click',()=>{
	var ider = document.getElementById('email');
	if (ider.getAttribute('required')==null) {
		ider.setAttribute('required','required');
	} else {
		ider.removeAttribute('required');
	}
	});
/*
document.getElementById('message').addEventListener('click',()=>{
	document.getElementById('message').style.visibility = 'hidden';
	document.getElementById('message').classList.remove('show');
	document.getElementById('message_box').classList.remove('alert-warning');
	document.getElementById('message_box').classList.remove('alert-success');
	});
*/
