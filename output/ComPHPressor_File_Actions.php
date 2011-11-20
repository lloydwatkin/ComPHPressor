<?php
include APPLICATION_PATH . '/src/ComPHPressor/File/Actions/Interface.php';
/**
 * Class to handle file actions
 *
 * @author     Lloyd Watkin <lloyd@evilprofessor.co.uk>
 * @since      19/11/2011
 * @package    ComPHPressor
 * @subpackage File
 */

/**
 * Class to handle file actions
 *
 * @author     Lloyd Watkin <lloyd@evilprofessor.co.uk>
 * @since      19/11/2011
 * @package    ComPHPressor
 * @subpackage File
 */
class ComPHPressor_File_Actions
    implements ComPHPressor_File_Actions_Interface
{
    /**
     * Valid extensions
     *
     * @var array
     */
    protected $_validExtensions = array(
        'php',
    );

    /**
     * Base path
     *
     * @var string
     */
    protected $_basePath;

    /**
     * Stores recursive directory iterator
     *
     * @var RecursiveDirectoryIterator
     */
    protected $_directoryIterator;

    /**
     * Constructor
     *
     * @param  string $basePath
     */
    public function __construct($basePath)
    {
        $this->_basePath = $basePath;
    }

    /**
     * Return a list of PHP files in a directory
     *
     * @param  string  $path
     * @param  boolean $relativePath
     * @return array
     */
    public function getFiles($path, $relativePath = true)
    {
        $inputPath = $path;
        if (true === $relativePath) {
            $inputPath = $this->_basePath . '/' . $path;
        }
        if (!$inputPath = realpath($inputPath)) {
            throw new RuntimeException(self::PATH_DOES_NOT_EXIST . " ({$path})");
        }
        $this->_directoryIterator = new RecursiveDirectoryIterator($inputPath);
        $files                    = array(); 
        foreach (new RecursiveIteratorIterator($this->_directoryIterator) as $file) {
            $fileExtension = $file->getExtension();
            if (true === in_array($fileExtension, $this->_validExtensions)) {
                $files[] = $file->getPathName();
            }
        }
        return $files;
    }

    /**
     * Copy file from one location to another
     *
     * @param  string $className
     * @param  string $existingFile
     * @param  string $outputPath
     * @return string
     */
    public function copy($className, $existingFile, $outputPath)
    {
        $newFile = $className . '.php';
        $output  = $outputPath . '/' . $newFile;
        if (false === copy($existingFile, $output)) {
            throw new RuntimeException(
                'Could not copy from "' . $input . '" to "' . $output . '"'
            );
        }
        return $output;
    }

    /**
     * Retrieve class name from a file
     *
     * @param  string $file
     * @return array
     */
    public function getClassName($file)
    {
        $contents      = file_get_contents($file);
        $tokens        = token_get_all($contents);
        $isClassToken  = false;
        $foundClasses  = array();
        foreach ($tokens as $token) {
            if (true === is_array($token)) {
                if (true === in_array($token[0], array(T_CLASS, T_INTERFACE))) {
                    $isClassToken = true;
                } else if ((true === $isClassToken) 
                    && ($token[0] == T_STRING)
                ) {
                    $foundClasses[] = $token[1];
                    $isClassToken   = false;
                }
            }
        }
        return $foundClasses;
    }
}
