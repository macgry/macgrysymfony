#!/bin/sh
RESULT_FILE="check_code.result.cache"
rm -f -- $RESULT_FILE
touch $RESULT_FILE

echo "Running php-cs-fixer..."
./vendor/bin/php-cs-fixer fix src/ --dry-run -vvv --rules=@Symfony,@PSR1,@PSR2,@PSR12 >> $RESULT_FILE
./vendor/bin/php-cs-fixer fix tests/ --dry-run -vvv --rules=@Symfony,@PSR1,@PSR2,@PSR12 >> $RESULT_FILE
rm -f -- .php-cs-fixer.dist.php
rm -f -- .php-cs-fixer.cache

echo "Running phpcs..."
./vendor/bin/phpcs --standard=Symfony src/ --ignore=Kernel.php >> $RESULT_FILE
./vendor/bin/phpcs --standard=Symfony tests/ --ignore=bootstrap.php >> $RESULT_FILE

echo "Running debug:translation..."
{
  ./bin/console debug:translation en --only-missing
  ./bin/console debug:translation pl --only-missing
} >> $RESULT_FILE

echo "Tear down..."
