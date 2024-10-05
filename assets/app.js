// import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

// console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
// Variable pour récupérer les éléments du bouton pour le menu.
let btnNavbar = document.getElementById('navbar-toggle');
let navbar = document.getElementById('navbar-right');
let btnCloseNavbar = document.getElementById('btn-close');

let btnFirstListMenuMobile = document.getElementById('btn-disclosure-1');
let firstListMenuMobile = document.getElementById('list-menu');

let btnNavbarCategory = document.getElementById('btn-disclosure-category')
let categoryList = document.getElementById('category-navbar');
//  Action pour ouvrir/fermer le menu au format tablette/mobile
btnNavbar.addEventListener('click', () => {
    navbar.classList.toggle('hidden');
    firstListMenuMobile.classList.add('hidden')
})
btnCloseNavbar.addEventListener('click', () => {
    navbar.classList.toggle('hidden');
})

btnFirstListMenuMobile.addEventListener('click', () => {
    firstListMenuMobile.classList.toggle('hidden');

})
btnNavbarCategory.addEventListener('click', () => {
    categoryList.classList.toggle('hidden')
})
// 