<?php
declare(strict_types=1);

namespace XBus;

interface RoleAuthorizerInterface
{
    public function authorize(array $roles);
}
