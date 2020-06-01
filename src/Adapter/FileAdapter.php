<?php 
/**
 * sFire Framework (https://sfire.io)
 *
 * @link      https://github.com/sfire-framework/ for the canonical source repository
 * @copyright Copyright (c) 2014-2020 sFire Framework.
 * @license   http://sfire.io/license BSD 3-CLAUSE LICENSE
 */

declare(strict_types=1);

namespace sFire\Logging\Adapter;

use sFire\Logging\Exception\BadFunctionCallException;
use sFire\Logging\Exception\RuntimeException;
use sFire\Logging\LoggerAbstract;
use sFire\FileControl\File;
use sFire\FileControl\Directory;


/**
 * Class FileAdapter
 * @package sFire\Logging
 */
class FileAdapter extends LoggerAbstract {
	

	public const HOUR 	= 'Y-m-d H';
    public const DAY 	= 'Y-m-d';
    public const WEEK 	= 'Y-W';
    public const MONTH = 'Y-m';
    public const YEAR 	= 'Y';


	/**
     * Contains an instance of Directory
	 * @var Directory
	 */
	private ?Directory $directory = null;


	/**
	 * Contains a string that will be appended to the log filename
	 * @var string
	 */
	private	?string $suffix = null;


	/**
	 * File extension of log file
	 * @var string 
	 */
	private	string $extension = '.log';


	/**
	 * Logging rotation mode
	 * @var string
	 */
	private	string $mode = self::DAY;


    /**
     * Sets the directory to write to
     * @param string $directory The path to the directory
     * @return self
     * @throws RuntimeException
     */
	public function setDirectory(string $directory): self {

		$this -> directory = new Directory($directory);

		if(false === $this -> directory -> isWritable()) {
			throw new RuntimeException(sprintf('Directory "%s" passed to %s() is not writable', $directory, __METHOD__));
		}

		return $this;
	}


	/**
	 * Returns the current directory
	 * @return Directory
	 */
	public function getDirectory(): ?Directory {
		return $this -> directory;
	}


	/**
	 * Set file extension of log file
	 * @param string $extension The extension of the log file
	 * @return self
	 */
	public function setExtension(string $extension): self {

		//Prepend dot for extension if necessary
		$extension = ($extension[0] === '.') ? $extension : '.' . $extension;
		
		$this -> extension = $extension;
		return $this;
	}


	/**
	 * Returns the file extension of log file
	 * @return string
	 */
	public function getExtension(): string {
		return $this -> extension;
	}


	/**
	 * Sets the log rotate mode to rotate log files for a hour, day, week, month or year
	 * @param string $mode
	 * @return self
	 */
	public function setMode(string $mode): self  {

		$this -> mode = $mode;
		return $this;
	}


	/**
	 * Returns the current rotation mode
	 * @return string
	 */
	public function getMode() {
		return $this -> mode;
	}


	/**
	 * Sets the suffix, a string that will be appended to the log filename
	 * @param string $suffix
	 * @return self
	 */
	public function setSuffix(string $suffix): self {

		$this -> suffix = $suffix;
		return $this;
	}


	/**
	 * Returns the current suffix for the filename
	 * @return string
	 */
	public function getSuffix(): ?string {
		return $this -> suffix;
	}


    /**
     * Write data to file
     * @param string $data
     * @return void
     * @throws BadFunctionCallException
     */
	public function write(string $data): void {

		if(null === $this -> directory) {
			throw new BadFunctionCallException(sprintf('Directory used in %s() is not set', __METHOD__));
		}

		$file = $this -> generateFileName();
		$file = new File($file);

		//Create file if not exists
		if(false === $file -> exists()) {
			$file -> create();
		}

		//Append data to file
		$file -> append($data . "\n");
	}


	/**
	 * Generates a filename with current rotation mode
	 * @return string
	 */
	private function generateFileName(): string {
		return $this -> directory -> getPath() . date($this -> getMode()) . $this -> getSuffix() . $this -> getExtension();
	}
}