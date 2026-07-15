INSERT INTO courses (code, title, credits, year_level, semester, department_id) VALUES
('BA101', 'Principles of Management',     3, 1, 1, 3),
('BA102', 'Business Mathematics',         3, 1, 1, 3),
('BA103', 'Introduction to Economics',    3, 1, 2, 3),
('BA104', 'Business Communication',       3, 1, 2, 3),
('BA201', 'Marketing Management',         3, 2, 1, 3),
('BA202', 'Financial Accounting',         3, 2, 1, 3),
('BA203', 'Organisational Behaviour',     3, 2, 2, 3),
('BA204', 'Business Statistics',          3, 2, 2, 3),
('BA301', 'Strategic Management',         3, 3, 1, 3),
('BA302', 'Human Resource Management',    3, 3, 1, 3),
('BA303', 'Entrepreneurship',             3, 3, 2, 3),
('BA304', 'Business Law',                 3, 3, 2, 3),
('BA401', 'International Business',       3, 4, 1, 3),
('BA402', 'Corporate Finance',            3, 4, 1, 3),
('BA403', 'Business Research Project I',  3, 4, 2, 3),
('BA404', 'Business Research Project II', 3, 4, 2, 3);

-- Enroll George (STU-2026-005, Year 2) in his Year 2 BA courses
INSERT INTO student_enrollments (student_id, course_id)
SELECT 'STU-2026-005', id FROM courses
WHERE department_id = 3 AND year_level = 2;
