Use http://sublite.net/stats.php to view stats on the database. It also
recalculates and updates the stats to be displayed on the home page. It also
determines the industries to have on the industry dropdown in the search.

It also gives a list of recruiters who have not posted jobs.

Use http://sublite.net/stats.php?cities to update cities counts too. This takes
a while.

To set up routing, uncomment in /etc/apache2/httpd.conf:
LoadModule rewrite_module libexec/apache2/mod_rewrite.so

## ES6 transpilation
We currently use `gulp` to transpile our ES6 JS files. To set up `gulp` on your
machine, install the CLI.
```bash
npm i -g gulp-cli
```
Make sure that you're in the project root directory, then install the
dependencies listed in `package.json`.
```bash
npm i
```
You should now be ready to start `gulp`.
```bash
gulp
```
You should see the following output.
```bash
[23:57:10] Requiring external module babel-register
[23:57:11] Using gulpfile ~/projects/sublite/gulpfile.babel.js
[23:57:11] Starting 'watch'...
[23:57:11] Finished 'watch' after 24 ms
[23:57:11] Starting 'js-es6'...
[23:57:11] Finished 'js-es6' after 53 ms
[23:57:11] Starting 'default'...
[23:57:11] Finished 'default' after 25 μs
```

You can open `gulpfile.babel.js` to see exactly what `gulp` is doing.
`gulpfile.babel.js` currently contains a task, `'js-es6'`, that transpiles all
`.js` files in `app/views` using `babel` and outputs the transpiled files to
`app/dist/views`. At the moment, I have only demonstrated an example of this
reorganization with `app/views/student/index.php` and
`app/views/student/index.js`. Observe that the ES6 arrow function defined in
`app/views/student/index.js` has been converted to a regular JS `function ()`.

Once we finalize the directory structure, we can begin separating all the inline
JS code from the `.php` view files into their own files and take advantage of
some other ES6 features.
