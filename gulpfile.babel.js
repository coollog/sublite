import gulp from 'gulp';
import changed from 'gulp-changed';
import babel from 'gulp-babel';

const SRC = 'app/views/**/*.js';
const DEST = 'app/dist/views';

gulp.task('js-es6', () => {
  return gulp.src(SRC)
    .pipe(changed(DEST))
    .pipe(babel())
    .pipe(gulp.dest(DEST));
});

gulp.task('watch', () => {
  gulp.watch(SRC, ['js-es6']);
});

gulp.task('default', ['watch', 'js-es6']);
