# IndexOf Enhanced

IndexOf Enhanced is an enhanced version of Apache Webserver's directory listing (IndexOf) that should work on any webserver running PHP. It's highly customizable and it comes with a dark and a light theme.

There are two versions: indexof and indexof_not-sortable :

- indexof is the full featured version of the script and lets the user sort files and directories.

- indexof_not-sortable is a light version of the script with a few less features. Remarkably files (and directories) can't be sorted because aren't stored into memory first (into an array) which should perform better for less powerful systems or very large directories. Since r2020-11-11 it becomes somewhat sortable, using SortTable by Stuart Langridge. There are some drawbacks: it requires the file sorttable.min.js on the server and JavaScript enabled on the client. It sorts file names alphabetically and case sensitive. Dates and File sizes are sorted alphabetically as well so that it's unreliable.

http://labs.geody.com/indexof/
