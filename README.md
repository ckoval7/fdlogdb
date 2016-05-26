# fdlogdb
Field Day logging Database Web Application

Please see files/FDLDB_proposal.pdf for an outline of the project

To run and display content, install a web server application such as XAMMP and place all files into xammp/htdocs.

Alternatively you can install Apache, PHP, and MySQL individually.

When you first install it please run /admin/db-config.php to setup the database tables and make an admin account.

After creating the database, goto /admin/setup.php to set the field day site information. 

Currently working:
	User accounts
	non-GOTA contact logging
	Image Upload (no display)
	Guestbook
	
Not working/to-do:
	Inventory
	Table of point values
	List of acceptable sections
	regex to check class
	Image display
	GOTA logging
	FD post processing
	The ability to delete contacts from the GUI
	Create a logo
	Come up with a name
	more to come!
