<?php

namespace App\Enums\Auth;

enum Permissions: string
{
    case MOUNT_DASHBOARD = 'mount_dashboard';
    case MOUNT_PROFILE = 'mount_profile';
    case UPDATE_PROFILE = 'update_profile';
    case MOUNT_USER = 'mount_user';
    case READ_USER = 'read_user';
    case UPDATE_USER = 'update_user';
    case DELETE_USER = 'delete_user';
    case CREATE_USER = 'create_user';
    case MOUNT_APPLICATION = 'mount_application';
    case READ_APPLICATION = 'read_application';
    case DELETE_APPLICATION = 'delete_application';
    case MOUNT_CLIENT = 'mount_client';
    case READ_CLIENT = 'read_client';
    case CREATE_CLIENT = 'create_client';
    case UPDATE_CLIENT = 'update_client';
    case DELETE_CLIENT = 'delete_client';
    case MOUNT_SETTING = 'mount_setting';
    case UPDATE_SETTING = 'update_setting';
    case VIEW_HORIZON = 'view_horizon';
}
