#!/bin/bash

### Check coding standards
vendor/bin/phpcs --standard=psr2 src
PHPCS=$?

### Check code quality
vendor/bin/phpmd src text codesize
PHPMD=$?

### Check for license headers
LICENSE=0

LICENSE_HEADER="<?php
/**
 * SclSocket Module (https://github.com/SCLInternet/SclSocket)
 *
 * @link https://github.com/SCLInternet/SclSocket for the canonical source repository
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */"

for i in `find src -name '*.php'`; do
    diff <(echo "$LICENSE_HEADER") <(head -7 "$i");

    if [ "$?" -ne "0" ]; then
        echo "Missing or invalid license header in \"$i\""
        let LICENSE=1
    fi
done

### Display results
EXIT=0

echo
echo "#### RESULTS:"

if [ "$PHPCS" -ne "0" ]; then
    echo "**** Coding standards failed"
    EXIT=1
fi
if [ "$PHPCS" -ne "0" ]; then
    echo "**** Mess detection failed"
    EXIT=1
fi
if [ "$LICENSE" -ne "0" ]; then
    echo "**** License header check failed"
    EXIT=1
fi

exit $EXIT
