let mix = require('laravel-mix');

const path = require('path');
let directory = path.basename(path.resolve(__dirname));

const dir = 'wp-content/themes/' + directory;
const assets = dir + '/assets';

mix
    .disableNotifications()

    // .copyDirectory(dir + '/resources/img', assets + '/assets/img')
    // .copyDirectory(dir + '/resources/fonts/SVN-Poppins', assets + '/assets/fonts/SVN-Poppins')
    // .copyDirectory(dir + '/resources/fonts/fontawesome/webfonts', assets + '/assets/webfonts')
    // .copyDirectory(dir + '/resources/js/plugins', assets + '/assets/js/plugins')

    .sass(dir + '/resources/sass/fonts.scss', assets + '/css')
    .sass(dir + '/resources/sass/admin.scss', assets + '/css')
    .sass(dir + '/resources/sass/editor-style.scss', assets + '/css')

    .sass(dir + '/resources/sass/plugins.scss', assets + '/css')
    .sass(dir + '/resources/sass/layout.scss', assets + '/css')
    .sass(dir + '/resources/sass/woocommerce.scss', assets + '/css')
    .sass(dir + '/resources/sass/elementor.scss', assets + '/css')
    .sass(dir + '/resources/sass/app.scss', assets + '/css')

    .js(dir + '/resources/js/login.js', assets + '/js')
    .js(dir + '/resources/js/admin.js', assets + '/js')
    .js(dir + '/resources/js/plugins-dev/draggable.js', assets + '/js/plugins')
    .js(dir + '/resources/js/plugins-dev/skip-link-focus-fix.js', assets + '/js/plugins')
    .js(dir + '/resources/js/plugins-dev/flex-gap.js', assets + '/js/plugins')

    .js(dir + '/resources/js/app.js', assets + '/js');
