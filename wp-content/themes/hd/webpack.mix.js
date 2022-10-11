/** */
let mix = require('laravel-mix');
//const purgeCss = require('@fullhuman/postcss-purgecss');
//const path = require('path');

mix.webpackConfig({
    resolve: {
        // options for resolving module requests
        // (does not apply to resolving of loaders)
        modules: [__dirname, 'node_modules']
    },
    stats: {
        children: true,
        warnings: true
    },
    externals: {
        // require("jquery") is external and available
        //  on the global var jQuery
        jquery: 'jQuery'
    },
    devtool: 'source-map'
});

/** */
mix.disableNotifications()
    .options({
        processCssUrls: false,
        postCss: [
            require('autoprefixer')({
                browsers: ['last 40 versions', 'iOS >= 9', 'not dead'],
                grid: true
            })
        ]
    });

// mix.copyDirectory('resources/img', 'assets/img')
//     .copyDirectory('resources/fonts/SVN-Poppins', 'assets/fonts/SVN-Poppins')
//     .copyDirectory('resources/fonts/fontawesome/webfonts', 'assets/webfonts')
//     .copyDirectory('resources/js/plugins', 'assets/js/plugins');

/** */
mix.setPublicPath('assets')
    .sourceMaps()

    // .sass('resources/sass/fonts.scss', 'css')
    // .sass('resources/sass/admin.scss', 'css')
    // .sass('resources/sass/editor-style.scss', 'css')

    //.sass('resources/sass/plugins.scss', 'css')
    .sass('resources/sass/layout.scss', 'css')

    .sass('resources/sass/woocommerce.scss', 'css')
    .sass('resources/sass/elementor.scss', 'css')
    .sass('resources/sass/app.scss', 'css')

    // .js('resources/js/login.js', 'js')
    //.js('resources/js/admin.js', 'js')
    //
    //.js('resources/js/plugins-dev/draggable.js', 'js/plugins')
    // .js('resources/js/plugins-dev/skip-link-focus-fix.js', 'js/plugins')
    // .js('resources/js/plugins-dev/flex-gap.js', 'js/plugins')

    .js('resources/js/app.js', 'js');
