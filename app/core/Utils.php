<?php

namespace SimpleEvents\core;

class Utils {

	public static function redirect(string $url): void
	{
		header("Location: $url");
		exit;
	}
}