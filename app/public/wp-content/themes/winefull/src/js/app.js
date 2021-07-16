// alert('goe bezig ELientje');
const navIcon = document.querySelector('#nav-icon');
const navItems = document.querySelector('#nav-items');

navIcon.addEventListener('click', e =>{
  navIcon.classList.toggle('open');
  navItems.classList.toggle('hidden');
})