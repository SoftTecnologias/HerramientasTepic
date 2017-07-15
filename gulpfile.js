var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss');
});

var gulp = require('gulp'),
    js_obfuscator = require('gulp-js-obfuscator');

var path = {
    build: {
        js: 'out/',
    },
    src: {
        js: '/js/**/*.js',
    }
};

gulp.src(path.src.js)
    .pipe(js_obfuscator({}, ["**/*.js"]))
    .pipe(gulp.dest(path.build.js));