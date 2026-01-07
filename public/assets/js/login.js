const password = document.querySelector('#password');
const eyeIcon = document.querySelector('#eyeIcon');

eyeIcon.addEventListener('click', () => {
    if(password.type === 'password'){
        password.type = 'text';
        eyeIcon.className = 'bx  bx-eye'; 
    } else {
        password.type = 'password';
        eyeIcon.className = 'bx  bx-eye-slash'; 
    }
});
