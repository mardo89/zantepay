var gulp = require('gulp'),
  spritesmith = require('gulp.spritesmith'),
  config = require('../config'),
  merge = require('merge-stream');


gulp.task('sprite', function () {
  var spriteData = gulp.src(config.paths.img.entry_1).pipe(spritesmith({
    imgName: 'sprite.png',
    cssName: '_sprite.styl',
    padding: 5,
    algorithm: 'top-down',
    cssTemplate: 'gulp/stylus.template.handlebars'
  }));

  var imgStream = spriteData.img
    .pipe(gulp.dest(config.paths.img.dest));

  var cssStream = spriteData.css
    .pipe(gulp.dest(config.paths.img.styl));

  return merge(imgStream, cssStream);
});

gulp.task('sprite2x', function () {
  var spriteData = gulp.src(config.paths.img.entry_2).pipe(spritesmith({
    imgName: 'sprite@2x.png',
    cssName: '_sprite-2x.styl',
    padding: 5,
    algorithm: 'top-down',
    cssTemplate: 'gulp/stylus.template.handlebars'
  }));

  var imgStream = spriteData.img
    .pipe(gulp.dest(config.paths.img.dest));

  var cssStream = spriteData.css
    .pipe(gulp.dest(config.paths.img.styl));

  return merge(imgStream, cssStream);
});


