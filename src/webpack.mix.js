const path = require('path');
const mix = require("laravel-mix");

mix.webpackConfig({
    resolve: {
        alias: {
            'jQuery': path.resolve(__dirname, 'node_modules/jquery/src/jquery')
        }
    }
});

mix.options({
    imgLoaderOptions: {
        enabled: false
    },
    autoprefixer: {
        remove: true
    }
})
    .sass("resources/sass/core/core.scss", "css/core.css")
    .sass('node_modules/dropzone/src/dropzone.scss', 'css/dropzone.css')
    .css("resources/vendor/summernote/summernote-bs4.min.css", "css/summernote-bs4.min.css")
    .css("resources/libs/fontawesome/css/all.min.css", "css/fontawesome.css")
    .js("resources/js/mainApp.js", "js/core.js")
    .js("resources/js/clientApp.js", "js/client.js")
    .js("resources/vendor/summernote/summernote-bs4.min.js", "js/summernote-bs4.min.js")
    .copy("resources/images", "public/images")
    .copy("resources/sass/core/fonts", "public/fonts")
    .copy("resources/libs/fontawesome/webfonts", "public/fonts")
    .extract(["vue", "jquery", "bootstrap", "popper.js", "axios", "sweetalert2", "lodash"])
    .sourceMaps()
    .vue()
    .autoload({ jquery: ["$", "window.jQuery"] })
    .browserSync("http://dev.apps.grupo-sei.net");

if (mix.inProduction()) {
    mix.version();
} else {
    mix.webpackConfig({
        devtool: "inline-source-map",
        stats: {
            warnings: true,
            errors: true
        },
        output: {
            publicPath: "public",
            filename: "[name].js",
            chunkFilename: "chunks/[name].js",
        }
    });
}
