var gulp = require('gulp'),
  config = require('../config'),
  browserSync = require('browser-sync'),
  reload = browserSync.reload;


gulp.task('html', function () {
  return gulp.src(config.paths.html.all)
    .pipe(reload({
      stream: true
    }));
});
