USE internship_platform;

-- Cleanup
DELETE FROM applications;
DELETE FROM internships;
DELETE FROM student_profiles;
DELETE FROM users;

-- Users
INSERT INTO users (id, name, email, password, role) VALUES 
(1, 'Alex Chen', 'student@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Student'),
(2, 'TechFlow Inc.', 'hr@techflow.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Company'),
(3, 'Admin User', 'admin@easyintern.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin');

-- Student Profile
INSERT INTO student_profiles (user_id, skills, profile_completion) VALUES 
(1, 'PHP, JavaScript, CSS, Bootstrap', 75);

-- Internships
INSERT INTO internships (id, company_id, title, description, requirements, duration, deadline, company_name) VALUES 
(1, 2, 'Junior Web Developer', 'Looking for a PHP enthusiast.', 'PHP, MySQL', '3 Months', '2026-05-10', 'TechFlow Inc.'),
(2, 2, 'UI/UX Designer', 'Expertise in Figma and CSS needed.', 'CSS, Figma', '6 Months', '2026-06-15', 'TechFlow Inc.'),
(3, 2, 'Frontend Intern', 'Experience with React or JS.', 'JavaScript, React', '4 Months', '2026-04-20', 'TechFlow Inc.');

-- Applications
INSERT INTO applications (internship_id, student_id, status) VALUES 
(1, 1, 'Approved'),
(2, 1, 'Pending');
