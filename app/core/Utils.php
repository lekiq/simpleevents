<?php

namespace SimpleEvents\Core;

class Utils {

	public static function redirect(string $url): void
	{
		header("Location: $url");
		exit;
	}
}