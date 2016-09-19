import through from 'through2';
import gutil from 'gulp-util';

const PluginError = gutil.PluginError;
const PLUGIN_NAME = 'gulp-js-script-injector';

export function gulpJsScriptInjector() {
  return through.obj((file, enc, cb) => {
    if (file.isStream()) {
      this.emit('error', new PluginError(PLUGIN_NAME,
        'Streams are not supported!'));
      return cb();
    }

    if (file.isBuffer()) {
      const contents = file.contents.toString('utf8').split('\n');
      for (const line of contents) {
        if (line.startsWith('// @require')) {
          console.log(line);
        }
      }
      return cb(null, file);
    }
  });
}
