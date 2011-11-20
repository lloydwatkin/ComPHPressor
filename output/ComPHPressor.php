<?php
include APPLICATION_PATH . '/src/ComPHPressor/Interface.php';

/**
 * ComPHPressor base class
 *
 * @author Lloyd Watkin <lloyd@evilprofessor.co.uk>
 * @since  19/11/2011
 * @package ComPHPressor
 */

/**
 * ComPHPressor base class
 *
 * @author Lloyd Watkin <lloyd@evilprofessor.co.uk>
 * @since  19/11/2011
 * @package ComPHPressor
 */
class ComPHPressor
    implements ComPHPressor_Interface
{
    /**
     * Stores configuration 
     *
     * @var array
     */
    protected $_config;

    /**
     * Stores uncleaned file list
     *
     * @var array
     */
    protected $_fileList;

    /**
     * Map of class ==> file
     *
     * @var array
     */
    protected $_classList;

    /**
     * Stores file action helper
     *
     * @var ComPHPressor_File_Action
     */
    protected $_fileActionHelper;

    /**
     * Base path
     * 
     * @var string
     */
    protected $_basePath;

    /**
     * Constructor, takes in config and checks
     *
     * @param array $config
     */
    public function __construct(array $config, $basePath = null)
    {
        if (is_null($basePath)) {
            $basePath = dirname(__FILE__);
        }
        $this->_basePath = $basePath;
        $this->_config   = $config;
    }

    /**
     * Build PHP autoloader for new setup
     *
     * @return boolean
     */
    public function build()
    {
        $this->_checkConfig();
        echo 'Configuration checked' . PHP_EOL;
        $this->_getFileList();
        echo 'File list loaded' . PHP_EOL;
        $this->_getClasses();
        echo 'Class list generated' . PHP_EOL;
        $this->_performFileMovements();
        echo 'File movements completed' . PHP_EOL;
        $this->_generateAutoloader();
        echo 'Autoloader generated' . PHP_EOL;
        echo 'Build complete...' . PHP_EOL;
    }

    /**
     * Perform file movements
     */
    protected function _performFileMovements()
    {
        $pathCompress = false;
        if (!isset($this->_config['output']['output.path-compress'])
            && (false == $this->_config['output']['output.path-compress'])
        ) {
            echo 'Path compression not selected' . PHP_EOL;
            return;
        }
        $actionHelper = $this->_getFileActionHelper();
        $outputPath   = $this->_config['output']['output.location'];
        if (isset($this->_config['output']['output.relative'])
            && (true == $this->_config['output']['output.relative'])
        ) {
            $outputPath = realpath($this->_basePath . '/' . $outputPath); 
        }
        foreach ($this->_classList as $class => $file) {
            $newFile = $actionHelper->copy(
                $class,
                $file,
                $outputPath
            );
            $this->_classList[$class] = $newFile;
            echo 'moving ' . $file . ' to ' . $this->_config['output']['output.location'] . PHP_EOL;
        }
    }

    /**
     * Generate autoloader
     */
    protected function _generateAutoloader()
    {
return;
        $this->_generateAutoloaderHeader();
        switch ($this->_config['output']['autoloader.type']) {
            case self::AUTOLOADER_TYPE_MAP:
            default:
                $type = self::AUTOLOADER_TYPE_MAP;
                $this->_generateMapAutoloader();
                break;
        }
        $this->_autoloaderFooter();
    }

    /**
     * Check configuration
     * 
     * @return boolean
     */
    protected function _checkConfig()
    {
        if (!isset($this->_config['input']['path'])
            || !is_array($this->_config['input']['path'])
            || (0 === count($this->_config['input']['path']))
        ) {
            throw new RuntimeException('Bad input configuation');
        }
        if (!isset($this->_config['output']['autoloader']['type'])) {
            $this->_config['output']['autloader']['type'] = 'map';
        }
        return true;
    }

    /**
     * Retrive class list from file
     *
     */
    protected function _getClasses()
    {
        $actionHelper = $this->_getFileActionHelper();
        foreach ($this->_fileList as $file) {
             if ($class = $actionHelper->getClassName($file)) {
                 if (count($class) > 1) {
                     throw new RuntimeException(self::MULTIPLE_CLASS_IN_FILE . " ({$file})");
                 }
                 $this->_classList[$class[0]] = $file; 
             }
        }
    }

    /**
     * Retrieve file list for autoloader building
     */
    protected function _getFileList()
    {
        $files        = array();
        $relativePath = (boolean) $this->_config['input']['relative'];
        $actionHelper = $this->_getFileActionHelper();
        foreach ($this->_config['input']['path'] as $path) {
            $newFiles  = $actionHelper->getFiles($path, $relativePath); 
            $files    += $newFiles;
            echo count($newFiles) . ' files to parse in path ' . $path . PHP_EOL;   
        }
        echo PHP_EOL . 'There are ' . count($files) . ' files to parse' . PHP_EOL;
        $this->_fileList = $files;
        return true;
    }

    /**
     * Set file actions helper
     *
     * @param  ComPHPressor_File_Actions_Interface $actions
     * @return $this *Provides a fluent interface*
     */
    public function setFileActionHelper(ComPHPressor_File_Actions_Interface $actions)
    {
        $this->_fileActionHelper = $actions;
        return $this;
    }
    
    /**
     * Retrieve file actions helper
     *
     * @return ComPHPressor_File_Actions_Interface
     */
    protected function _getFileActionHelper()
    {
        if (is_null($this->_fileActionHelper)) {
            include APPLICATION_PATH . '/src/ComPHPressor/File/Actions.php';
            $this->_fileActionHelper = new ComPHPressor_File_Actions($this->_basePath);
        }
        return $this->_fileActionHelper;
    }
}
