<?php

use Wenprise\Foundation\Application;

if (! function_exists('message')) {
    /**
     * 显示表单提示消息
     *
     * @param $message
     *
     * @deprecated
     */

    function message($message)
    {
        if ($message) {
            echo '<div class="alert alert-'.$message['type'].'">'.$message['message'].'</div>';
        }
    }
}

/**
 * 生成通知消息
 *
 * @param $type    string 通知消息类型
 * @param $message string 通知消息内容
 *
 * @return \Plasticbrain\FlashMessages\FlashMessages
 *
 * @deprecated
 */
if (! function_exists('flash')) {
    function flash($type, $message)
    {
        $msg = new \Plasticbrain\FlashMessages\FlashMessages();
        $msg->$type($message);

        return $msg;
    }
}

/**
 * 显示通知消息
 *
 * @deprecated
 */
if (! function_exists('messages')) {
    function messages()
    {
        $msg = new \Plasticbrain\FlashMessages\FlashMessages();
        $msg->display();
    }
}

if (! function_exists('wprs_set_paths')) {
    /**
     * 全局注册路径
     *
     * @param array $paths Paths to register using alias => path pairs.
     */
    function wprs_set_paths(array $paths)
    {
        foreach ($paths as $name => $path) {
            if (! realpath($path)) {
                wp_mkdir_p($path);
            }
            if (! isset($GLOBALS['wenprise.paths'][$name])) {
                $GLOBALS['wenprise.paths'][$name] = realpath($path).DS;
            }
        }
    }
}

if (! function_exists('wprs_path')) {
    /**
     * 获取前面注册的路径
     *
     * @param string $name The path name/alias. If none is provided, returns all registered paths.
     *
     * @return string|array
     */
    function wprs_path($name = '')
    {
        if (! empty($name)) {
            return $GLOBALS['wenprise.paths'][$name];
        }

        return $GLOBALS['wenprise.paths'];
    }
}

if (! function_exists('wprs_convert_path')) {
    /**
     * 转换 '.' 到 '/' 路径分隔符
     *
     * @param string $path 使用 '.' 分隔的原始字符串
     *
     * @return string 转换后的使用 '/' 分隔的字符串
     */
    function wprs_convert_path($path)
    {
        if (strpos($path, '.') !== false) {
            $path = str_replace('.', DS, $path);
        } else {
            $path = trim($path);
        }

        return (string) $path;
    }
}

if (! function_exists('config')) {
    /**
     * 获取设置值
     *
     * @param $key
     * @return mixed
     */
    function config($key)
    {
        return \Wenprise\Facades\Config::get($key);
    }
}

if (! function_exists('str_contains')) {
    /**
     * 判断一个字符串是否包含另一个
     *
     * @param string $haystack
     * @param string|array $needles
     *
     * @return bool
     */
    function str_contains($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle != '' && strpos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }
}

if (! function_exists('app')) {
    /**
     * 快速获取实例
     *
     * @param null $abstract 抽象实例名称
     * @param array $parameters
     *
     * @return mixed
     */
    function app($abstract = null, array $parameters = [])
    {
        $application = isset($GLOBALS['wenprise']) ? $GLOBALS['wenprise']->container : Application::getInstance();

        if (is_null($abstract)) {
            return $application;
        }

        return $application->make($abstract, $parameters);
    }
}

if (! function_exists('container')) {
    /**
     * 快速获取实例
     *
     * @param null $abstract 抽象实例名称
     * @param array $parameters
     *
     * @return mixed
     */
    function container($abstract = null, array $parameters = [])
    {
        return app($abstract, $parameters);
    }
}

if (! function_exists('wenprise')) {
    /**
     * 获取 Wenprise 类实例
     *
     * @return Wenprise
     */
    function wenprise()
    {
        if (! class_exists('Wenprise')) {
            wp_die('Wenprise has not yet been initialized. Please make sure the Wenprise framework is installed.');
        }

        return Wenprise::instance();
    }
}

if (! function_exists('view')) {
    /**
     * 创建视图的辅助函数
     *
     * @param string $view 视图相对路径，名
     * @param array $data 传入的数据
     *
     * @return string
     */
    function view($view = null, array $data = [], array $mergeData = [])
    {
        $factory = container('view');

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($view, $data, $mergeData)->render();
    }
}

if (! function_exists('meta')) {
    /**
     * 从对象中获取元数据
     *
     * @param string $key
     * @param int $id
     * @param string $context
     * @param bool $single
     *
     * @return mixed|string
     */
    function meta($key = '', $id = null, $context = 'post', $single = true)
    {
        if (is_null($id)) {
            $id = get_the_ID();
        }

        // If no ID found, return empty string.
        if (! $id) {
            return '';
        }

        return get_metadata($context, $id, $key, $single);
    }
}


/**
 * 渲染 Knp Menu 生成的菜单
 *
 * @param $menu
 *
 * @return mixed|string
 */
if ( ! function_exists( 'wprs_render_menu' ) ) {
    function wprs_render_menu( $menus )
    {
        $current_link = ( isset( $_SERVER[ 'HTTPS' ] ) ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        foreach ( $menus as $m ) {
            $m->setLinkAttribute( 'class', 'c-menu__link' );
            if ( $m->getUri() == $current_link ) {
                $m->setCurrent( true );
                $m->setLinkAttribute( 'class', 'c-menu__link is-active' );
            }
        }

        $renderer = new ListRenderer( new \Knp\Menu\Matcher\Matcher() );
        $menus    = $renderer->render( $menus, [
            'currentClass'  => 'is-active',
            'branch_class'  => 'c-menu__item',
            'leaf_class'    => 'c-menu__item',
            'ancestorClass' => 'c-menu__item',
        ] );

        $menus = str_replace( '&lt;', '<', $menus );
        $menus = str_replace( '&gt;', '>', $menus );
        $menus = str_replace( '&quot;', '"', $menus );

        return $menus;
    }
}