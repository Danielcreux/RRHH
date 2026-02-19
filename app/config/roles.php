<?php
return [
  'super_admin' => [
    'label' => 'Super Admin',
    'permisos' => ['*'],
  ],
  'rrhh' => [
    'label' => 'RRHH',
    'permisos' => [
      'dashboard',
      'empleados',
      'asistencia',
      'nominas',
      'solicitudes',
      'talentia',
    ],
  ],
  'empleado' => [
    'label' => 'Empleado',
    'permisos' => [
      'dashboard',
      'mi_perfil',
      'mis_fichajes',
      'mis_nominas',
      'mis_solicitudes',
      'talentia',
    ],
  ],
];
