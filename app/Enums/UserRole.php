<?php
namespace App\Enums;

enum UserRole: string
{
    case Admin     = 'admin';
    case Architect = 'architect';
    case Client    = 'client';
}