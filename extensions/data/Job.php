<?php

namespace li3_queue\extensions\data;

use \lithium\core\Object;

class Job extends \lithium\core\Object {

	/**
	 * Unique identifier for the current job
	 *
	 * @var mixed Unique identifier (string, integer, UUID, etc.)
	 */
	public $id = null;

	/**
	 * Holds the current status of the job
	 *
	 * @var string A status type, e.g. 'ready' or 'reserved'.
	 */
	public $status = null;

	/**
	 * Job data
	 *
	 * @var mixed Data necessary for job processing
	 */
	public $data = null;

	/**
	 * Additional properties/information that may be required for the
	 * processing of this job by the requesting entity.
	 *
	 * @var array Optional additional properties
	 */
	public $properties = array();

	/**
	 * Constructor for a Job
	 *
	 * Allows setting of job properties on instantiation
	 */
	public function __construct($id, $data, $properties = array()) {
		$this->id = $id;
		$this->data = $data;

		if (!empty($properties)) {
			foreach ($properties as $type => &$property) {
				$this->{$type} = $property;
			}
		}
	}
}
?>