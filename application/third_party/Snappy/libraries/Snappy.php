<?php

include_once APPPATH.'third_party/Snappy/vendor/composer/autoload_real.php';

ComposerAutoloaderInitc36c73ec6cca18923b04d1240ec238b0::getLoader();

class Snappy
{

	protected $pdf;

	public function __construct()
	{
		$this->pdf = new \Knp\Snappy\Pdf('/usr/local/bin/wkhtmltopdf');
	}

	public function getOutput($input, array $options = [])
	{
		return $this->pdf->getOutput($input, $options);
	}

	public function generateFromHtml($html, $output, array $options = [], $overwrite = false)
	{
		return $this->pdf->generateFromHtml($html, $output, $options = [], $overwrite);
	}

	public function getOutputFromHtml($html, array $options = [])
	{
		return $this->pdf->getOutputFromHtml($html, $options);
	}

	public function setOption($name, $value)
	{
		return $this->pdf->setOption($name, $value);
	}

	public function setOptions(array $options)
	{
		return $this->pdf->setOptions($options);
	}

}
