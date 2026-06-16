<?php

namespace App\Enums\Common;

enum UserActions: string
{
    case MOUNT = 'mount';
    case READ = 'read';
    case UPDATE = 'update';
    case DELETE = 'delete';
    case CREATE = 'create';
}
