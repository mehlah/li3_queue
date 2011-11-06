<?php

namespace li3_queue\extensions\data;

use \lithium\core\Object;

abstract class Queue extends \lithium\core\Object {

	/**
	 * Queue Constructor
	 *
	 * Sets parameters required by the queue adapter being used.
	 *
	 * @param array $config Configuration parameters.
	 * @return void
	 */
	public function __construct($config = array()) {
		$defaults = array();
		parent::__construct($config + $defaults);
	}

	/**
	 * Connect to the queue service
	 *
	 * @param array $parameters Connection-specific queue parameters.
	 * @return boolean True on successful connection, false otherwise.
	 */
	abstract public function connect($host, $port, $options = array());

	/**
	 * Adds a job to the queue
	 *
	 * @param object $job Job to be added
	 * @param integer $priority Priority of the job.
	 * @return boolean True on successful enqueue, false otherwise.
	 */
	abstract public function enqueue($Job, $priority = null);

	/**
	 * Remove a job from the queue
	 *
	 * @param mixed $id Job id to be removed.
	 * @return Boolean true if dequeue was successful, false otherwise.
	 */
	abstract public function dequeue($id);

	/**
	 * Returns the job identified by $id, but without removing it from
	 * the queue
	 *
	 * @param mixed $id Id of the job to peek at. If no id is set, then
	 *        peek at the next ready job.
	 * @return object Job object if it exists, null otherwise.
	 */
	abstract public function peek($id = null);
}
?>