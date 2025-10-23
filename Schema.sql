CREATE TABLE Employees (
  emp_id INT PRIMARY KEY,
  name   VARCHAR(100) NOT NULL
);
CREATE TABLE Business_Manager (
  emp_id INT PRIMARY KEY,
  campaign_budget DECIMAL(10,2),
  FOREIGN KEY (emp_id) REFERENCES Employees(emp_id)
);
CREATE TABLE Tech_Manager (
  emp_id INT PRIMARY KEY,
  supply VARCHAR(100),
  FOREIGN KEY (emp_id) REFERENCES Employees(emp_id)
);
CREATE TABLE Marketing_Manager (
  emp_id INT PRIMARY KEY,
  FOREIGN KEY (emp_id) REFERENCES Employees(emp_id)
);

CREATE TABLE Product (
  product_id INT PRIMARY KEY,
  brand      VARCHAR(100),
  price      DECIMAL(8,2)
);
CREATE TABLE Earphone_case (
  product_id INT PRIMARY KEY,
  colour     VARCHAR(50),
  FOREIGN KEY (product_id) REFERENCES Product(product_id)
);
CREATE TABLE Smartwatch_bands (
  product_id INT PRIMARY KEY,
  length     VARCHAR(50),
  FOREIGN KEY (product_id) REFERENCES Product(product_id)
);
CREATE TABLE Cable_organizers (
  product_id INT PRIMARY KEY,
  width      VARCHAR(50),
  FOREIGN KEY (product_id) REFERENCES Product(product_id)
);

CREATE TABLE Manages (
  emp_id     INT,
  product_id INT,
  since_date DATE,
  PRIMARY KEY (emp_id, product_id),
  FOREIGN KEY (emp_id) REFERENCES Employees(emp_id),
  FOREIGN KEY (product_id) REFERENCES Product(product_id)
);

CREATE TABLE Sources (
  manager_id INT,
  product_id INT,
  PRIMARY KEY (manager_id, product_id),
  FOREIGN KEY (manager_id) REFERENCES Tech_Manager(emp_id),
  FOREIGN KEY (product_id) REFERENCES Product(product_id)
);

CREATE TABLE Promotes (
  manager_id    INT,
  product_id    INT,
  campaign_name VARCHAR(100),
  PRIMARY KEY (manager_id, product_id, campaign_name),
  FOREIGN KEY (manager_id) REFERENCES Marketing_Manager(emp_id),
  FOREIGN KEY (product_id) REFERENCES Product(product_id)
);

CREATE TABLE Payment (
  payment_id INT PRIMARY KEY,
  amount     DECIMAL(10,2) NOT NULL
);
CREATE TABLE Card_Payment (
  payment_id INT PRIMARY KEY,
  FOREIGN KEY (payment_id) REFERENCES Payment(payment_id)
);
CREATE TABLE Giftcard_Payment (
  payment_id INT PRIMARY KEY,
  FOREIGN KEY (payment_id) REFERENCES Payment(payment_id)
);

ALTER TABLE Employees
  MODIFY emp_id INT NOT NULL AUTO_INCREMENT;
ALTER TABLE Product
  MODIFY product_id INT NOT NULL AUTO_INCREMENT;
ALTER TABLE Payment
  MODIFY payment_id INT NOT NULL AUTO_INCREMENT;
ALTER TABLE Smartwatch_bands
  MODIFY length INT;
ALTER TABLE Product
  MODIFY brand VARCHAR(100);