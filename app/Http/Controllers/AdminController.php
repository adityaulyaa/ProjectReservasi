<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\UserController;

class AdminController extends UserController
{
    // Inherits all user management methods (index, create, store, edit, update, destroy, verify, reject)
    // from App\Http\Controllers\Admin\UserController for full backward compatibility and direct AdminController usage.
}
