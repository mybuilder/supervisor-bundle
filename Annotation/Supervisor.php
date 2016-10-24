<?php

namespace MyBuilder\Bundle\SupervisorBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Annotation which we can parse to generate a supervisor program
 *
 * @Annotation
 * @Target("CLASS")
 */
class Supervisor
{
    /**
     * @var integer
     */
    public $processes;

    /**
     * @var string
     */
    public $params;

    /**
     * @var string
     */
    public $executor;
}
