var gulp = require('gulp'),
  config = require('../config'),
  browserSync = require('browser-sync');


gulp.task('server', function() {
  browserSync.init({
    server: {
      baseDir: config.paths.project,
      routes: {
        "/bower_components": "bower_components"
      }
    },
    socket: {
        domain: "localhost:3000"
    }
  });
});
