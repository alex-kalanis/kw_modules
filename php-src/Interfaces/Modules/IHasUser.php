<?php

namespace kalanis\kw_modules\Interfaces\Modules;


use kalanis\kw_accounts\Interfaces\IUser;


/**
 * Class IHasUser
 * @package kalanis\kw_modules\Interfaces\Modules
 * Module has user
 */
interface IHasUser extends IModule
{
    /**
     * Return user
     * @return IUser|null
     */
    public function getUser(): ?IUser;
}
