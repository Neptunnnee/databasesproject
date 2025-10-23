INSERT INTO Employees (emp_id, name) VALUES
  (101, 'Mohammed'),
  (102, 'Ryan'),
  (103, 'Rashid'),
  (104, 'Diego'),
  (105, 'Selin'),
  (106, 'Farid');

INSERT INTO Business_Manager (emp_id) VALUES (101), (102);
INSERT INTO Tech_Manager     (emp_id) VALUES (103), (104);
INSERT INTO Marketing_Manager(emp_id) VALUES (105), (106);

INSERT INTO Product (product_id, brand, price) VALUES
  (1, 'Eclipse airdots', 80.00),
  (2, 'Ewatch band medium', 90.00),
  (3, 'Nebula cable organiser', 120.00),
  (4, 'Ewatch band large ', 60.00),
  (5, 'Eclipse airdots pro', 45.00),
  (6, 'Nebula cable organiser', 75.00),
  (7, 'Ewatch band small', 80.00),
  (8, 'Ewatch airdots pro max', 55.00),
  (9, 'Lumen cable organiser', 40.00);

INSERT INTO Earphone_case (product_id) VALUES (1), (5), (8);
INSERT INTO Smartwatch_bands (product_id, length) VALUES
  (2, 42),
  (4, 44),
  (7, 40);
INSERT INTO Cable_organizers (product_id) VALUES (3), (6), (9);

INSERT INTO Manages (emp_id, product_id, since_date) VALUES
  (101, 1, '2024-09-01'),
  (101, 3, '2024-10-01'),
  (102, 5, '2024-09-15'),
  (102, 6, '2024-10-10'),
  (102, 7, '2024-10-12');

INSERT INTO Sources (manager_id, product_id) VALUES
  (103, 2),
  (103, 4),
  (104, 7);

INSERT INTO Promotes (manager_id, product_id, campaign_name) VALUES
  (105, 2, 'instagram'),
  (105, 2, 'tiktok'),
  (106, 2, 'facebook'),
  (105, 5, 'Backtoschool'),
  (106, 5, 'black friday'),
  (106, 5, 'Studentdiscount'),
  (105, 3, 'Organizer-pro'),
  (106, 6, 'Nebula-pro'),
  (105, 6, 'Lumen-bundle'),
  (105, 7, 'Band-fit');

INSERT INTO Payment (payment_id, amount) VALUES
  (201, 29.99),
  (202, 49.99),
  (203, 15.00),
  (204, 120.00),
  (205, 75.00),
  (206, 60.00),
  (207, 90.00);

INSERT INTO Card_Payment (payment_id) VALUES (201), (202), (205);
INSERT INTO Giftcard_Payment (payment_id) VALUES (203), (206);