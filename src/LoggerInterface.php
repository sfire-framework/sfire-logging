<?php
/**
 * sFire Framework (https://sfire.io)
 *
 * @link      https://github.com/sfire-framework/ for the canonical source repository
 * @copyright Copyright (c) 2014-2020 sFire Framework.
 * @license   http://sfire.io/license BSD 3-CLAUSE LICENSE
 */

declare(strict_types=1);

namespace sFire\Logging;


/**
 * Interface LoggerInterface
 * @package sFire\Logging
 */
interface LoggerInterface {


    /**
     * Write data to file
     * @param string $data
     */
    public function write(string $data);
}