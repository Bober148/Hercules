-- Добавляет дату, время и место к записям расписания.
-- Запусти один раз в phpMyAdmin (база hercules) или: mysql hercules < migrations/001_schedule_datetime_location.sql
ALTER TABLE `schedule`
  ADD COLUMN `SessionDate` DATE        NULL,
  ADD COLUMN `SessionTime` TIME        NULL,
  ADD COLUMN `Location`    VARCHAR(100) NULL;
