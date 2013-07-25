#!/bin/bash
# Usage: ./clearcache.sh from this directory
# Clears the generated javascript, CSS, and Kohana cache

rm -vfr oc/cache/*

# the (js|css) regex did not work on Mac, so this will have to do
find themes -type f -regex '.*/js/minified-.*\.js$' -exec rm -fv {} \; -o -regex '.*/css/minified-.*\.css$' -exec rm -fv {} \;
