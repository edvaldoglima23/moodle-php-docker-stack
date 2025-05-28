# Moodle PHP Docker Stack

Ambiente de desenvolvimento completo construído com Docker e PHP, apresentando uma implementação robusta do Moodle 4.1.

<div align="center">

![Moodle Version](https://img.shields.io/badge/Moodle-4.1-blue)
![PHP Version](https://img.shields.io/badge/PHP-7.4+-green)
![MariaDB Version](https://img.shields.io/badge/MariaDB-10.6-yellow)
![Docker](https://img.shields.io/badge/Docker-Compose-blue)

</div>

## 📋 Índice

- [Sobre o Projeto](#-sobre-o-projeto)
- [Tecnologias](#-tecnologias)
- [Arquitetura](#-arquitetura)
- [Começando](#-começando)
- [Configuração](#-configuração)
- [Plugin de Notificações](#-plugin-de-notificações)
- [Contribuindo](#-contribuindo)
- [Licença](#-licença)

## 🚀 Sobre o Projeto

Stack completa para desenvolvimento Moodle, oferecendo um ambiente containerizado com Docker, incluindo:
- Moodle 4.1 como LMS principal
- MariaDB 10.6 para banco de dados
- phpMyAdmin para administração
- Plugin personalizado de notificações

## 💻 Tecnologias

### Core
- **Moodle 4.1**
- **PHP 7.4+**
- **MariaDB 10.6**
- **Docker & Docker Compose**

### Componentes
- **Servidor Web:** Apache
- **Administração BD:** phpMyAdmin
- **Cache:** Redis (opcional)
- **Sistema de Arquivos:** Volumes Docker


## 🎯 Começando

### Pré-requisitos
- Docker
- Docker Compose
- Git

### Instalação

1. **Clone o repositório**
```bash
git clone [url-do-repositorio]
cd moodle-php-docker-stack
```

2. **Inicie os containers**
```bash
docker compose up -d
```

3. **Acesse**
- Moodle: http://localhost:8080
- phpMyAdmin: http://localhost:8081


## ⚙ Configuração

### Variáveis de Ambiente
```yaml
MOODLE_DATABASE_HOST=mariadb
MOODLE_DATABASE_PORT_NUMBER=3306
MARIADB_ROOT_PASSWORD=
```

### Volumes Persistentes
```yaml
volumes:
  - mariadb_data:/bitnami/mariadb
  - moodle_data:/bitnami/moodle
  - moodledata_data:/bitnami/moodledata
```

## 📬 Plugin de Notificações

### Funcionalidades
- CRUD completo de avisos
- Agendamento automático via CRON
- Sistema de permissões granular
- Interface responsiva
- Suporte multilíngue (PT-BR/EN)

### Configuração do CRON
```bash
# Executar CRON manualmente
docker exec -it [container-id] php admin/cli/cron.php

# Verificar logs
docker logs [container-id]
```

## 🔧 Manutenção

### Backup
```bash
# Backup do banco de dados
docker exec mariadb mysqldump -u root -p bitnami_moodle > backup.sql
```

### Logs
```bash
# Visualizar logs do Moodle
docker logs moodle

# Visualizar logs do MariaDB
docker logs mariadb
```

## 🚨 Resolução de Problemas

### Problemas Comuns

1. **Permissões de Arquivos**
   - Execute o script fix-permissions.sh
   - Verifique as permissões dos volumes

2. **Conexão com Banco**
   - Verifique as credenciais
   - Confirme se o container MariaDB está rodando

3. **Cache**
   - Limpe o cache do Moodle via interface
   - Execute o purge_caches.php

## 🤝 Contribuindo

1. Fork o projeto
2. Crie sua Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add: Amazing Feature'`)
4. Push para a Branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença GPL v3. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 📊 Status do Projeto

![Status](https://img.shields.io/badge/Status-Em%20Desenvolvimento-green)

## 📞 Suporte

- Crie uma issue
- Consulte a [documentação do Moodle](https://docs.moodle.org/)
- Verifique a [documentação do Docker](https://docs.docker.com/)

---

<div align="center">

**Desenvolvido com ❤️ para a comunidade Moodle**

</div>
