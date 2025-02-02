const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');

mix.webpackConfig({
   module: {
         rules: [
            {
               test: /\.m?js$/,
               exclude: /(node_modules|bower_components)/,
               use: {
                     loader: 'babel-loader',
                     options: {
                        presets: ['@babel/preset-env'],
                     },
               },
            },
         ],
   },
});
  