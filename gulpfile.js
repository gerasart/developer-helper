var gulp       = require('gulp'),
    rollup     = require('gulp-better-rollup'),
    watch = require('gulp-watch'),
    // watch = gulp.watch,
    // browserSync = require("browser-sync"),
    // reload = browserSync.reload,
    plumber = require('gulp-plumber'),
    // sass = require('gulp-sass'),
    // prefixer = require('gulp-autoprefixer'),
    // newer = require('gulp-newer'),
    // open = require('gulp-open'),
    // gutil = require('gulp-util'),
    sourcemaps = require('gulp-sourcemaps');

// =============================================================================//
// ПУТИ
// =============================================================================//

var paths = {
    url: 'http://galzhytlobud.svitsoft.com/',
    build: {  //Тут мы укажем куда складывать готовые после сборки файлы
        php: './',
        js: 'inc/js/',
        css: 'inc/css/',
    },
    src: {  //Пути откуда брать исходники
        php: ['src/*.php'],
        js: ['inc/js/src/*.js'],
        style: ['inc/scss/*.scss'],
    },
    watch: {  //Тут мы укажем, за изменением каких файлов мы хотим наблюдать
        php: '**/*.php',
        js: 'inc/js/src/**/*.js',
        style: 'inc/scss/**/*.scss',
    },
    clean: './build'
};

// -----------------------------------------------------------------------------
// webserver
// -----------------------------------------------------------------------------

function webServer() {
    browserSync.init({
        // port: 4000,
        // target: 'http://wp.docker.localhost:8000/',
        // changeOrigin: true,
        // notify: false
        // http://192.168.1.149:3000 local
        // server: {
        //     baseDir: '.'
        // },
        target: paths['url'],
        reloadDelay: 200,
        reloadDebounce: 500,
        injectChanges: true,
        files: [
            paths['watch']['css']
        ],
        injectFileTypes: [
            "css"
        ]
    });
    // done();
}

// BrowserSync Reload
function browserSyncReload() {
    browserSync.reload();
    // done();
}

function openBrowser() {
    var options = {
        uri: paths['url'],
        app: 'chrome'
    };
    gulp.src(__filename)
        .pipe(open(options));
}

// -----------------------------------------------------------------------------
// js
// -----------------------------------------------------------------------------

// gulp.task('js:build-old', function () {
//     return gulp.src(paths.src.js) //Найдем наш main файл
//         .pipe(newer('build/js'))
//         .pipe(plumber())
//         .pipe(rigger()) //Прогоним через rigger
//         // .pipe(gulpIf(isDevelopment, sourcemaps.init())) //Инициализируем sourcemap
//         .pipe(uglify()) //Сожмем наш js
//         // .pipe(gulpIf(isDevelopment, sourcemaps.write())) //Пропишем карты
//         .pipe(rename({suffix: '.min'})) // переименуем файл CSS
//         .pipe(plumber.stop())
//         .pipe(remember('js:build'))
//         .pipe(gulp.dest(paths.build.js)) //Выплюнем готовый файл в build
//         .pipe(reload({stream: true})); //И перезагрузим сервер
// });


function jsBuild() {
    // console.log('jsBuild');
    return gulp.src(paths.src.js)
        .pipe(plumber())
        .pipe(sourcemaps.init())
        // transform the files here.
        .pipe(rollup('es'))
        .pipe(sourcemaps.write())
        .pipe(plumber.stop())
        .pipe(gulp.dest(paths.build.js))
        // .pipe(browserSync.stream())
        ;
}

// -----------------------------------------------------------------------------
// style
// -----------------------------------------------------------------------------

function styleBuild() {
    // return gulp.src(paths.src.style)
    // // .pipe(changed('build/css'))
    //     .pipe(plumber(function (error) {
    //         gutil.log(gutil.colors.red(error.message));
    //         this.emit('end');
    //     }))
    //     .pipe(sourcemaps.init())
    //     .pipe(sass({
    //         includepathss: ['scss'],
    //         errLogToConsole: true
    //     }))
    //     // .pipe(prefixer({
    //     //   browsers: ['last 5 versions'],
    //     //   cascade: true
    //     // }))
    //     //.pipe(cleanCSS({compatibility: 'ie8'})) //Сожмем
    //     // .pipe(plumber.stop())
    //     //.pipe(rename({suffix: '.min'}))// переименуем файл CSS
    //     .pipe(sourcemaps.write('.'))
    //     .pipe(gulp.dest(paths.build.css)) // И в build
    //     .pipe(reload({stream: true})); //И перезагрузим сервер
}

// -----------------------------------------------------------------------------
// build
// -----------------------------------------------------------------------------

gulp.task('web_server', webServer);
gulp.task('open_browser', openBrowser);
gulp.task('js:build', jsBuild);
gulp.task('style:build', styleBuild);


// -----------------------------------------------------------------------------
// build
// -----------------------------------------------------------------------------

gulp.task('build', gulp.parallel(
    'js:build',
    //'plugins_js:build',
    // 'style:build',
    //'plugins_styles:build',
    //'image:build',
    //'fonts:build',
    //'favicon:build',
    //'sprite:build',
    //'video:build'
    // 'sftp-deploy-watch',
));

// -----------------------------------------------------------------------------
// watch
// -----------------------------------------------------------------------------

gulp.task('watch', function () {
    watch(paths.watch.js, jsBuild);
    // var watcher = gulp.watch(paths.watch.js);
    // watcher.on('all', function(event, path, stats) {
    //     console.log('File ' + path + ' was ' + event + ', running tasks...');
    //     jsBuild();
    // });
    //watch([paths.watch.plugins_js], function(event, cb) {
    //    gulp.start('plugins_js:build');
    //});
    watch(paths.watch.style, styleBuild);
    //watch([paths.watch.plugins_styles], function(event, cb) {
    //    gulp.start('plugins_styles:build');
    //});
    //watch([paths.watch.img], function(event, cb) {
    //    gulp.start('image:build');
    //});
    //watch([paths.watch.sprite], function(event, cb) {
    //    gulp.start('sprite:build');
    //});
    //watch([paths.watch.fonts], function(event, cb) {
    //    gulp.start('fonts:build');
    //});
    //watch([paths.watch.favicon], function(event, cb) {
    //    gulp.start('favicon:build');
    //});
    watch(paths.watch.php, browserSyncReload);
});

// -----------------------------------------------------------------------------
// default
// -----------------------------------------------------------------------------
// gulp.watch(['**/*.php', 'inc/**/*.php', paths.watch.js]).on('change', reload);
// gulp.task('default', ['build', 'webserver', 'open_browser', 'watch']);
gulp.task('server',gulp.series('build', gulp.parallel('open_browser', 'web_server', 'watch')));
// gulp.task('server',gulp.series('build', gulp.parallel('web_server', 'watch')));
// gulp.task('server',gulp.series('build', gulp.parallel('watch')));
gulp.task('default', gulp.series('build', 'watch'));