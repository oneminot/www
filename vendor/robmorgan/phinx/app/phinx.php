<?php
/* Phinx
 *
 * (The MIT license)
 * Copyright (c) 2014 Rob Morgan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated * documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 */

if (!defined('PHINX_VERSION')) {
    define('PHINX_VERSION', (0 === strpos('@PHINX_VERSION@', '@PHINX_VERSION')) ? '0.4.1' : '@PHINX_VERSION@');
}

$files = array(
  __DIR__ . '/../../../autoload.php',
  __DIR__ . '/../src/Phinx/autoload.php.dist'
);

$found = false;
foreach ($files as $file) {
    if (file_exists($file)) {
        require $file;
        $found = true;
        break;
    }
}

if (!$found) {
    die(
      'You need to set up the project dependencies using the following commands:' . PHP_EOL .
      'curl -s http://getcomposer.org/installer | php' . PHP_EOL .
      'php composer.phar install' . PHP_EOL
    );
}

return new Phinx\Console\PhinxApplication(PHINX_VERSION);
