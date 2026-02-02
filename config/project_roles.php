<?php
return [
    // Proje içi roller
    'roles' => ['project_owner', 'project_manager', 'team_lead', 'contributor', 'observer'],

    // Proje içi yetkiler (abilities)
    'abilities' => [
        'project.view'   => ['project_owner', 'project_manager', 'team_lead', 'contributor', 'observer'],

        'project.update' => ['project_owner', 'project_manager', 'team_lead'],


        'task.view'      => ['project_owner', 'project_manager', 'team_lead', 'contributor'],

        'task.create'    => ['project_owner', 'project_manager'],

        'task.delete'    => ['project_owner', 'project_manager'],

        'task.update'    => ['project_owner', 'project_manager'],
    ],
];