Deploying 
=========

(only for those who have access to the production server)

    # Staging
    ansible-playbook -i ansible/hosts-deploy ansible/deploy/playbook.yml --limit=staging --extra="project_version=develop" --ask-vault-pass

    # Production
    ansible-playbook -i ansible/hosts-deploy ansible/deploy/playbook.yml --limit=production --extra="project_version=[VERSION]" --ask-vault-pass
