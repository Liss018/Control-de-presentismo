
document.getElementById('menu-icon').addEventListener('click', function() {
    const nav = document.getElementById('nav');
    if (nav.style.display === 'flex') {
        nav.style.display = 'none';
    } else {
        nav.style.display = 'flex';
    }
});


