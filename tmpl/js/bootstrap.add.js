(function () {
	'use strict'
	var forms = document.querySelectorAll('.needs-validation')
	Array.prototype.slice.call(forms)
		.forEach(function (form) {
			form.addEventListener('submit', function (e) {
				e.preventDefault();
				if (document.getElementById('password_view').checked==true) {
					document.getElementById('password_view').click();
				}
				if (!form.checkValidity()) {
					e.stopPropagation();
				}
				form.classList.add('was-validated');
				if (form.checkValidity()) {
					var r = document.getElementById('roller');
					var message = document.getElementById('message');
					var message_box = document.getElementById('message_box');
					if (r.style.visibility == 'hidden' || r.style.visibility == '') { r.style.visibility = 'visible' } else { r.style.visibility = 'hidden'; }
					fetch('index.php', {method: 'POST',body: new FormData( document.getElementById('form') )})
					.then(response=>response.json())
					.then(data=>{
						r.style.visibility = 'hidden';
						message.style.visibility = 'visible';
						if (data['ans']=='warning') {
							message_box.setAttribute('class','align-middle '+data['class_alert']);
							message_box.innerHTML = data['view_text'].join('');
							setTimeout(() => { message.style.visibility = 'hidden'; }, 2000);
						}
						else if (data['ans']=='useradd') {
							document.getElementById('form').setAttribute('class','needs-validation');
							message_box.setAttribute('class','align-middle '+data['class_alert']);
							message_box.innerHTML = data['view_text'].join('');
							setTimeout(() => {
								document.getElementById('tclose').click(); message.style.visibility = 'hidden';
								var inp = document.querySelectorAll('input');
								for (var i=0; i<inp.length; i++) {
									if (inp[i].getAttribute('aria-describedby')) { inp[i].value = ''; }
								}
							}, 2000);
						}
						else {
							message_box.setAttribute('class','align-middle alert alert-warning');
							message_box.innerHTML = 'What a fuck one!';
							setTimeout(() => { document.getElementById('tclose').click(); message.style.visibility = 'hidden'; }, 2000);
							}
						console.log(data);
					})
					.catch(()=>{
						r.style.visibility = 'hidden';
						message.style.visibility = 'visible';
						message_box.setAttribute('class','align-middle alert alert-danger');
						message_box.innerHTML = 'What a fuck two!';
						setTimeout(() => { document.getElementById('tclose').click(); message.style.visibility = 'hidden'; }, 2000);
					});
				}
			}, false)
		})
})()


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

