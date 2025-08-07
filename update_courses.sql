-- Update existing courses with new GeeksForGeeks links
UPDATE courses 
SET link = 'https://www.geeksforgeeks.org/javascript/learn-web-development-basics-with-html-css-and-javascript/'
WHERE title = 'Introduction to Web Development';

UPDATE courses 
SET link = 'https://www.geeksforgeeks.org/courses/master-python-complete-beginner-to-advanced'
WHERE title = 'Python Programming';

UPDATE courses 
SET link = 'https://www.geeksforgeeks.org/dbms/database-design-ultimate-guide/'
WHERE title = 'Database Design';
