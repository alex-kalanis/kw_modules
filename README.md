# kw_modules

![Build Status](https://github.com/alex-kalanis/kw_modules/actions/workflows/code_checks.yml/badge.svg)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alex-kalanis/kw_modules/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alex-kalanis/kw_modules/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/alex-kalanis/kw_modules/v/stable.svg?v=1)](https://packagist.org/packages/alex-kalanis/kw_modules)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)
[![Downloads](https://img.shields.io/packagist/dt/alex-kalanis/kw_modules.svg?v1)](https://packagist.org/packages/alex-kalanis/kw_modules)
[![License](https://poser.pugx.org/alex-kalanis/kw_modules/license.svg?v=1)](https://packagist.org/packages/alex-kalanis/kw_modules)
[![Code Coverage](https://scrutinizer-ci.com/g/alex-kalanis/kw_modules/badges/coverage.png?b=master&v=1)](https://scrutinizer-ci.com/g/alex-kalanis/kw_modules/?branch=master)

## Modules base

Selecting which modules will run. This is the core of KWCMS. Because everything starts
with modules which represent blocks of content.

### PHP Installation

```bash
composer.phar require alex-kalanis/kw_modules
```

(Refer to [Composer Documentation](https://github.com/composer/composer/blob/master/doc/00-intro.md#introduction) if you are not
familiar with composer)


### PHP Usage

1.) Use your autoloader (if not already done via Composer autoloader)

2.) Add some external packages with connection to the local or remote services.

3.) Connect the "\kalanis\kw_modules\Access\Factory::getProcessor" into your app and set necessary params.

4.) Extend your libraries by interfaces inside the package.

5.) Just call setting and render

### Caveats

This thing is created with tree in mind. So the loaders and modules itself are targeted
by arrays, not raw strings as was usual.
