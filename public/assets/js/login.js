// const password = document.querySelector('#password');
// const eyeIcon = document.querySelector('#eyeIcon');

// eyeIcon.addEventListener('click', () => {
//     if(password.type === 'password'){
//         password.type = 'text';
//         eyeIcon.className = 'bx  bx-eye'; 
//     } else {
//         password.type = 'password';
//         eyeIcon.className = 'bx  bx-eye-slash'; 
//     }
// });

// Select all password fields and corresponding toggle icons
const togglePasswordIcons = document.querySelectorAll('.toggle-password');

togglePasswordIcons.forEach(icon => {
    icon.addEventListener('click', () => {
        const input = icon.previousElementSibling; // gets the input just before the icon
        if(input.type === 'password') {
            input.type = 'text';
            icon.className = 'bx bx-eye';
        } else {
            input.type = 'password';
            icon.className = 'bx bx-eye-slash';
        }
    });
});

