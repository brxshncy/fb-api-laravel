<?php 

namespace App\Enums;


enum PostPrivacy: string 
{
    case PUBLIC = 'public';
    case FRIENDS = 'friends';
    case ONLY_ME = 'only_me';
}