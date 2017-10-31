var gulp = require('gulp'),
  beep = require('beepbeep'),
  plumber = require('gulp-plumber'),
  config = require('../config'),
  stylus = require('gulp-stylus'),
  sourcemaps = require('gulp-sourcemaps'),
  poststylus = require('gulp-postcss'),
  autoprefixer = require('autoprefixer'),
  cssnano = require('cssnano'),
  // focus = require('postcss-focus'),
  flexbug = require('postcss-flexbugs-fixes'),
  flexibility = require('postcss-flexibility'),
  aspectRatio = require('postcss-aspect-ratio'),
  fontpath = require('postcss-fontpath');


gulp.task('styles', function () {
  return gulp.src(config.paths.css.entry)
    .pipe(plumber({
      errorHandler: function(err) {
        beep();
        console.log(err);
        this.emit('end');
      }
    }))
    .pipe(sourcemaps.init())
    .pipe(stylus({
      errors: true,
      compress: true
    }))
    .pipe(sourcemaps.write())
    .pipe(poststylus([
      autoprefixer({
        browsers: ["> 0.2%"]
      }),
      // cssnano(),
      // focus(),
      flexbug(),
      fontpath(),
      flexibility(),
      aspectRatio()
    ]))
    .pipe(gulp.dest(config.paths.css.dest));
});
