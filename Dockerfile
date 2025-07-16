FROM jenkins/jenkins:lts

USER root

# Installer PHP, Composer et autres outils nécessaires
RUN apt-get update && \
    apt-get install -y php-cli unzip curl git && \
    curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    composer --version && \
    php -v

USER jenkins
