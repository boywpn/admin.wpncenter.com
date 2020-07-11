<?php

return [
    'name' => 'Job/Jobs',

    'entity_private_access' => true,

    'advanced_views' => true,

    'job_picture_path' => 'storage/files/jobs_images/',
    'public_job_picture_path' => 'files/jobs_images/',

    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'job.jobs.settings',
        'job.jobs.browse',
        'job.jobs.create',
        'job.jobs.update',
        'job.jobs.destroy'
    ]
    
];
