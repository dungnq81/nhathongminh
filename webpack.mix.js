let mix = require('laravel-mix');
let glob = require('glob');

mix
    //.sourceMaps()
    .options({
        processCssUrls: false,
        clearConsole: true,
        terser: {
            extractComments: false,
        },
        postCss: [
            require('autoprefixer')({
                //browsers: ['last 40 versions', '> 0.5%', 'iOS >= 7', 'Firefox ESR', 'not dead'],
                grid: true
            })
        ]
    });

// Run only for themes.
glob.sync('./wp-content/themes/**/webpack.mix.js').forEach(item => require(item));
