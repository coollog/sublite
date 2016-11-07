Use http://sublite.net/stats.php to view stats on the database. It also
recalculates and updates the stats to be displayed on the home page. It also
determines the industries to have on the industry dropdown in the search.

It also gives a list of recruiters who have not posted jobs.

Use http://sublite.net/stats.php?cities to update cities counts too. This takes
a while.

To set up routing, uncomment in /etc/apache2/httpd.conf:
LoadModule rewrite_module libexec/apache2/mod_rewrite.so

## ES6 bundling and transpilation
We currently use `babel` to bundle and transpile our ES6 JS files. To set up
`brunch` on your machine, make sure that you're in the project root directory,
then install the dependencies listed in `package.json`.
```bash
npm i
```
You should now be ready to start `brunch`.
```bash
npm run watch
```
You should see the following output.
```bash
> sublite@1.0.0 watch /Users/chrisf/projects/sublite
> brunch watch

04 Oct 20:36:45 - info: compiled 3 files into 2 files, copied 122 in 1.6 sec
```

`brunch-config.js` is the config file for `brunch`. See
http://brunch.io/docs/config for more info.
