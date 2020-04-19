const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 | // original
 | mix.js('resources/js/app.js', 'public/js').sass('resources/sass/app.scss', 'public/css');
 */



/*
*  temperory small ones
*/
// mix.scripts(['resources/js/super/backy.js'], 'public/assets/js/super_main.js');
// mix.less('resources/less/super/backy.less', 'public/assets/css/super_main.css');

/* ******** FULL ******** */

// **** FRONT **** */

mix.scripts([
    'node_modules/jquery/dist/jquery.min.js',
    'node_modules/date-and-time/date-and-time.min.js',
    'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
], 'public/assets/js/front_libs.js');

mix.styles([
    'node_modules/bootstrap/dist/css/bootstrap.min.css',
    'node_modules/font-awesome/css/font-awesome.min.css'
], 'public/assets/css/front_libs.css');

mix.copyDirectory([
    'resources/imgs'
], 'public/assets/imgs');

mix.copyDirectory([
    'node_modules/font-awesome/fonts'
], 'public/assets/fonts');

mix.scripts(['resources/js/main/front.js'], 'public/assets/js/front_main.js');
mix.less('resources/less/front/entry.less', 'public/assets/css/front_main.css');


/* **** SUPER **** */

mix.scripts([
    'node_modules/jquery/dist/jquery.min.js',
    'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
    'node_modules/date-and-time/date-and-time.min.js',
    'node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
    'node_modules/underscore/underscore-min.js',
    'resources/adminlte/js/adminlte.js'
], 'public/assets/js/super_core_libs.js');

mix.copyDirectory('node_modules/tinymce/plugins', 'public/assets/js/tinymce/plugins')
    .copyDirectory('node_modules/tinymce/skins', 'public/assets/js/tinymce/skins')
    .copyDirectory('node_modules/tinymce/themes', 'public/assets/js/tinymce/themes')
    .copyDirectory('resources/js/main/tinymce/plugins', 'public/assets/js/tinymce/plugins')
    .copy('node_modules/tinymce/tinymce.min.js', 'public/assets/js/tinymce/')
    .minify('resources/js/main/tinymce/plugins/image/plugin.js');

mix.styles([
    'node_modules/font-awesome/css/font-awesome.min.css',
    'resources/adminlte/css/ionicons.min.css',
    'node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
    'resources/adminlte/css/adminlte.css',
    'resources/less/super/css/backy.less'
], 'public/assets/css/super_core_libs.css');

mix.scripts(['resources/js/super/backy.js'], 'public/assets/js/super_main.js');
mix.less('resources/less/super/backy.less', 'public/assets/css/super_main.css');

