#!/bin/bash

if [[ $1 == "html" ]]
then
	phpunit --testdox-html tests.html
else
	phpunit --testdox-text tests.txt 
	printf "\n\n---- TEST RESULTS ----\n\n"
	cat tests.txt
fi
