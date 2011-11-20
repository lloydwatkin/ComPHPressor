<?php
/**
 * ComPHPressor base class interface
 *
 * @author Lloyd Watkin <lloyd@evilprofessor.co.uk>
 * @since  19/11/2011
 * @package ComPHPressor
 */

/**
 * ComPHPressor base class interface
 *
 * @author Lloyd Watkin <lloyd@evilprofessor.co.uk>
 * @since  19/11/2011
 * @package ComPHPressor
 */
interface ComPHPressor_Interface
{
    /**
     * Errors
     *
     * @var string
     */
    const MULTIPLE_CLASS_IN_FILE = 'Multiple classes found within file';

    /**
     * Constructor, takes in config and checks
     *
     * @param array $config
     */
    public function __construct(array $config);

    /**
     * Build PHP autoloader for new setup
     *
     * @return boolean
     */
    public function build();
}
