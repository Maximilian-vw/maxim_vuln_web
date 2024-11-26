const loginBtn = document.getElementById('login-btn');
const usernameInput = document.getElementById('username');
const passwordInput = document.getElementById('password');
const errorMessage = document.getElementById('error-message');

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

loginBtn.addEventListener('click', (e) => {
    e.preventDefault();
    const username = DOMPurify.sanitize(usernameInput.value);
    const password = DOMPurify.sanitize(passwordInput.value);

    // Validasi data
    if (username.length < 3) {
        usernameInput.classList.add('invalid');
        return;
    } else {
        usernameInput.classList.remove('invalid');
    }

    if (password.length < 3) {
        passwordInput.classList.add('invalid');
        return;
    } else {
        passwordInput.classList.remove('invalid');
    }

    if (!password.match(/^[a-zA-Z0-9]+$/)) {
        passwordInput.classList.add('invalid');
        return;
    } else {
        passwordInput.classList.remove('invalid');
    }

    // Jika validasi berhasil, maka kirimkan data ke backend
    const formData = new FormData();
    formData.append('wahyu', username);
    formData.append('vivin', password);
    formData.append('csrf_token', csrfToken); 

    fetch('/login.php', {
        method: 'POST',
        body: formData,
        credentials: 'include', 
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(responseText => {
        const sanitizedResponse = DOMPurify.sanitize(responseText);
        if (sanitizedResponse === 'success') {
           
        } else {
            errorMessage.innerText = 'Invalid username or password!';
        }
    })
    .catch(error => {
        console.error('There was a problem with the fetch operation:', error);
        errorMessage.innerText = 'An error occurred. Please try again later.';
    });
});
