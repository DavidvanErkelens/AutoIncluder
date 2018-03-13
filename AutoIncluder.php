<?php
/**
 *  AutoIncluder.php
 *
 *  Class that takes care of automatic loading of classes in a certain directory.
 *  This requires that the classname is equal to the filename.
 *
 *  @author         David van Erkelens, 2018
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

    /**
     *  Function that is called when an unknown class is required
     *  @param  string
     */
    private function autoloadClass($className)
    {
        // Store known classes
        $classes = array();

        // Do we have a cached array of classes for this directory?
        if (file_exists($this->directory.'/autoloader_class_cache'))
        {
            // Get the classes already known
            $classes = unserialize(file_get_contents($this->directory.'/autoloader_class_cache'));

            // Have we seen this class before?
            if(array_key_exists($className, $classes))
            {
                // Get file required
                $file = $classes[$className];

                // Does the file still exist?
                if (file_exists($file)) 
                {
                    // Include file, we're done
                    include($file); return;
                }

                // File does not exist anymore, remove from array
                unset($classes[$className]);
            }
        }

        // Check if we can find the file in the folder
        $result = $this->classExistsInFolder($className, new DirectoryIterator($this->directory));

        // If found, store the result
        if ($result) 
        {
            // Add to classes array
            $classes[$className] = $result;

            // Cache known classes
            file_put_contents($this->directory.'/autoloader_class_cache', serialize($classes));
        }
    }

    /**
     *  Recursive helper function to check if a class exists in a folder
     *  @param  string
     *  @param  DirectoryIterator
     *  @return string (pathname on success) | false
     */
    private function classExistsInFolder($className, DirectoryIterator $iterator)
    {
        // Loop over entries
        foreach ($iterator as $entry)
        {
            // Skip dot folder
            if ($entry->isDot()) continue;

            // If it's a directory, enter the directory
            if ($entry->isDir())
            {
                // Check if the file existing in the subdirectory
                if ($pathName = $this->classExistsInFolder($className, new DirectoryIterator($entry->getPathname()))) return $pathName;
            }

            // Is the entry a file?
            if ($entry->isFile())
            {
                // Check if the filename is '<classname>.php'
                if ($entry->getFileName() == $className . '.php')
                {
                    // Include the file, return the path
                    include($pathName = $entry->getPathname());
                    return $pathName;
                }
            }
        }

        // We did not find the required file.
        return false;
    }
}
