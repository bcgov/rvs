const mix = require('laravel-mix');
const fs = require('fs');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
// Define the path to your Laravel Modules directory
const modulesPath = path.join(__dirname, 'Modules');

// Loop through module directories
fs.readdirSync(modulesPath).forEach(moduleName => {
    const modulePath = path.join(modulesPath, moduleName);

    // Check if the module has a 'Resources/css/app.css' file
    const cssPath = path.join(modulePath, 'Resources/assets/css/app.css');
    if (fs.existsSync(cssPath)) {
        // Compile the CSS file into a module-specific output directory
        mix.postCss(cssPath, 'public/css');
       // mix.sass(cssPath, `public/css/modules/${moduleName}.css`);
    }
});

mix.js('resources/js/app.js', 'public/js')
    .copyDirectory('resources/images', 'public/images')

    .vue()
    .postCss('resources/css/app.css', 'public/css', [require('tailwindcss'), require('autoprefixer')])
        //
    .alias({
        '@': 'resources/js',
    });
const webpack = require('webpack');

if (mix.inProduction()) {
    mix.version();
    mix.webpackConfig({
        plugins: [
            new webpack.DefinePlugin({
            __VUE_OPTIONS_API__: JSON.stringify(true),
            __VUE_PROD_DEVTOOLS__: JSON.stringify(false),
            __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: JSON.stringify(false),
            }),
        ],
    });

}else{
    mix.webpackConfig({
        plugins: [
            new webpack.DefinePlugin({
            __VUE_OPTIONS_API__: JSON.stringify(true),
            __VUE_PROD_DEVTOOLS__: JSON.stringify(true),
            __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: JSON.stringify(false),
            }),
        ],
    });
}
