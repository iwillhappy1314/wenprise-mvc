<?php

use Wenprise\Foundation\Application;


/**
 * 显示表单提示消息
 *
 * @param $message
 */
if ( ! function_exists( 'message' ) ) {
	function message( $message ) {
		if ( $message ) {
			echo '<div class="alert alert-' . $message[ 'type' ] . '">' . $message[ 'message' ] . '</div>';
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
 */
if ( ! function_exists( '' ) ) {
	function flash( $type, $message ) {
		$msg = new \Plasticbrain\FlashMessages\FlashMessages();
		$msg->$type( $message );

		return $msg;
	}
}


/**
 * 显示通知消息
 */
if ( ! function_exists( 'messages' ) ) {
	function messages() {
		$msg = new \Plasticbrain\FlashMessages\FlashMessages();
		$msg->display();
	}
}


if ( ! function_exists( 'wprs_set_paths' ) ) {
	/**
	 * 全局注册路径
	 *
	 * @param array $paths Paths to register using alias => path pairs.
	 */
	function wprs_set_paths( array $paths ) {
		foreach ( $paths as $name => $path ) {
			if ( ! isset( $GLOBALS[ 'wenprise.paths' ][ $name ] ) ) {
				$GLOBALS[ 'wenprise.paths' ][ $name ] = realpath( $path ) . DS;
			}
		}
	}
}

if ( ! function_exists( 'wprs_path' ) ) {
	/**
	 * 获取前面注册的路径
	 *
	 * @param string $name The path name/alias. If none is provided, returns all registered paths.
	 *
	 * @return string|array
	 */
	function wprs_path( $name = '' ) {
		if ( ! empty( $name ) ) {
			return $GLOBALS[ 'wenprise.paths' ][ $name ];
		}

		return $GLOBALS[ 'wenprise.paths' ];
	}
}


if ( ! function_exists( 'wprs_convert_path' ) ) {
	/**
	 * 转换 '.' 到 '/' 路径分隔符
	 *
	 * @param string $path 使用 '.' 分隔的原始字符串
	 *
	 * @return string 转换后的使用 '/' 分隔的字符串
	 */
	function wprs_convert_path( $path ) {
		if ( strpos( $path, '.' ) !== false ) {
			$path = str_replace( '.', DS, $path );
		} else {
			$path = trim( $path );
		}

		return (string) $path;
	}
}


if ( ! function_exists( 'str_contains' ) ) {
	/**
	 * 判断一个字符串是否包含另一个
	 *
	 * @param string       $haystack
	 * @param string|array $needles
	 *
	 * @return bool
	 */
	function str_contains( $haystack, $needles ) {
		foreach ( (array) $needles as $needle ) {
			if ( $needle != '' && strpos( $haystack, $needle ) !== false ) {
				return true;
			}
		}

		return false;
	}
}


if ( ! function_exists( 'app' ) ) {
	/**
	 * 快速获取实例
	 *
	 * @param null  $abstract 抽象实例名称
	 * @param array $parameters
	 *
	 * @return mixed
	 */
	function app( $abstract = null, array $parameters = [] ) {
		if ( is_null( $abstract ) ) {
			return Application::getInstance();
		}

		return Application::getInstance()->make( $abstract, $parameters );
	}
}


if ( ! function_exists( 'container' ) ) {
	/**
	 * 快速获取实例
	 *
	 * @param null  $abstract 抽象实例名称
	 * @param array $parameters
	 *
	 * @return mixed
	 */
	function container( $abstract = null, array $parameters = [] ) {
		return app( $abstract, $parameters );
	}
}


if ( ! function_exists( 'wenprise' ) ) {
	/**
	 * 获取 Wenprise 类实例
	 *
	 * @return Wenprise
	 */
	function wenprise() {
		if ( ! class_exists( 'Wenprise' ) ) {
			wp_die( 'Wenprise has not yet been initialized. Please make sure the Wenprise framework is installed.' );
		}

		return Wenprise::instance();
	}
}


if ( ! function_exists( 'view' ) ) {
	/**
	 * 创建视图的辅助函数
	 *
	 * @param string $view 视图相对路径，名
	 * @param array  $data 传入的数据
	 *
	 * @return string
	 */
	function view( $view = null, array $data = [], array $mergeData = [] ) {
		$factory = container( 'view' );

		if ( func_num_args() === 0 ) {
			return $factory;
		}

		return $factory->make( $view, $data, $mergeData )->render();
	}
}


if ( ! function_exists( 'meta' ) ) {
	/**
	 * 从对象中获取元数据
	 *
	 * @param string $key
	 * @param int    $id
	 * @param string $context
	 * @param bool   $single
	 *
	 * @return mixed|string
	 */
	function meta( $key = '', $id = null, $context = 'post', $single = true ) {
		if ( is_null( $id ) ) {
			$id = get_the_ID();
		}

		// If no ID found, return empty string.
		if ( ! $id ) {
			return '';
		}

		return get_metadata( $context, $id, $key, $single );
	}
}
