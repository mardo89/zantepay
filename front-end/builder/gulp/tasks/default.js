var config = require('../config'),
    gulp = require('gulp'),
    browserSync = require('browser-sync');

gulp.task('watch', function() {
  gulp.watch(config.paths.css.all, ['styles']).on('change', function(evt) {
    browserSync.reload();
  });
  gulp.watch(config.paths.html.all, ['html']).on('change', function(evt) {
    browserSync.reload();
  });
});

gulp.task('default', ['styles', 'html', 'server', 'watch']);
