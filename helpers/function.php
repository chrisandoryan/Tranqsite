<?php


if (!function_exists('dd')) {
	function dd($variable){
		print_r($variable);
		die();
	}
}

if (!function_exists('url')) {
    function url($url){
        if ($url[0] != '/')
            $url = "/$url";
        return sprintf(
            "%s://%s:%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME'],
			$_SERVER['SERVER_PORT'],
            $url
        );
    }
}

if (!function_exists('root')) {
    function root($root = 'public')
    {
        return explode($root, $_SERVER['DOCUMENT_ROOT'])[0] . "/";
    }
}

if (!function_exists('component')) {
	function component($path)
	{
		return require_once(root() . "components/$path.php");
	}
}

if (!function_exists('config')) {
    function config($path)
    {
        return require_once(root() . "config/$path.php");
    }
}

if (!function_exists('controller')) {
	function controller($path)
    {
        return require_once(root() . "controller/$path.php");
    }
}

if (!function_exists('back')) {
    function back()
    {
        $url = $_SERVER['HTTP_REFERER'];
        header("Location: $url");
        die();
    }
}

if (!function_exists('redirect')) {
    function redirect($url)
    {
        $url = url($url);
        header("Location: $url");
        die();
    }
}

if (!function_exists('asset')) {
    function asset($path) 
    {
        return url("$path");
    }
}

if (!function_exists('has_error')) {
    function has_error()
    {
        return (isset($_SESSION['error']));
    }
}

if (!function_exists('add_error')) {
    function add_error($error)
    {
        $_SESSION['error'] = $error;
    }
}

if (!function_exists('get_error')) {
    function get_error()
    {
        $error = $_SESSION['error'];
        unset($_SESSION['error']);
        return $error;
    }
}

if (!function_exists('has_message')) {
    function has_message()
    {
        return (isset($_SESSION['message']));
    }
}

if (!function_exists('add_message')) {
    function add_message($message)
    {
        $_SESSION['message'] = $message;
    }
}

if (!function_exists('get_message')) {
    function get_message()
    {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
        return $message;
    }
}

if (!function_exists('has_success')) {
    function has_success()
    {
        return (isset($_SESSION['success']));
    }
}

if (!function_exists('add_success')) {
    function add_success($success)
    {
        $_SESSION['success'] = $success;
    }
}

if (!function_exists('get_success')) {
    function get_success()
    {
        $success = $_SESSION['success'];
        unset($_SESSION['success']);
        return $success;
    }
}

if (!function_exists('is_auth')) {
    function is_auth()
    {
        if ($_SESSION['auth'] != true) 
            die('Not authenticated');
        else {
            die('Already authenticated');
        }
    }
}
