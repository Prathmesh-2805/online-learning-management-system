-- Insert new courses from GeeksForGeeks
INSERT INTO courses (title, description, link) VALUES
('Data Structures and Algorithms', 'Master fundamental data structures and algorithms with practical implementations', 'https://www.geeksforgeeks.org/courses/dsa-self-paced'),

('Java Programming', 'Comprehensive Java course from basics to advanced concepts', 'https://www.geeksforgeeks.org/courses/complete-java-developer'),

('Machine Learning', 'Learn machine learning concepts, algorithms, and practical applications', 'https://www.geeksforgeeks.org/courses/machine-learning-live'),

('Full Stack Web Development', 'Complete guide to becoming a full stack web developer', 'https://www.geeksforgeeks.org/courses/full-stack-node'),

('C++ Programming', 'Master C++ programming with hands-on projects', 'https://www.geeksforgeeks.org/courses/c-plus-plus'),

('Operating Systems', 'Understanding operating system concepts and implementation', 'https://www.geeksforgeeks.org/courses/operating-systems'),

('React.js Development', 'Learn modern React.js development and best practices', 'https://www.geeksforgeeks.org/courses/react-js'),

('Cloud Computing with AWS', 'Master cloud computing concepts using Amazon Web Services', 'https://www.geeksforgeeks.org/courses/aws'),

('Cyber Security', 'Learn essential cybersecurity concepts and practices', 'https://www.geeksforgeeks.org/courses/cyber-security-live'),

('System Design', 'Master the art of designing scalable systems', 'https://www.geeksforgeeks.org/courses/system-design-live'),

('JavaScript Advanced', 'Advanced JavaScript concepts and modern features', 'https://www.geeksforgeeks.org/courses/javascript-advanced'),

('Data Science', 'Comprehensive course on data science and analytics', 'https://www.geeksforgeeks.org/courses/data-science-live'),

('Angular Development', 'Learn Angular framework for web applications', 'https://www.geeksforgeeks.org/courses/angular-complete'),

('DevOps Engineering', 'Master DevOps tools and practices', 'https://www.geeksforgeeks.org/courses/devops-live'),

('Android App Development', 'Create Android applications using Java and Android Studio', 'https://www.geeksforgeeks.org/courses/android-development-kotlin');

-- Update course descriptions with more detailed information
UPDATE courses SET description = CASE title
    WHEN 'Data Structures and Algorithms' THEN 'Learn essential DSA concepts including arrays, linked lists, trees, graphs, sorting algorithms, and problem-solving techniques. Perfect for coding interviews.'
    WHEN 'Java Programming' THEN 'Master Java programming from basics to advanced topics including OOP, collections, multithreading, and Spring framework. Includes real-world projects.'
    WHEN 'Machine Learning' THEN 'Explore ML algorithms, data preprocessing, model training, and deployment. Covers supervised, unsupervised learning, and deep learning basics.'
    WHEN 'Full Stack Web Development' THEN 'Build complete web applications using modern technologies. Covers HTML, CSS, JavaScript, Node.js, Express, and MongoDB.'
    WHEN 'C++ Programming' THEN 'Comprehensive C++ course covering OOP, STL, memory management, and modern C++ features. Includes practical coding exercises.'
    WHEN 'Operating Systems' THEN 'Understanding OS concepts including process management, memory management, file systems, and synchronization.'
    WHEN 'React.js Development' THEN 'Build modern user interfaces with React.js. Learn hooks, state management, routing, and best practices.'
    WHEN 'Cloud Computing with AWS' THEN 'Master AWS services including EC2, S3, Lambda, and more. Learn cloud architecture and deployment.'
    WHEN 'Cyber Security' THEN 'Learn network security, cryptography, ethical hacking, and security best practices. Includes hands-on labs.'
    WHEN 'System Design' THEN 'Learn to design large-scale distributed systems. Covers scalability, reliability, and performance optimization.'
    WHEN 'JavaScript Advanced' THEN 'Advanced JS concepts including closures, promises, async/await, and modern ES6+ features.'
    WHEN 'Data Science' THEN 'Comprehensive data science course covering Python, pandas, NumPy, and machine learning algorithms.'
    WHEN 'Angular Development' THEN 'Master Angular framework, TypeScript, RxJS, and state management. Build real-world applications.'
    WHEN 'DevOps Engineering' THEN 'Learn Docker, Kubernetes, Jenkins, and CI/CD pipelines. Master automation and deployment.'
    WHEN 'Android App Development' THEN 'Create Android apps using Kotlin. Learn UI design, data storage, APIs, and app publishing.'
END
WHERE title IN (
    'Data Structures and Algorithms',
    'Java Programming',
    'Machine Learning',
    'Full Stack Web Development',
    'C++ Programming',
    'Operating Systems',
    'React.js Development',
    'Cloud Computing with AWS',
    'Cyber Security',
    'System Design',
    'JavaScript Advanced',
    'Data Science',
    'Angular Development',
    'DevOps Engineering',
    'Android App Development'
);
