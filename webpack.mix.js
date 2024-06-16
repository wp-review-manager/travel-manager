let mix = require('laravel-mix');
const path = require('path');

mix.webpackConfig({
    resolve: {
      alias: {
        '@': path.resolve(__dirname, 'src/admin'),
        '@components': path.resolve(__dirname, 'src/admin/components'),
        '@pages': path.resolve(__dirname, 'src/admin/pages') 
      },
    },
  });

mix.setPublicPath('assets');

mix.setResourceRoot('../');
mix
    .js('src/admin/start.js', 'assets/admin/js/start.js').vue({ version: 3 })
    .sass('src/scss/admin/app.scss', 'assets/css/element.css');
