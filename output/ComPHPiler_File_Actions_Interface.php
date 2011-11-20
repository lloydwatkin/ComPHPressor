<?php
/**
 * Interface for file handling actions
 *
 * @author Lloyd Watkin <lloyd@evilprofessor.co.uk>
 * @since 19/11/2011
 * @package ComPHPressor
 * @subpackage File
 */

/**
 * Interface for file handling actions
 *
 * @author Lloyd Watkin <lloyd@evilprofessor.co.uk>
 * @since 19/11/2011
 * @package ComPHPressor
 * @subpackage File
 */
interface ComPHPressor_File_Actions_Interface
{
    /**
     * Errors
     * 
     * @var string
     */
    const PATH_DOES_NOT_EXIST = 'Path does not exist';

    /**
     * Constructor
     *
     * @param  string $basePath
     */
    public function __construct($basePath);

    /**
     * Copy file from one location to another
     *
     * @param  string $className
     * @param  string $existingFile
     * @param  string $outputPath
     * @return string
     */
    public function copy($className, $existingFile, $outputPath);

    /**
     * Return a list of PHP files in a directory
     *
     * @param  string  $path
     * @param  boolean $relativePath
     * @return array
     */
    public function getFiles($path, $relativePath = true);

    /**
     * Retrieve class name from a file
     *
     * @param  string $file
     * @return string
     */
    public function getClassName($file);
}
