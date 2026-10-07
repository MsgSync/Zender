-- Development administrator (mirrors what the web installer creates).
-- Email: admin@zender.test   Password: password
--
-- Apply to a database seeded from install.sql:
--   mysql zender_test < tools/dev-admin.sql
INSERT INTO users (role, email, password, name, language, suspended)
SELECT 1, 'admin@zender.test', '$2y$12$UKyZRVScBO6XwR0cZGTrFOieyqQF.Tc7YP8PtnHdWyNTBJExSMgmi', 'Administrator', 1, 0
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'admin@zender.test');
