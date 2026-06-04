-- Init script for MetaCash

-- Empresas
CREATE TABLE IF NOT EXISTS empresas (
  id_empresa SERIAL PRIMARY KEY,
  nome_empresa VARCHAR(255) NOT NULL,
  cnpj VARCHAR(20) UNIQUE
);

-- Usuarios
CREATE TABLE IF NOT EXISTS usuarios (
  id_usuario SERIAL PRIMARY KEY,
  id_empresa INTEGER REFERENCES empresas(id_empresa) ON DELETE CASCADE,
  matricula VARCHAR(50),
  nome_completo VARCHAR(255) NOT NULL,
  cpf VARCHAR(20) UNIQUE,
  email VARCHAR(255) UNIQUE NOT NULL,
  senha VARCHAR(255) NOT NULL,
  nivel_permissao VARCHAR(50) DEFAULT 'Membro'
);

-- Categoria
CREATE TABLE IF NOT EXISTS categoria (
  id_categoria SERIAL PRIMARY KEY,
  id_empresa INTEGER REFERENCES empresas(id_empresa) ON DELETE CASCADE,
  nome_categoria VARCHAR(255) NOT NULL,
  tipo_categoria VARCHAR(50) NOT NULL
);

-- Transacoes
CREATE TABLE IF NOT EXISTS transacoes (
  id_transacao SERIAL PRIMARY KEY,
  id_empresa INTEGER REFERENCES empresas(id_empresa) ON DELETE CASCADE,
  id_usuario INTEGER REFERENCES usuarios(id_usuario) ON DELETE SET NULL,
  id_categoria INTEGER REFERENCES categoria(id_categoria) ON DELETE SET NULL,
  tipo_transacao VARCHAR(20) NOT NULL,
  valor_transacao NUMERIC(12,2) NOT NULL,
  data_transacao DATE NOT NULL DEFAULT CURRENT_DATE,
  descricao_transacao TEXT
);
