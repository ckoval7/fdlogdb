# fdlogdb
Field Day logging Database Web Application

Please see files/FDLDB_proposal.pdf for an outline of the project

To run and display content, install a web server application such as XAMMP and place all files into xammp/htdocs.

Alternatively you can install Apache, PHP, and MySQL individually.

Set the following property in the PHP settings file: php.ini
upload_max_filesize=8M

When you first install it please run **http://localhost/admin/db-config.php** to setup the database tables and make an admin account.

After creating the database, goto /admin/setup.php to set the field day site information. 

Currently working:

	- User accounts
	- non-GOTA contact logging
	- Image Upload (no display)
	- Guestbook
	- The ability to delete contacts from the GUI
	- Inventory
	- List of acceptable sections
	- Image display
	- regex to check class
	
Not working/to-do:

	* Table of point values
	* GOTA logging
	* FD post processing
	* Create a logo
	* Come up with a name
	* more to come!
