<?php
class DashboardController extends Controller {

  
public function index(): void {
    $rol = Auth::rol();

    $stats = [
        'empleados_activos' => (new Employee())->countActive(), 
        'solicitudes_pendientes' => (new LeaveRequest())->countPending(),
        'nominas_borrador' => (new Payroll())->countDraft(),
        'fichajes_incompletos_hoy' => (new Attendance())->countPendingToday(),
    ];

    $this->view('dashboard/index', ['stats' => $stats, 'rol' => $rol]);
  }
}
