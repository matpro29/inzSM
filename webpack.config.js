var Encore = require('@symfony/webpack-encore');

Encore
// directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    .autoProvidejQuery()
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')
    // .cleanupOutputBeforeBuild()
    // .enableSassLoader()
    // .enableSourceMaps(!Encore.isProduction())
    .createSharedEntry('layout', './assets/js/layout.js')
    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
     */
    // .addEntry('layout-js', './assets/js/layout.js')
    .addEntry('/js/flatpickr', './assets/js/flatpickr-init.js')
    .addEntry('/js/owl', './assets/js/owl-carousel.js')

    //css
    .addStyleEntry('layout-css', './assets/scss/layout.scss')

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables Sass/SCSS support
    .enableSassLoader()

// uncomment if you use TypeScript
//.enableTypeScriptLoader()

// uncomment if you're having problems with a jQuery plugin
//.autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();

