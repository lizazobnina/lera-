SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS service_applications;
DROP TABLE IF EXISTS client_users;
DROP TABLE IF EXISTS referrals;
DROP TABLE IF EXISTS policies;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS clinics;
DROP TABLE IF EXISTS services;
DROP TABLE IF EXISTS admins;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE client_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(150) NOT NULL,
  phone VARCHAR(50) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  birth_date DATE NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  login VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  name VARCHAR(120) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE clinics (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  address VARCHAR(255) NOT NULL,
  phone VARCHAR(50),
  email VARCHAR(120),
  specialization VARCHAR(150),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE clients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(150) NOT NULL,
  birth_date DATE NOT NULL,
  phone VARCHAR(50) NOT NULL,
  email VARCHAR(120),
  passport VARCHAR(80),
  address VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE policies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  client_id INT NOT NULL,
  policy_number VARCHAR(50) NOT NULL UNIQUE,
  program_name VARCHAR(120) NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  status ENUM('active','expired','draft') DEFAULT 'draft',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE referrals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  client_id INT NOT NULL,
  policy_id INT NOT NULL,
  clinic_id INT NOT NULL,
  doctor_name VARCHAR(150),
  service_name VARCHAR(150) NOT NULL,
  referral_date DATE NOT NULL,
  status ENUM('new','sent','completed','cancelled') DEFAULT 'new',
  comment TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
  FOREIGN KEY (policy_id) REFERENCES policies(id) ON DELETE CASCADE,
  FOREIGN KEY (clinic_id) REFERENCES clinics(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE service_applications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  service_id INT NOT NULL,
  user_id INT NULL,
  full_name VARCHAR(150) NOT NULL,
  phone VARCHAR(50) NOT NULL,
  email VARCHAR(120),
  birth_date DATE NULL,
  comment TEXT,
  status ENUM('new','processing','approved','rejected') DEFAULT 'new',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES client_users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO client_users (full_name, phone, email, password_hash, birth_date) VALUES
('Тестовый Клиент', '+7 900 000-00-00', 'client@mail.ru', '$2y$12$SGzYeWXEaLe5459LSC9NJOaEpclAOgXy7LCdp3qW6.iD.gUBT2gY.', '2000-01-01');

INSERT INTO admins (login, password_hash, name) VALUES
('admin', '$2y$12$SGzYeWXEaLe5459LSC9NJOaEpclAOgXy7LCdp3qW6.iD.gUBT2gY.', 'Администратор');

INSERT INTO services (name, description, price) VALUES
('Базовая ДМС', 'Первичные консультации терапевта, базовая диагностика, оформление полиса и помощь при острых заболеваниях.', 25000.00),
('Расширенная ДМС', 'Приемы профильных специалистов, лабораторные анализы, УЗИ, диагностика и сопровождение клиента.', 45000.00),
('Семейная ДМС', 'Программа для взрослых и детей: терапевт, педиатр, диагностика и обслуживание в клиниках-партнерах.', 72000.00),
('Премиум ДМС', 'Приоритетная запись, ведущие клиники, расширенная диагностика, стоматология и персональное сопровождение.', 120000.00),
('ДМС для детей', 'Педиатрия, плановые осмотры, вакцинация по показаниям, консультации детских специалистов и диагностика.', 38000.00),
('Стоматология ДМС', 'Профилактические осмотры, лечение кариеса, профессиональная гигиена и консультации стоматолога.', 30000.00),
('Экстренная помощь', 'Круглосуточная консультационная поддержка, вызов врача, срочная диагностика и направление в клинику.', 55000.00),
('Корпоративная ДМС', 'Программа для сотрудников организации: медосмотры, консультации, диагностика и индивидуальные условия.', 95000.00),
('Диагностика и анализы', 'Комплекс лабораторных исследований, УЗИ, ЭКГ, консультация терапевта и рекомендации по результатам.', 18000.00);

INSERT INTO clinics (name, address, phone, email, specialization) VALUES
('МедЦентр Плюс', 'Москва, ул. Лесная, 12', '+7 495 111-22-33', 'plus@clinic.ru', 'Терапия, диагностика'),
('Клиника Здоровье', 'Москва, пр-т Мира, 45', '+7 495 222-33-44', 'info@zdorovie.ru', 'Стоматология, хирургия'),
('Семейная медицина', 'Москва, ул. Садовая, 8', '+7 495 333-44-55', 'family@med.ru', 'Педиатрия, терапия');

INSERT INTO clients (full_name, birth_date, phone, email, passport, address) VALUES
('Иванов Иван Иванович', '1990-04-12', '+7 900 123-45-67', 'ivanov@mail.ru', '4512 123456', 'Москва, ул. Новая, 10'),
('Петрова Анна Сергеевна', '1988-09-25', '+7 901 555-44-33', 'petrova@mail.ru', '4513 987654', 'Москва, ул. Зеленая, 7');

INSERT INTO policies (client_id, policy_number, program_name, start_date, end_date, price, status) VALUES
(1, 'DMS-2026-001', 'Расширенная ДМС', '2026-01-01', '2026-12-31', 45000.00, 'active'),
(2, 'DMS-2026-002', 'Семейная ДМС', '2026-02-01', '2027-01-31', 72000.00, 'active');

INSERT INTO referrals (client_id, policy_id, clinic_id, doctor_name, service_name, referral_date, status, comment) VALUES
(1, 1, 1, 'Терапевт', 'Первичная консультация', '2026-06-10', 'sent', 'Запись на утро'),
(2, 2, 3, 'Педиатр', 'Консультация специалиста', '2026-06-12', 'new', 'С собой взять паспорт и полис');

INSERT INTO service_applications (service_id, user_id, full_name, phone, email, birth_date, comment, status) VALUES
(2, 1, 'Смирнова Мария Олеговна', '+7 999 111-22-33', 'smirnova@mail.ru', '1995-03-16', 'Интересует расширенная программа', 'new');
