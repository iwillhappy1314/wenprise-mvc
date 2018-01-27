<?php

namespace Wenprise\View\Engines;

use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\ViewFinderInterface;
use Twig_Environment;

class TwigEngine extends PhpEngine {
	/**
	 * @var Twig_Environment
	 */
	protected $environment;

	/**
	 * @var \Illuminate\View\ViewFinderInterface
	 */
	protected $finder;

	/**
	 * @var string
	 */
	protected $extension = '.twig';

	public function __construct( Twig_Environment $environment, ViewFinderInterface $finder ) {
		$this->environment = $environment;
		$this->finder      = $finder;
	}


	/**
	 * Return the evaluated template.
	 *
	 * @param string $path The file name with its file extension.
	 * @param array  $data Template data (view data)
	 *
	 * @return string
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
	public function get( $path, array $data = [] ) {
		$file = array_search( $path, $this->finder->getViews() );

		/*
		 * Allow the use of a '.' notation.
		 */
		$file = wprs_convert_path( $file );

		return $this->environment->render( $file . $this->extension, $data );
	}
}
