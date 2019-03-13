// Dependencies

const gulp = require('gulp'),
    sass = require('gulp-sass'),
    cleanCSS = require('gulp-clean-css'),
    prefix = require('gulp-autoprefixer'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),    
    htmlmin = require('gulp-htmlmin'),
    imagemin = require('gulp-imagemin'),    
    imageminJpegtran = require('imagemin-jpegtran'),
    imageminPngquant = require('imagemin-pngquant'), 
    imageminGifsicle = require('imagemin-gifsicle'), 
    imageminSvgo = require('imagemin-svgo'),    
    imageminOptipng = require('imagemin-optipng'),    
    del = require('del'),
    runSequence = require('run-sequence'),
    clean = require('gulp-clean'),
    sassfiles = ['**/*.sass','assets/**/*.scss'],
    environments = require('gulp-environments'),
    ext_replace = require('gulp-ext-replace'),
    foreach = require("gulp-foreach"),
    file = require("gulp-file"),
    addsrc = require('gulp-add-src'),
    files = [{name: "dist/functions.php",content: "<?php // Silence is golden... ?>"},
            {name: "dist/index.php",content: "<?php // Silence is golden... ?>"},
            {name: "dist/header.php",content: "<?php // Silence is golden... ?>"},
            {name: "dist/footer.php",content: "<?php // Silence is golden... ?>"},
            {name: "dist/home.php",content: "<?php // Silence is golden... ?>"},
            {name: "dist/page.php",content: "<?php // Silence is golden... ?>"}],
    browserSync = require('browser-sync').create();

var development = environments.development,
    production = environments.production;
    

// SASS / CSS generator 
gulp.task('sass', function() {
    gulp.src(sassfiles)
        .pipe(sass().on('error', sass.logError))
        .pipe(cleanCSS({
            compatibility: 'ie8',
            level: {
                1: {
                    cleanupCharsets: true, // controls `@charset` moving to the front of a stylesheet; defaults to `true`
                    normalizeUrls: true, // controls URL normalization; defaults to `true`
                    optimizeBackground: true, // controls `background` property optimizations; defaults to `true`
                    optimizeBorderRadius: true, // controls `border-radius` property optimizations; defaults to `true`
                    optimizeFilter: true, // controls `filter` property optimizations; defaults to `true`
                    optimizeFont: true, // controls `font` property optimizations; defaults to `true`
                    optimizeFontWeight: true, // controls `font-weight` property optimizations; defaults to `true`
                    optimizeOutline: true, // controls `outline` property optimizations; defaults to `true`
                    removeEmpty: true, // controls removing empty rules and nested blocks; defaults to `true`
                    removeNegativePaddings: true, // controls removing negative paddings; defaults to `true`
                    removeQuotes: false, // controls removing quotes when unnecessary; defaults to `true`
                    removeWhitespace: true, // controls removing unused whitespace; defaults to `true`
                    replaceMultipleZeros: true, // contols removing redundant zeros; defaults to `true`
                    replaceTimeUnits: true, // controls replacing time units with shorter values; defaults to `true`
                    replaceZeroUnits: true, // controls replacing zero values with units; defaults to `true`
                    roundingPrecision: false, // rounds pixel values to `N` decimal places; `false` disables rounding; defaults to `false`
                    selectorsSortingMethod: 'standard', // denotes selector sorting method; can be `'natural'` or `'standard'`, `'none'`, or false (the last two since 4.1.0); defaults to `'standard'`
                    specialComments: 1, // denotes a number of /*! ... */ comments preserved; defaults to `all`
                    tidyAtRules: true, // controls at-rules (e.g. `@charset`, `@import`) optimizing; defaults to `true`
                    tidyBlockScopes: true, // controls block scopes (e.g. `@media`) optimizing; defaults to `true`
                    tidySelectors: true // controls selectors optimizing; defaults to `true`,
                }
            },
            properties: {
                ieFilters: true, // controls keeping IE `filter` / `-ms-filter`
                iePrefixHack: true, // controls keeping IE prefix hack
                ieSuffixHack: true, // controls keeping IE suffix hack
                spaceAfterClosingBrace: true // controls keeping space after closing brace - `url() no-repeat` into `url()no-repeat`
            }
        }))
        .pipe(prefix(['last 15 versions', '> 1%', 'ie 8', 'ie 7']))
        .pipe(gulp.dest('./'))
        .pipe(browserSync.stream());
});

