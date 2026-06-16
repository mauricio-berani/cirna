<?php

namespace App\Enums\Common;

enum ComponentEvents: string
{
    case ACTION_REQUIRED = 'action-required';
    case DELETE_ITEM = 'delete-item';
    case PROFILE_UPDATED = 'profile-updated';
    case SEARCH_UPDATED = 'search-updated';
    case STATUS_FILTER_UPDATED = 'status-filter-updated';
}
