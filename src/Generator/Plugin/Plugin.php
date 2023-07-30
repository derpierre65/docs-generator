<?php

namespace Derpierre65\DocsGenerator\Generator\Plugin;

abstract class Plugin
{
	abstract public function getEvents(): array;
}