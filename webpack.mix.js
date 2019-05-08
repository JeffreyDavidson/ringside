const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.styles(
    [
        "resources/css/theme/style.bundle.css",
        "resources/css/theme/skins/header/base/light.css",
        "resources/css/theme/skins/header/menu/light.css",
        "resources/css/theme/skins/brand/dark.css",
        "resources/css/theme/skins/aside/dark.css"
    ],
    "public/css/theme.css"
);
mix.extract([
    "jquery",
    "popper.js",
    "bootstrap",
    "js-cookie",
    "moment",
    "tooltip.js",
    "perfect-scrollbar",
    "sticky-js",
    "wnumb",
    "datatables"
]);
mix.styles(
    [
        "resources/vendors/flaticon/flaticon.css",
        "resources/vendors/flaticon2/flaticon.css"
    ],
    "public/css/fonts.css"
);
mix.sass("resources/sass/app.scss", "public/css");

/************************************************************************
 * Copy Static Assets
 ************************************************************************/
mix.copyDirectory("resources/vendors/flaticon/font", "public/css/font");
mix.copyDirectory("resources/vendors/flaticon2/font", "public/css/font");

/************************************************************************
 * Mix Configuration
 * * We aren't keen on the "Build successful" notifications, so disable
 *   them
 * * We load in our webpack.config.js, which just contains aliases so
 *   PHPstorm reads them
 ************************************************************************/
mix.disableSuccessNotifications();
mix.sourceMaps(false, "inline-source-map");

mix.js(["resources/js/scripts.bundle.js"], "public/js/app.js");
