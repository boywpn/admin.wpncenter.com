<?php

return [
    'name' => 'Job2/Jobs',

    'entity_private_access' => true,

    'advanced_views' => true,

    'job_picture_path' => 'storage/files/jobs_images/',
    'public_job_picture_path' => 'files/jobs_images/',

    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'job2.jobs.settings',
        'job2.jobs.browse',
        'job2.jobs.create',
        'job2.jobs.update',
        'job2.jobs.destroy'
    ]
    
];
