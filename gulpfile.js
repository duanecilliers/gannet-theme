'use strict'

var gulp = require('gulp')
var browserSync = require('browser-sync').create()
const postcss = require('gulp-postcss')
const sourcemaps = require('gulp-sourcemaps')

gulp.task('serve', function () {
    browserSync.init({
        proxy: 'one.wordpress.test'
    });
    gulp.watch('css/**/*.css', gulp.series('css'))
    gulp.watch('*.php').on('change', browserSync.reload);
})

gulp.task('css', () => {
    return gulp.src('css/index.css')
        .pipe(sourcemaps.init())
        .pipe(postcss([
            require('postcss-import'),
            require('precss'),
            require('autoprefixer'),
            require('tailwindcss')
        ]))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('build/'))
        .pipe(browserSync.stream())
})
