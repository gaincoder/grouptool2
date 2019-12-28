var Encore = require('@symfony/webpack-encore');

Encore
// directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
     */
    .addEntry('default/closed_area', './assets/js/default/closed_area.js')
    .addEntry('default/public_area', './assets/js/default/public_area.js')
    .addEntry('gemeinsam_bewegen/closed_area', './assets/js/gemeinsam_bewegen/closed_area.js')
    .addEntry('gemeinsam_bewegen/public_area', './assets/js/gemeinsam_bewegen/public_area.js')
    .addEntry('pollform', './assets/js/pollform.js')

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

// uncomment if you use TypeScript
//.enableTypeScriptLoader()

// uncomment if you use Sass/SCSS files

    .enableSassLoader((options) => {
        options.sourceMap = true;
        options.sassOptions = {
            includePaths: [
                'node_modules', 'assets'
            ]
        }})


// uncomment if you're having problems with a jQuery plugin
.autoProvidejQuery()


     .copyFiles({
         from: './assets/images',
         to: 'images/[path][name].[ext]'
     })
;

module.exports = Encore.getWebpackConfig();