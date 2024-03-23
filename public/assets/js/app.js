(function() {
    "use strict";
    let loginForm    = document.getElementById('login-form');
    let submit       = document.getElementById('login-modal-submit');
    const loginModal = new bootstrap.Modal('#login-modal', {backdrop: static});
    let loginLink    = document.getElementById('open-login-modal');

    if (loginLink !== undefined) {
        loginLink.addEventListener('click', function(e) {
            loginModal.toggle();
        });
    }

    submit.addEventListener('click', function(e) {
        let action   = loginForm.getAttribute('action');
        let formData = new FormData(loginForm);
        //console.log(formData);
        fetch(action, {
            method: 'POST',
            body: formData,
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(response => {
            if (response.ok) {
                //let resObj = JSON.parse(response);
                console.log(response.json());
                loginModal.toggle();
                return response.json();
            } // maybe throw error...
        })
        .then(data => {
            // maybe close modal here after updating the nav...
        })
        .catch((error) => {
            alert('error reported');
        });
    });
})();