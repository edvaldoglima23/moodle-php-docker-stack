#!/bin/bash

# Cria diretórios necessários
mkdir -p /bitnami/moodledata/cache
mkdir -p /bitnami/moodledata/localcache
mkdir -p /bitnami/moodledata/temp
mkdir -p /bitnami/moodledata/sessions
mkdir -p /bitnami/moodledata/lock

# Define o proprietário correto
chown -R daemon:daemon /bitnami/moodledata

# Define permissões recomendadas pelo Moodle (02775)
find /bitnami/moodledata -type d -exec chmod 02775 {} \;
find /bitnami/moodledata -type f -exec chmod 0664 {} \;

# Garante que o diretório raiz do moodledata tenha permissão de escrita
chmod 02775 /bitnami/moodledata

# Limpa o cache do Moodle
php /bitnami/moodle/admin/cli/purge_caches.php

echo "Permissões ajustadas conforme recomendações do Moodle!" 