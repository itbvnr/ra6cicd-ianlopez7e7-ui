CREATE TABLE IF NOT EXISTS tasques (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  text       VARCHAR(255)  NOT NULL,
  tag        VARCHAR(50)   NOT NULL DEFAULT 'Nova',
  done       TINYINT(1)    NOT NULL DEFAULT 0,
  created_at TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO tasques (text, tag, done) VALUES
  ('Crear un compte a GitHub',                                           'Prereq', 1),
  ('Instal·lar Visual Studio Code',                                      'Prereq', 1),
  ('Crear el repositori a GitHub i obrir el Codespace',                 'Part 1', 0),
  ('Configurar GitHub Pages i desplegar la versió estàtica',            'Part 1', 0),
  ('Configurar Docker Compose amb el contenidor web i la base de dades','Part 2', 0),
  ('Verificar l\'entorn de desenvolupament al Codespace',               'Part 2', 0),
  ('Crear el workflow de GitHub Actions per al desplegament automàtic', 'Part 3', 0),
  ('Configurar els secrets del repositori per a l\'accés a AWS',        'Part 3', 0),
  ('Verificar el desplegament automàtic a l\'EC2 d\'AWS',               'Part 3', 0),
  ('Fer un canvi a l\'aplicació i comprovar el cicle CI/CD complet',    'Part 4', 0);
