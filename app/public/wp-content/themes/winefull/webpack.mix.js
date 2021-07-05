const mix= require('laravel-mix');
require('laravel-mix-tailwind');

mix.js('src/js/app.js', 'app.js')
  .sass('src/scss/app.scss', 'style.css')
  .tailwind()
  .disableSuccessNotifications();