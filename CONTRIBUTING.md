# Contributing to Yclas Self-Hosted

Looking to contribute something to Yclas? **Here's how you can help.**

## Environment
Recommended PHP 5.5 , MySQL 5.5, Apache 2.2, Linux.

For development we recommend you to create a vhost called 'reoc.lo' this will enable debug/profiler tools, disable cache and disable minify

```
Host file:
127.0.0.1   reoc.lo
```

```
Vhost apache:
<VirtualHost *:80>
ServerName reoc.lo
DocumentRoot /var/www/yclas/
</VirtualHost>
```

## Cloning repo
Go to https://github.com/yclas for each repo in the top right theres a button that says Fork. Click there to clone each repo, don't forget to clone common. That will copy the repos to your github user, ex: https://github.com/USER?tab=repositories

Clone your project in local
```
git clone git@github.com:USER/yclas.git yclas && cd yclas
```

This will clone the yclas project



Ready ;)

## How to commit
If you have made modifications to the code.

```
git status # to see what's going on
git commit -a -m 'message here, this will commit the changes on the tracked files'
git push origin master # will "upload" the changes to your repo
```

Tricks
```
git add . # will add all the files, even new ones
git add -u # will add all the tracked files even the deleted ones
git commit -a -m 'working on yclas/yclas#725' # this will mention an issue in the repo
```


## Pull Requests

Now you have new code at your fork ex https://github.com/USER/yclas. To move them to the original https://github.com/yclas/yclas repo you need to go to https://github.com/USER/yclas, and click on Pull Request (next to compare). This will create a pull request to the original code and the responsible will decide to merge it or not.

Notes:
- Try to submit pull requests against master branch for easier merging
- Try not to pollute your pull request with unintended changes--keep them simple and small
- Try to share which browsers your code has been tested in before submitting a pull request

## Keep sync with original repo
First time, add a remote with the upstream
```
git remote add upstream git@github.com:yclas/yclas.git
```

Everytime you want to sync just
```
git fetch upstream && git merge upstream/master
```

Remember to be at you master branch!

## Reporting issues

https://github.com/yclas/yclas/issues

We only accept issues that are bug reports or feature requests. Bugs must be isolated and reproducible problems that we can fix within the Yclas core. Please read the following guidelines before opening any issue.

1. **Search for existing issues.** We get a lot of duplicate issues, and you'd help us out a lot by first checking if someone else has reported the same issue. Moreover, the issue may have already been resolved with a fix available.
2. **Create an isolated and reproducible test case.** Be sure the problem exists in Yclas code.
3. **Include a live example.** Make use of screenshots if needed.
4. **Share as much information as possible.** Include operating system and version, browser and version, version of OC, customized or vanilla build, etc. where appropriate. Also include steps to reproduce the bug.



## Key branches

- master is the development branch.
- We create tags per release from master branch.
- We have many other branches not in use anymore since we changed the way we use the git flow.


## Coding standards

- PHP http://kohanaframework.org/3.3/guide/kohana/conventions
- SQL https://github.com/yclas/yclas/wiki/SQL-Coding-Standard

## License

By contributing your code, you agree to license your contribution under the terms of the GPLv3: Read [LICENSE](LICENSE)
