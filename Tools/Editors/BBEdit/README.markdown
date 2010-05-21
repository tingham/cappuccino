BBEdit Clippings for Cappuccino
--

Author: Thomas Ingham <tingham @ coalmarch.com> Regular Expression help from Josh Stutts
Created: May 20th, 2010
License: Inherited from Cappuccino

The clippings in this package are for BBEdit, see the BBEdit documentation for implementing the resulting build files.

To build your dictionary; copy any templates that you commonly use already with Cappuccino into the Clippings folder herein, they will be included during the parsing of source files so you can update incrementally without getting set back.

  cd ~/path/to/cappuccino
  php -e Tools/Editors/BBEdit/gendict.php
  ..wait..
  cp -R Tools/Editors/BBEdit/Objective-J.j ~/Library/Application\ Support/BBEdit/Clippings/
  ..done..

Please feel free to add/remove code from this generator as you see fit. I had fun making it and its already saved me a ton of time.

