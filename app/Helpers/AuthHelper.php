<?php

namespace App\Helpers;

use Auth;

class AuthHelper
{
	public static function getGuard($request) {
		$class = get_class($request->user());

		switch ($class) {
			case 'App\Models\Users\User':
				return 'web';
		}

		return false;
	}

	public function hasAnyPermission($permissions) {
		$result = false;

		if ($this->authenticated('admin')) {
			$result = auth('admin')->user()->hasAnyPermission($permissions);
		}

		return $result;
	}

	public function renderName($format = 'f l') {
		$result = null;

		if (auth()->check()) {
			$result = auth()->user()->renderName($format);
		}

		return $result;
	}

	public function renderAvatar() {
		$result = null;

		if (auth()->check()) {
			$result = auth()->user()->renderImagePath();
		}

		return $result;
	}

	public function hasRole($roles) {
		$result = null;

		if (auth()->check()) {
			$result = auth()->user()->hasRole($roles);
		}

		return $result;
	}

	public function company() {
		$result = null;

		if (auth()->check()) {
			$result = auth()->user()->company_id;
		}

		return $result;
	}

	public function companyName() {
		$result = null;

		if (auth()->check()) {
			$result = auth()->user()->company ? auth()->user()->company->name :'Accounting System';
		}

		return $result;
	}

	public function authenticated($guard = null) {
		return auth($guard)->check();
	}
}