// CSS to dist
gulp.task('css-dist', function() {
    return gulp.src(['./style.css'])
        .pipe(gulp.dest('dist'));
});

// Delete actual commons and vendors for build updating
gulp.task('clean:js', function () {
    return gulp.src(['assets/js/commons.js','assets/js/vendors.js'], {read: false})
      .pipe(clean({force: true}))
});

gulp.task('scripts', ['clean:js'], function () {
    gulp.src(['assets/js/commons.js','assets/js/vendors.js'])
    .pipe(gulp.dest('assets/js/'));
});

// Commons .js generator
gulp.task('commons', function(){
  return gulp.src(['assets/**/*.js','!assets/js/commons.js','!assets/js/vendors.js'])
    .pipe(uglify())
    .pipe(concat('commons.js'))
    .pipe(gulp.dest('assets/js'));
});

// Vendors .js generator
gulp.task('vendors', function() {
  return gulp.src(['node_modules/jquery/dist/jquery.js'])
    .pipe(uglify())
    .pipe(concat('vendors.js'))
    .pipe(gulp.dest('assets/js'));
});

// .js to dist
gulp.task('js-dist', function() {
    return gulp.src(['assets/js/commons.js','assets/js/vendors.js'])
        .pipe(gulp.dest('dist/assets/js/'));
});

// Copy favico to dist
gulp.task('favico', function() {
    return gulp.src(['./.ico','./favico.*'])
        .pipe(imagemin({
            plugins: [
                imageminJpegtran(),
                imageminPngquant({quality: '65-80'})
            ]          
        }))    
        .pipe(development(gulp.dest('dist')));
});

// Copy htaccess gzip compressor to dist
gulp.task('htaccess', function() {
    return gulp.src('./.htaccess')
        .pipe(development(gulp.dest('dist')));
});

// Copy and minify images to dist
gulp.task('images', function(){
    return gulp.src(['assets/imgs/**/*.*'])
      .pipe(imagemin({
        plugins: [
            imageminJpegtran(),
            imageminPngquant({quality: '65-80'})
        ]          
      }))
      .pipe(gulp.dest('dist/assets/imgs'));
});

// Copy and minify html to dist
gulp.task('html', function() {
    return gulp.src('*.html')
    .pipe(development(htmlmin({collapseWhitespace: true})))
    .pipe(production(ext_replace('.php')))
    .pipe(gulp.dest('dist'));
});

// Create util files for prod:build
gulp.task("create-file", function() {   
    return gulp.src("./README.md", {base: "./dist"})
    .pipe(foreach(function(stream, f){
        files.forEach(function(gfile){
            stream
                .pipe(file(gfile.name, gfile.content))
                .pipe(production(gulp.dest("./")));
        });
        return stream;
    }))
    .pipe(production(gulp.dest("./dist")));
});

// Fonts to dist
gulp.task('fonts', function() {
    return gulp.src('assets/fonts/*')
        .pipe(gulp.dest('dist/assets/fonts/'));
});

// Browsersync + SASS + js generators and cleaner
gulp.task('serve', ['scripts', 'sass', 'commons', 'vendors'], function() {
    browserSync.init({
        server: './'
    });
    gulp.watch(sassfiles, ['sass']);
    gulp.watch(['**/*.html','assets/**/*.js','!assets/js/commons.js','!assets/js/vendors.js']).on('change', browserSync.reload);
    gulp.watch(['assets/**/*.js','!assets/js/commons.js','!assets/js/vendors.js'], ['scripts','commons','vendors']);
});

// Clean actual (if exist) dist before build
gulp.task('clean:build', function () {
    return del.sync(['dist']);
});

// Build task
gulp.task('build', function (callback) {
    console.log('Building project...')
    runSequence('clean:build', ['html', 'css-dist', 'images', 'favico', 'fonts', 'htaccess', 'js-dist', 'create-file'],
        callback
    );
});

// Default task
gulp.task('default', ['serve']);