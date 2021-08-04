const nav = document.querySelector('.navbar');
let logoNavDark = document.getElementById('logoNavDark');
let logoNavLight = document.getElementById('logoNavLight');
window.addEventListener('scroll', () => {
    if (window.scrollY > 57) {
        nav.classList.add('scroll');
        logoNavDark.classList.add('active');
        logoNavLight.classList.add('desactive');
    } else {
        nav.classList.remove('scroll');
        logoNavDark.classList.remove('active');
        logoNavLight.classList.remove('desactive');
    }
})