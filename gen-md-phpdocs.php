<?php

if ($argc < 2) {
    echo 'Usage: php gen-md-phpdocs -- <source directory>', PHP_EOL;
    exit;
}

$path = trim(escapeshellarg($argv[1]), "'");

echo 'Generating markdown PHP doc for namespaced classes and interfaces in: ', $path, PHP_EOL;
$cmd = './vendor/bin/phpdoc -d ' . $path . ' -t ' . './docs/ --ignore="' . $path . '/vendor/*" --template="./vendor/cvuorinen/phpdoc-markdown-public/data/templates/markdown-public"' . PHP_EOL;
echo $cmd;

passthru($cmd);
