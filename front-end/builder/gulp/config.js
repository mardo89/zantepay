module.exports = {
  paths: {
    project: '../',
    html: {
      all: '../*.html'
    },
    css: {
      entry: '../src/stylus/*.styl',
      all: '../src/stylus/**/*.styl',
      dest: '../css/'
    },
    js: {
      entry: '../src/js/*.js',
      all: '../src/js/**/*.js',
      dest: '../js/'
    },
    img: {
      entry_1: '../src/sprite/default/*.png',
      entry_2: '../src/sprite/retina/*.png',
      styl: '../src/stylus/utils/',
      dest: '../images/'
    },
  }
};
