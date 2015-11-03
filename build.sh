#!/bin/bash
rm -rf bin
echo "**************************************************************************"
echo "* Updating composer for the votertool package                            *"
echo "**************************************************************************"
composer update
mkdir -p bin
echo "**************************************************************************"
echo "* Building the bin/votertool.phar file                                   *"
echo "**************************************************************************"
vendor/bin/box build
echo "**************************************************************************"
echo "* Note: php.ini must have phar.readonly = Off set to build the phar file *"
echo "**************************************************************************"