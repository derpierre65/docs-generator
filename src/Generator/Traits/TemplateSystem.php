<?php

namespace Derpierre65\DocsGenerator\Generator\Traits;

trait TemplateSystem
{
	protected function replaceTemplateVariables(string $template, array $variables) : string
	{
		$formattedVariables = [];
		foreach ( $variables as $key => $value ) {
			$formattedVariables['%'.$key.'%'] = $value;
		}

		return str_replace(array_keys($formattedVariables), array_values($formattedVariables), $template);
	}

	protected function getTemplate(string $template) : string
	{
		return file_get_contents($this->config['paths']['template'].$template.'.md') ?? '';
	}

	protected function buildSiteConfig(array $config) : string
	{
		if ( empty($config) ) {
			return '';
		}

		$html = "---\n";
		foreach ( $config as $key => $value ) {
			$html .= $key.': '.json_encode($value)."\n";
		}

		return $html . "---\n";
	}
}