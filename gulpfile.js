'use strict'

var gulp = require('gulp')
var browserSync = require('browser-sync').create()

sass.compiler = require('node-sass')

gulp.task('serve', function () {
    browserSync.init({
        proxy: "one.wordpress.test"
    });
    // gulp.watch('./sass/**/*.scss', gulp.series('sass'))
    gulp.watch("*.php").on('change', browserSync.reload);
})

// gulp.task('sass', function () {
//     return gulp.src('./sass/**/*.scss')
//         .pipe(sass().on('error', sass.logError))
//         .pipe(gulp.dest('./'))
//         .pipe(browserSync.stream())
// })
