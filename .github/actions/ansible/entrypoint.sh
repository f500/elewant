#!/bin/sh

set -e

# Write the password file
echo "$VAULT_PASS" > ~/.vault_pass.txt
mkdir ~/.ssh
ansible-vault view --vault-password-file ~/.vault_pass.txt ansible/deploy/deploy.secret > ~/.ssh/id_rsa
chmod 0600 ~/.ssh/id_rsa

ansible-playbook ansible/deploy/playbook.yml \
    -i ansible/hosts-deploy \
    --vault-password-file ~/.vault_pass.txt \
    --key-file ~/.ssh/id_rsa \
    ${INPUT_EXTRAVARS} \
    ${INPUT_VERBOSITY}
