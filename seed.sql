-- Seed some clubs
INSERT INTO clubs (name, description) VALUES
  ('ULAB Computer Club', 'Tech talks, hackathons, and workshops'),
  ('ULAB Debate Club', 'Debate practice and tournaments'),
  ('ULAB Photography Club', 'Photo walks, exhibitions, and tutorials')
ON DUPLICATE KEY UPDATE description = VALUES(description);
