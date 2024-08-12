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
    .js('src/js/public/tm_public.js', 'assets/js/tm_public.js')
    .sass('src/scss/admin/app.scss', 'assets/css/element.css')
    .sass('src/scss/public/tm_public.scss', 'assets/css/tm_public.css')
    .copy('src/js/PaymentMethods', 'assets/js/PaymentMethods')
    .copy('src/img', 'assets/images')
