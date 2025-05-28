# Processo seletivo - Desenvolvedor Full-Stack Júnior (PHP - Moodle)
Bem vindo, candidat@.

Estamos felizes que você esteja participando do processo seletivo para a vaga de Desenvolvedor Full-Stack Júnior (PHP - Moodle) do Senai - Soluções Digitais.

A prova será aplicada para auxiliar no desenvolvimento e manutenção ao nosso ambiente EAD https://ava.sesisenai.org.br/

A prova deverá utilizar as seguintes tecnologias:

- LMS Moodle
- Linguagem de programação PHP
- GIT para versionamento da aplicação.


Será necessário roda a aplicação na sua máquina, você pode baixar através do link oficial ou da aplicação docker
- https://download.moodle.org/
- https://hub.docker.com/r/bitnami/moodle/

# O que será cobrado na prova? #

- Será necessario a criação/instalação de um novo recurso de avisos.
  -  Ao instalar deverá criar uma tabela no banco de dados para armazenar os avisos;
  -  Criar telas para CRUD dos avisos que deve ser acessado pelo menu Configurações do curso;
    - Gerenciar acesso ao recurso atraves das permissões;
    - O envio do aviso deverá ser feito via CRON, em datas definidas nas configurações da notificação;

Exemplo: 
![image](https://github.com/user-attachments/assets/ec78d406-548e-470b-bacd-170951e3c4d0)
https://drive.google.com/file/d/1Lr4-7t8G0-TGR8cgKNYMI0rGQ5GLxH_Y/preview


# Atenção 
- Envie todo projeto para o github
- Na raiz do projeto anexe o novo recurso desenvolvido em um arquivo zip separado.


### Na etapa da entrevista deverá ser apresentado a aplicação em funcionamento.

- Instruções para a execução da prova será enviada por e-mail no horário definido em edital.
- Todo código desenvolvido deverá ser commitado neste repositório, não sendo aceito o envio da prova por outros meios.

(Escreva aqui as instruções para que possamos corrigir sua prova, bem como qualquer outra observação sobre a prova que achar pertinente compartilhar)__

### Será avaliado
- Facilidade no entendimento do código.
- Complexidade ciclomática do código.
- Organização do projeto;
- Desenvolvimento e funcionamento dos requisitos propostos.
- Criatividade e inovação na solução proposta.
- Usabilidade do usuário.
- Estilização do conteudo.

### Informações extras
- Descreva ao final deste documento (Readme.md) o detalhamento de funcionalidades implementadas, sejam elas já descritas na modelagem e / ou extras.
- Detalhar também as funcionalidades que não conseguiu implementar e o motivo.

---

## Implementação Realizada

O plugin de avisos foi desenvolvido seguindo os requisitos solicitados e as melhores práticas do Moodle. A implementação inclui:

### 1. Estrutura do Plugin
   - **Localização**: `/local/avisos/`
   - **Arquivos principais**:
     - `add.php`: Criação de novos avisos
     - `edit.php`: Edição de avisos existentes
     - `delete.php`: Remoção de avisos
     - `index.php`: Listagem e gerenciamento
     - `lib.php`: Funções principais e hooks
     - `version.php`: Informações de versão

### 2. Estrutura de Diretórios
```
local/avisos/
├── classes/
│   ├── form/
│   │   └── aviso_form.php          # Formulário principal
│   └── task/
│       └── send_avisos.php         # Tarefa CRON
├── cli/
│   └── test_task.php               # Script de teste do CRON
├── db/
│   ├── access.php                  # Definições de permissões
│   ├── install.xml                 # Estrutura do banco de dados
│   ├── message.php                 # Configuração de mensagens
│   └── tasks.php                   # Registro de tarefas CRON
├── lang/
│   ├── en/
│   │   └── local_avisos.php        # Strings em inglês
│   └── pt_br/
│       └── local_avisos.php        # Strings em português
├── add.php                         # Interface de criação
├── delete.php                      # Interface de exclusão
├── edit.php                        # Interface de edição
├── index.php                       # Interface principal
├── lib.php                         # Funções e hooks
└── version.php                     # Informações do plugin
```

### 3. Funcionalidades Implementadas

#### ✅ CRUD Completo de Avisos
- **Criar**: Interface para criação de novos avisos com título, conteúdo e data de envio
- **Listar**: Visualização de todos os avisos do curso com status (pendente/enviado)
- **Editar**: Modificação de avisos existentes (apenas se ainda não foram enviados)
- **Excluir**: Remoção de avisos com confirmação

#### ✅ Sistema de Agendamento via CRON
- **Tarefa automática**: `\local_avisos\task\send_avisos`
- **Frequência**: Execução a cada 5 minutos
- **Processamento**: Verifica avisos pendentes e envia automaticamente
- **Status**: Atualiza status de `pendente` para `enviado` após envio

#### ✅ Controle de Permissões
- **`local/avisos:manage`**: Criar, editar e excluir avisos
- **`local/avisos:view`**: Visualizar avisos
- **Integração**: Sistema de permissões nativo do Moodle
- **Contexto**: Permissões por curso

#### ✅ Interface Responsiva e Intuitiva
- **Design**: Interface consistente com o tema do Moodle
- **Navegação**: Breadcrumbs e links contextuais
- **Feedback**: Mensagens de sucesso, erro e confirmação
- **Usabilidade**: Formulários validados e intuitivos

#### ✅ Internacionalização
- **Idiomas**: Suporte completo para português e inglês
- **Strings**: Todas as mensagens externalizadas
- **Padrão**: Seguindo convenções do Moodle

### 4. Banco de Dados

#### Tabela: `local_avisos`
```sql
- id (int): Chave primária
- courseid (int): ID do curso (FK para mdl_course)
- title (varchar): Título do aviso
- message (text): Conteúdo do aviso
- senddate (int): Data/hora agendada para envio (timestamp)
- sent (int): Status do envio (0=pendente, 1=enviado)
- timecreated (int): Data de criação (timestamp)
- timemodified (int): Data de modificação (timestamp)
- createdby (int): ID do usuário criador
```

### 5. Sistema de Notificações
- **Integração**: Sistema de mensagens nativo do Moodle
- **Destinatários**: Todos os usuários matriculados no curso
- **Formato**: Mensagens HTML com título e conteúdo
- **Histórico**: Registro completo de envios

### 6. Testes e Validação

#### Como Testar o Plugin

1. **Instalação**
   ```bash
   # Copiar plugin para o Moodle
   docker cp moodle/local/avisos/ teste_02-moodle-1:/bitnami/moodle/local/
   
   # Reiniciar container
   docker restart teste_02-moodle-1
   ```

2. **Configuração**
   - Acessar como administrador
   - Ir em Administração > Notificações
   - Confirmar instalação do plugin
   - Verificar se CRON está configurado

3. **Uso Básico**
   - Acessar um curso como professor/administrador
   - Localizar "Avisos" no menu de navegação do curso
   - Criar um novo aviso com data futura
   - Verificar na listagem o status "Pendente"
   - Aguardar execução do CRON ou executar manualmente
   - Verificar mudança de status para "Enviado"

4. **Teste Manual do CRON**
   ```bash
   # Executar CRON completo
   docker exec -it teste_02-moodle-1 bash -c "cd /bitnami/moodle && php admin/cli/cron.php"
   
   # Executar apenas a tarefa de avisos
   docker exec -it teste_02-moodle-1 bash -c "cd /bitnami/moodle && php admin/cli/scheduled_task.php --execute='\\local_avisos\\task\\send_avisos' --force"
   ```

### 7. Decisões Técnicas

#### Arquitetura
- **Padrão MVC**: Separação clara entre modelo, visão e controle
- **Classes Moodle**: Uso extensivo de `moodleform`, `moodle_url`, etc.
- **Hooks**: Integração via hooks nativos do Moodle
- **Segurança**: Validação de entrada e controle de acesso

#### Performance
- **Índices**: Índice na coluna `senddate` para otimizar consultas do CRON
- **Paginação**: Preparado para grandes volumes de dados
- **Cache**: Uso do sistema de cache do Moodle quando aplicável

#### Manutenibilidade
- **Documentação**: Código amplamente documentado
- **Padrões**: Seguindo coding standards do Moodle
- **Modularidade**: Código organizado em classes e funções específicas

### 8. Observações Importantes

#### Limitações Conhecidas
- **Edição**: Avisos já enviados não podem ser editados (por design)
- **Exclusão**: Avisos enviados podem ser excluídos (mantém histórico no sistema de mensagens)
- **Anexos**: Não implementado suporte a anexos (pode ser adicionado futuramente)

#### Melhorias Futuras Sugeridas
- **Templates**: Sistema de templates para avisos recorrentes
- **Variáveis**: Substituição automática de variáveis (nome do aluno, curso, etc.)
- **Relatórios**: Dashboard com estatísticas de envio
- **Filtros**: Envio seletivo para grupos específicos
- **Anexos**: Suporte a arquivos anexos

#### Compatibilidade
- **Versão**: Testado em Moodle 4.1+
- **PHP**: Compatível com PHP 7.4+
- **Banco**: Suporte a MySQL/MariaDB e PostgreSQL

---

## Configuração do Moodle com Docker

Este repositório contém a configuração Docker para rodar o Moodle usando a imagem bitnami/moodle.

### Requisitos

- Docker
- Docker Compose

### Como usar

1. Clone este repositório
2. Execute o comando para iniciar os contêineres:

```bash
docker compose up -d
```

3. Aguarde alguns minutos até que o Moodle seja instalado e configurado (pode levar até 10 minutos na primeira execução)
4. Acesse o Moodle no navegador em:
   - http://localhost:8080

### Problemas comuns

Se encontrar problemas na conexão com o banco de dados, experimente estas soluções:

1. Verifique se ambos os contêineres estão em execução:
```bash
docker ps
```

2. Reinicie os contêineres:
```bash
docker compose down
docker compose up -d
```

3. Verifique os logs dos contêineres:
```bash
docker logs mariadb
docker logs moodle
```

### Solução alternativa usando `docker run`

Se ainda tiver problemas com o Docker Compose, tente executar os contêineres diretamente:

```bash
# Crie uma rede para os contêineres
docker network create moodle-network

# Execute o MariaDB
docker run -d --name mariadb \
  --network moodle-network \
  -e ALLOW_EMPTY_PASSWORD=no \
  -e MARIADB_USER=bn_moodle \
  -e MARIADB_PASSWORD=bitnami123 \
  -e MARIADB_DATABASE=bitnami_moodle \
  -v mariadb_data:/bitnami/mariadb \
  bitnami/mariadb:10.6

# Execute o Moodle
docker run -d --name moodle \
  --network moodle-network \
  -p 8080:8080 -p 8443:8443 \
  -e MOODLE_DATABASE_HOST=mariadb \
  -e MOODLE_DATABASE_PORT_NUMBER=3306 \
  -e MOODLE_DATABASE_USER=bn_moodle \
  -e MOODLE_DATABASE_NAME=bitnami_moodle \
  -e MOODLE_DATABASE_PASSWORD=bitnami123 \
  -e ALLOW_EMPTY_PASSWORD=no \
  -v moodle_data:/bitnami/moodle \
  -v moodledata_data:/bitnami/moodledata \
  bitnami/moodle:4.1
```

### Credenciais padrão

- **Usuário**: user
- **Senha**: bitnami123 -> Admin#2024

### Volumes

A configuração usa volumes Docker para persistir os dados:
- `mariadb_data`: Armazena os dados do banco de dados
- `moodle_data`: Armazena os arquivos do Moodle
- `moodledata_data`: Armazena os dados e uploads do Moodle

### Parar os contêineres

Para parar os contêineres, execute:

```bash
docker compose down
```

---

