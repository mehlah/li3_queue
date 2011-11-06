<?php

namespace li3_queue\extensions\data\queue\adapters;

use \lithium\util\socket\Stream;

class Beanstalk extends \li3_queue\extensions\data\Queue {

	/**
	 * The stream socket connection
	 *
	 * @var object
	 */
	protected $_stream = null;

	/**
	 * Default delay time for jobs
	 *
	 * An integer number of seconds to wait before putting the job in
	 * the ready queue. The job will be in the "delayed" state during this time.
	 *
	 * @var integer Delay time in seconds. Defaults to zero.
	 */
	public $delay = 0;

	/**
	 * Default priority for jobs
	 *
	 * an integer < 2**32. Jobs with smaller priority values will be
	 * scheduled before jobs with larger priorities. The most urgent priority is 0;
	 * the least urgent priority is 4294967295.
	 *
	 * @var integer Priority level. Defaults to 65536.
	 * @see http://github.com/kr/beanstalkd/blob/v1.3/doc/protocol.txt?raw=true
	 */
	public $priority = 65536;

	/**
	 * Default time to run for jobs
	 *
	 * an integer number of seconds to allow a worker to run this job.
	 * This time is counted from the moment a worker reserves this job.
	 *If the worker does not delete, release, or bury the job within
	 * <ttr> seconds, the job will time out and the server will release the job.
	 * The minimum ttr is 1. If the client sends 0, the server will silently
	 * increase the ttr to 1.
	 *
	 * @var integer Number of seconds to allow the job to complete.
	 *      Defaults to 120 seconds (2 minutes).
	 */
	public $runtime = 5;

	const USING = 'USING';

	const RESERVED = 'RESERVED';

	const RELEASED = 'RELEASED';

	const BURIED = 'BURIED';

	const DELETED = 'DELETED';

	const NOT_FOUND = 'NOT_FOUND';

	const INSERTED = 'INSERTED';

	const TIMED_OUT = 'TIMED_OUT';

	const JOB_TOO_BIG = 'JOB_TOO_BIG';

	/**
	 * Connect to the queue and store the resource handle
	 *
	 * @param string $host FQDN or IP of host running beanstalkd
	 * @param integer $port Port on which  beanstalkd is listening
	 * @param array $options Beanstalk specific connection options
	 * @todo Lazy-open stream connection
	 */
	public function connect($host, $port, $options = array()) {
		$options += array(
			'persistent' => true,
			'host' => $host,
			'port' => $port
		);
		$stream = new Stream($options);
		$resource = $stream->open();

		if (!is_resource($resource)) {
			return false;
		}
		$this->_stream = $resource;
		return true;
	}

	/**
	 * Close the stream connection
	 *
	 * @return boolean True on successful close, false if an error occurs, and
	 *         null if no stream connection exists.
	 */
	public function close() {
		if (!is_resource($this->_stream)) {
			return false;
		}
		return $this->_stream->close();
	}

	/**
	 * Adds a job to the queue
	 *
	 * @param object $Job Job to be added
	 * @param array $options Optional parameters for
	 * @return boolean True on successful enqueue, false otherwise
	 */
	public function enqueue($Job, $options = array()) {
		$options += array(
			'delay' => $this->delay,
			'priority' => $this->priority,
			'ttr' => $this->runtime
		);
	}

	/**
	 * Remove a job from the queue
	 *
	 * @param mixed $id Job id to be removed.
	 * @return Boolean true if dequeue was successful, false otherwise.
	 */
	public function dequeue($id) {

	}

	/**
	 * Obtain the stream resource for the active connection
	 *
	 * @return object Stream resource if it exists, null otherwise
	 */
	public function resource() {
		return $_stream;
	}

	/**
	 * Returns the job identified by $id, but without removing it from
	 * the queue
	 *
	 * @param mixed $id Id of the job to peek at. If no id is set, then
	 *        peek at the next ready job.
	 * @return object Job object if it exists, null otherwise.
	 */
	public function peek($id = null) {
		if (is_null($id)) {
			//return $this->_stream->send($this->_wrap('peek-ready'));
		}
	}

	/**
	 * Prepares commands and data to be sent to the beanstalkd service by first
	 * terminating them with \r\n EOL markers.
	 *
	 * @param string $data Data to be EOL terminated.
	 * @return EOL terminated string
	 */
	protected function _wrap($data) {
		return $data . "\r\n";
	}
}
?>