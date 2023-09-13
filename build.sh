export COMPOSER_HOME=/tmp/.composer
export npm_config_cache=$(mktemp -d) 
php composer.phar install --prefer-dist --no-interaction --no-scripts --no-progress --no-ansi --no-dev
php composer.phar dump-autoload

npm install
rm -rf $npm_config_cache
