# web_backup

Small tool to backup different folders (web projects) on a web server and provide them to download.

**Important:** This tool doesn't provide any user management or other protection for unauthorized access. The backups contain also the configuration files that can contain access tokens end/or passwords for other services (APIs, mail account etc.). Please protect access to the tool and the backups by using the web server mechanisms (like .htaccess on apache) and delete the backup files as soon as you have downloaded them.