# Contributing to Open Classifieds

Looking to contribute something to Open Classifieds? **Here's how you can help.**

## Environment
Recommended PHP 5.4 , MySQL 5.5, Apache 2.2, Linux

For development and to enable debug and disable cache and minify you can create a vhost:

Host file:
127.0.0.1   reoc.lo

Vhost apache:
```
<VirtualHost *:80>
ServerName reoc.lo
DocumentRoot /var/www/reoc/
</VirtualHost>
````

## Git usage
Example to clone project on local:

git clone git@github.com:open-classifieds/openclassifieds2.git reoc
cd reoc
git branch -a (lists all the branches)
git checkout -b 2.0 origin/2.0 (or latest branch)


GIT files to ignore changes, DO NOT COMMIT THIS FILES:

git update-index --assume-unchanged robots.txt
git update-index --assume-unchanged oc/config/auth.php
git update-index --assume-unchanged oc/config/database.php
git update-index --assume-unchanged .htaccess
git update-index --assume-unchanged sitemap.xml.gz
git update-index --assume-unchanged sitemap.xml
git update-index --assume-unchanged install/install.lock
git update-index --assume-unchanged oc/cache/.empty

## Reporting issues

https://github.com/open-classifieds/openclassifieds2/issues

We only accept issues that are bug reports or feature requests. Bugs must be isolated and reproducible problems that we can fix within the Open Classifieds core. Please read the following guidelines before opening any issue.

1. **Search for existing issues.** We get a lot of duplicate issues, and you'd help us out a lot by first checking if someone else has reported the same issue. Moreover, the issue may have already been resolved with a fix available.
2. **Create an isolated and reproducible test case.** Be sure the problem exists in Open Classifieds code.
3. **Include a live example.** Make use of screenshots if needed.
4. **Share as much information as possible.** Include operating system and version, browser and version, version of OC, customized or vanilla build, etc. where appropriate. Also include steps to reproduce the bug.



## Key branches

- In the home page 2.0.7 shown branch is the current deployed branch.
- The previous branch 2.0.x is the latest, deployed version.
- The current development branch is 2.1.


## Pull requests

- Try to submit pull requests against the current development branch for easier merging
- Try not to pollute your pull request with unintended changes--keep them simple and small
- Try to share which browsers your code has been tested in before submitting a pull request



## Coding standards

PHP http://kohanaframework.org/3.2/guide/kohana/conventions
SQL https://github.com/open-classifieds/openclassifieds2/wiki/SQL-Coding-Standard

## License

By contributing your code, you agree to license your contribution under the terms of the GPLv3: Read LICENSE.md
