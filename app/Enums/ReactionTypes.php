<?php
namespace App\Enums;

enum ReactionTypes: string
{
    case LIKE = 'like';
    case DISLIKE = 'dislike';
}