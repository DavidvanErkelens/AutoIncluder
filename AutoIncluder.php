<?php
/**
 *  AutoIncluder.php
 *
 *  Class that takes care of automatic loading of classes in a certain directory.
 *  This requires that the classname is equal to the filename.
 *
 *  @author         David van Erkelens
 */

/**
 *  Class definition
 */
class AutoIncluder
{
    /**
     *  The folder to include files from
     *  @var  string
     */
    private $directory;

    /**
     *  Constructor
     *  @param  string      the directory to auto-include
     */
    public function __construct($directory)
    {
        // Store directory
        $this->directory = $directory;

        // Register autoload function
        spl_autoload_register('AutoIncluder::autoloadClass');
    }

    private function autoloadClass($className)
    {

        echo $this->directory;
        // Classes
        $classes = array();

        // Load existing links to classes
        if (file_exists($this->directory.'/autoloader_class_cache'))
        {
            $classes = unserialize(file_get_contents($this->directory.'/autoloader_class_cache'));

            if(array_key_exists($className, $classes))
            {
                // Get file
                $file = $classes[$className];

                if (file_exists($file)) 
                {
                    include($file);
                    return;
                }

                unset($classes[$className]);
            }
        }

        $result = $this->classExistsInFolder($className, new DirectoryIterator($this->directory));
        if ($result) 
        {
            $classes[$className] = $result;
            file_put_contents($this->directory.'/autoloader_class_cache', serialize($classes));
        }
    }

    private function classExistsInFolder($className, DirectoryIterator $iterator)
    {
        foreach ($iterator as $fileinfo)
        {
            if ($fileinfo->isDot()) continue;
            if ($fileinfo->isDir())
            {
                if ($fileDir = $this->  classExistsInFolder($className, new DirectoryIterator($fileinfo->getPathname()))) return $fileDir;
            }   

            if ($fileinfo->isFile())
            {
                echo $fileinfo->getFileName() . PHP_EOL;
                if ($fileinfo->getFileName() == $className . '.php')
                {
                    include($fileDir = $fileinfo->getPathname());
                    return $fileDir;
                }
            }
        }

        return false;
    }
}
