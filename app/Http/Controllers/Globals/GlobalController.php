<?php

namespace App\Http\Controllers\Globals;

use App\Http\Controllers\Controller;
use App\Models\Users\User;

class GlobalController extends Controller
{
	public function fetchClients()
	{
		return User::getClients();
	}
}