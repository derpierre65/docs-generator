<?php

namespace Derpierre65\DocsGenerator\Generator\Plugin;

final class PluginManager
{
	protected array $listeners = [];

	protected array $plugins = [];

	public function registerPlugin(Plugin $plugin) : void
	{
		$this->plugins[] = $plugin;

		foreach ( $plugin->getEvents() as $index => $event ) {
			$this->listeners[$this->getKey($event['class'], $event['event'])][] = $event['callable'];
		}
	}

	public function fireAction(object|string $eventObject, string $eventName, array &$parameters = []) : void
	{
		$className = is_object($eventObject) ? get_class($eventObject) : $eventObject;
		$event = $this->getKey($className, $eventName);

		if ( empty($this->listeners[$event]) ) {
			return;
		}

		foreach ( $this->listeners[$event] as $key => $listener ) {
			$listener($parameters);
		}
	}

	protected function getKey(string $className, string $eventName) : string
	{
		return $className.'@'.$eventName;
	}
}