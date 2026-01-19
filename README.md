# Admin and Teacher dashboards
Both dashboards functions. If the admin makes changes, those changes are reflected on the teacher's dashboard. The 4 cards at the top are dynamically loaded from the db. 

# Users
I took out the dropdown filters not because i was lazy to code them ofc but bc the table arrows basically filter the table already. 

### i updated the Database.php i had based on omar's.
what Database.php does:
1.	Connects to MySQL without assuming the database exists.
2.	Creates the sms database if missing.
3.	Switches to that database.
4.	Creates the users table if it doesn’t exist.
5.	Checks for a superuser (role_id = 2), and inserts one if none exist.

- also i finally got the reset password feature working. Try it out. 
- i added sessions at the top of some of the view files.
- The admin functions like adding user and editing doesn't show from the teacher's perspective. 