<?php
// outputs e.g.  somefile.txt was last modified: December 29 2002 22:16:23.

$filename = 'C:\Users\Anil M\Desktop\Learning+PHP+Design+Patterns+-+William+Sanders+(2013).pdf';
if (file_exists($filename)) {
    echo "    Create: " . date ("F d Y H:i:s.", filectime($filename));
    echo "<br/>   Last modified: " . date ("F d Y H:i:s.", filemtime($filename));
}
?>