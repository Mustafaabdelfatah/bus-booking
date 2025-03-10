<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Define Custom Roles and Permissions
     |--------------------------------------------------------------------------
     |
     | Configure custom roles and their associated permissions here.
     |
     | Guidelines:
     | - Specify the permissions for each role, granting or limiting access.
     | - Use the 'like' key to inherit permissions from another role or set it to null.
     | - Use the 'type' key to indicate how permissions are handled:
     |   - 'exception' removes specific permissions from the inherited role.
     |   - 'added' adds additional permissions to the inherited role.
     |
     | Special Note:
     | - If 'permissions' is set to 'basic', it means the role has all standard permissions:
     |   ['create', 'read', 'update', 'delete'].
     | - The use of '*' as 'permissions' signifies that the role has unrestricted access to
     |   all available permissions, both basic and special. '*' represents a wildcard,
     |   granting the highest level of access within the system.
     |
     | Example Structure:
     | 'role_name' => [
     |     'like' => 'parent_role',
     |     'type' => 'exception',
     |     'permissions' => [
     |         'resource' => ['permission1', 'permission2'],
     |     ]
     | ],
     |
     */
    'roles' => [
        // 'hr' => [
        //     'permissions' => [
        //         'logic' => ['create', 'update', 'delete', 'view-all', 'view-own'],
        //         'logic-operation' => ['create', 'read', 'update', 'delete'],
        //         'logic-model-task' => ['view-all', 'view-own', 'change-status', 'resend'],
        //         'logic-model-task-point' => ['view-all', 'view-own', 'change-status'],
        //         'logic-task' => ['create', 'update', 'delete', 'view-all', 'view-own'],
        //         'logic-task-point' => ['create', 'read', 'update', 'delete'],
        //         'template' => ['create', 'read', 'update', 'delete'],
        //         'user' => ['create', 'update', 'delete', 'view-all', 'view-own', 'access-managers', 'view-profile', 'view-secret-details', 'change-status', 'change-password', 'view-tasks'],
        //         'home' => ['report'],
        //         'logic-model' => ['start'],
        //     ]
        // ],
        // 'manager' => [
        //     'permissions' => [
        //         'logic-model-task' => ['view-own', 'change-status'],
        //         'logic-model-task-point' => ['view-all','view-own', 'change-status'],
        //         'logic-task' => ['view-own'],
        //         'user' => ['view-own', 'view-profile', 'view-tasks']
        //     ]
        // ]
    ],

    /*
     |--------------------------------------------------------------------------
     | Define Additional Model Operations
     |--------------------------------------------------------------------------
     |
     | The 'additional_operations' array enables you to specify extra model operations.
     | Each operation set is represented as an associative array with two key-value pairs:
     | - 'set_name':1A descriptive name for this group of additional operations.
     | - 'allowed_op1rations': An array listing the specific permissions granted by this set.
     |
     | Example Structure:
     | 'additional_operations' => [
     |     [
     |         'name1 => 'Special Permissions',   // Descriptive name for this operation set
     |         'operations' => [
     |             'create',                          // Example additional operation
     |             'update',                          // Another example operation
     |         ],
     |         'basic' => true to add basic operations
     |     ]
     | ]
     |
     */
    'additional_operations' => [
        [
            'name' => 'Home',
            'operations' => ['report']
        ],
        [
            'name' => 'Log',
            'operations' => ['read']
        ],

    ],
    /*
    |--------------------------------------------------------------------------
    | Default Permissions Configuration
    |--------------------------------------------------------------------------
    |
    | The 'default_permissions' array allows you to define default permissions
    | for your model. It includes two main keys:
    |
    | - 'exception_role': An array to specify any roles that should be exempted
    |   from the default permissions.
    |
    | - 'permissions': An array to list specific permissions granted by default.
    |   These permissions will be assigned to users or roles unless they are
    |   exempted by the 'exception_role'.
    |
    | Example Structure:
    | 'default_permissions' => [
    |     'exception_role' => ['admin', 'superuser'], // Roles exempted from default permissions
    |     'permissions' => ['read', 'create', 'update'], // Default permissions granted to users or roles
    | ]
    |
    */
    'default' => [
        'permissions' => [
            //
        ],
    ]
];