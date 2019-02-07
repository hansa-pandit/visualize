var gulp = require('gulp');
var sass = require('gulp-sass');
var sassGlob = require('gulp-sass-glob');
var imagemin = require('gulp-imagemin');
var autoprefixer = require('gulp-autoprefixer');
var cleanCSS = require('gulp-clean-css');

gulp.task('compile:sass', function () {
    return gulp
        .src('sass/**/*.scss')
        .pipe(sassGlob())
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer({
            browsers: ['last 2 versions', 'iOS >=6'],
            cascade: false,
        }))
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(gulp.dest('css'));
});

gulp.task('compress:image', function() {
    return gulp
        .src('images/*')
        .pipe(imagemin([
            imagemin.gifsicle({interlaced: true}),
            imagemin.jpegtran({progressive: true}),
            imagemin.optipng({optimizationLevel: 5}),
            imagemin.svgo({
                plugins: [
                    {removeViewBox: true},
                    {cleanupIDs: false}
                ]
            })
        ]))
        .pipe(gulp.dest('compress-images'));
});

gulp.task('watch:sass', function(){
    gulp.watch('sass/**/*.scss', ['compile:sass']);

});
gulp.task('watch:images', function(){
    gulp.watch('images/*', ['compress:image']);

});


gulp.task('default', ['compile:sass', 'compress:image']);

gulp.task('watch', ['watch:sass', 'watch:images']);
