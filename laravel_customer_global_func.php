<?php
	
// php artisan make:provider HelperServiceProvider
// 移除 boot();
public function register() {

	foreach(glob(app_path('Helpers') . '/*.php') as $file) {
		require_once $file;
	}
}

// 打开 config/app.php，然后将 HelperServiceProvider 放在你的 AppServiceProvider 上面