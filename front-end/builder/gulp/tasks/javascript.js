var gulp = require('gulp'),
  concat = require('gulp-concat'),
  config = require('../config'),
  uglify = require('gulp-uglify');


gulp.task('scripts', function() {
  return gulp.src([
    'bower_components/jquery/dist/jquery.js',
    '../src/js/particles.min.js', 
    '../src/js/main.js', 
  ])
    .pipe(concat('main.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest(config.paths.js.dest));
});