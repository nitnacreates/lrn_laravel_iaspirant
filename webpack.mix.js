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

// mix.less('resources/less/front/plugins/clndr.less', 'public/assets/plugins/clndr/clndr.css');

/* ******** FULL ******** */


// ////////////////// PLUGINS ////////////////// 

mix.copyDirectory('node_modules/tinymce/plugins', 'public/assets/plugins/tinymce/plugins')
    .copyDirectory('node_modules/tinymce/skins', 'public/assets/plugins/tinymce/skins')
    .copyDirectory('node_modules/tinymce/themes', 'public/assets/plugins/tinymce/themes')
    .copyDirectory('resources/js/main/tinymce/plugins', 'public/assets/plugins/tinymce/plugins')
    .copy('node_modules/tinymce/tinymce.min.js', 'public/assets/plugins/tinymce/')
    .copy('node_modules/select2/dist/js/select2.min.js', 'public/assets/plugins/select2/')
    .copy('node_modules/select2/dist/css/select2.min.css', 'public/assets/plugins/select2/')
    .copy('node_modules/clndr/clndr.min.js', 'public/assets/plugins/clndr/')
    .copy('node_modules/chart.js/dist/Chart.min.css', 'public/assets/plugins/chart/')
    .copy('node_modules/chart.js/dist/Chart.min.js', 'public/assets/plugins/chart/')
    .copy('resources/less/front/plugins/triangle.svg', 'public/assets/plugins/clndr/');

mix.less('resources/less/front/plugins/clndr.less', 'public/assets/plugins/clndr/clndr.css');
mix.minify('resources/js/main/tinymce/plugins/image/plugin.js');
// ////////////////// FRONT ////////////////// 
 
mix.scripts([
    'node_modules/jquery/dist/jquery.min.js',
    'node_modules/underscore/underscore-min.js',
    'node_modules/moment/min/moment.min.js',
    'node_modules/countdown/countdown.js',
    'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
    'node_modules/bootbox/dist/bootbox.all.min.js',
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

mix.scripts([
    'resources/js/main/front.js',
    'resources/js/main/prelim.js',
    'resources/js/main/user.js'
], 'public/assets/js/front_user.js');
mix.scripts([
    'resources/js/main/front.js',
    'resources/js/main/prelim.js',
], 'public/assets/js/front_median.js');
mix.scripts([
    'resources/js/main/front.js'
], 'public/assets/js/front_main.js');

mix.less('resources/less/front/entry.less', 'public/assets/css/front_main.css');

// ////////////////// Super //////////////////

mix.scripts([
    'node_modules/jquery/dist/jquery.min.js',
    'node_modules/underscore/underscore-min.js',
    'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
    'node_modules/moment/min/moment.min.js',
    'node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
    'node_modules/bootbox/dist/bootbox.all.min.js',
    'resources/adminlte/js/adminlte.js'
], 'public/assets/js/super_core_libs.js');


mix.styles([
    'node_modules/font-awesome/css/font-awesome.min.css',
    'resources/adminlte/css/ionicons.min.css',
    'node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
    'resources/adminlte/css/adminlte.css',
    'resources/less/super/css/backy.less'
], 'public/assets/css/super_core_libs.css');

mix.scripts(['resources/js/super/backy.js'], 'public/assets/js/super_main.js');
mix.less('resources/less/super/backy.less', 'public/assets/css/super_main.css');
