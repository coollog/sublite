import File from 'vinyl';
import fs from 'fs';
import { gulpJsScriptInjector } from '../src/plugin';
import { expect } from 'chai';

describe('gulp-js-script-injector', () => {
  describe('in buffer mode', () => {
    it('does stuff', done => {
      const contents = fs.readFileSync(__dirname + '/testfiles/test1.js');
      const contentsStr = contents.toString('utf8');
      const fakeFile = new File({
        contents
      });
      const injector = gulpJsScriptInjector();
      injector.write(fakeFile);
      injector.once('data', file => {
        expect(file.isBuffer()).to.equal(true);
        expect(file.contents.toString('utf8')).to.equal(contentsStr);
        done();
      });
    });
  });
});
