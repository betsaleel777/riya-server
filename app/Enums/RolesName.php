<?php

namespace App\Enums;

enum RolesName: string {
    case ADMIN = 'Administrateur';
    case FINANCIAL = 'Finance';
    case EMPLOYEE = 'Employée';
}
