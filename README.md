Use http://sublite.net/stats.php to view stats on the database.
It also recalculates and updates the stats to be displayed on the home page.
It also determines the industries to have on the industry dropdown in the search.

It also gives a list of recruiters who have not posted jobs.

Use http://sublite.net/stats.php?cities to update cities counts too. This takes a while.

To set up routing, uncomment in /etc/apache2/httpd.conf:
LoadModule rewrite_module libexec/apache2/mod_rewrite.so