# kw_modules

Selecting which modules will run. This is the core of KWCMS. Because everything starts
with modules which represent blocks of content.

## PHP Installation

```
{
    "require": {
        "alex-kalanis/kw_modules": "dev-master"
    },
    "repositories": [
        {
            "type": "http",
            "url":  "https://github.com/alex-kalanis/kw_modules.git"
        }
    }
}
```

(Refer to [Composer Documentation](https://github.com/composer/composer/blob/master/doc/00-intro.md#introduction) if you are not
familiar with composer)


## PHP Usage

1.) Use your autoloader (if not already done via Composer autoloader)

2.) Add some external packages with connection to the local or remote services.

3.) Connect the "kalanis\kw_mapper\Records\ARecord" into your app. Extends it for setting your case.

4.) Extend your libraries by interfaces inside the package.

5.) Just call setting and render

## Caveats

The most of dialects for database has no limits when updating or deleting
- and roundabout way is to get sub-query with dialect-unknown primary column
by which the db will limit selection.

Another one is when you define children with the same alias - you cannot ask for
them in one query or it will mesh together and you got corrupted data. In better
case.

