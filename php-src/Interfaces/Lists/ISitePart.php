<?php

namespace kalanis\kw_modules\Interfaces\Lists;


/**
 * Class ISitePart
 * @package kalanis\kw_modules\Interfaces\Lists
 * Which part of site will be rendered
 */
interface ISitePart
{
    // data sources
    public const SITE_NOWHERE   = 0; # cannot directly use in any part of site, dummy modules
    public const SITE_RESPONSE  = 1; # basic modules accessing the whole response (watermark image, rss, default page content, ...) - GET/POST
    public const SITE_LAYOUT    = 2; # modules which will be loaded by response modules as part of layout (themes, logo, menu, langs) - <html>
    public const SITE_ROUTED    = 3; # modules which will be accessed via router - from URL, usually via some menu
    public const SITE_CONTENT   = 4; # modules which will be loaded by other modules as part of page content (dirlist, galleries, ...) - <body>

    public const KEY_LEVEL = 'level'; # when pass as an argument this one determine which module conf will be loaded
}
