<?php

namespace App\Providers;

use App\Interfaces\AdminDashboardRepositoryInterface;
use App\Interfaces\AttendanceRepositoryInterface;
use App\Interfaces\BatchRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\RoleRepositoryInterface;
use App\Interfaces\EmployeeRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\DepartmentRepositoryInterface;
use App\Interfaces\EmployeeDashboardRepositoryInterface;
use App\Interfaces\InfoSheetRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\SystemEngineerRepositoryInterface;
use App\Interfaces\UserPermissionRepositoryInterface;
use App\Interfaces\EmployeeOrderRepositoryInterface;
use App\Interfaces\EmployeeSalaryRepositoryInterface;
use App\Interfaces\FeedbackRepositoryInterface;
use App\Interfaces\HolidayRepositoryInterface;
use App\Interfaces\HrDashboardRepositoryInterface;
use App\Interfaces\LeadRepositoryInterface;
use App\Interfaces\LeaveRepositoryInterface;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\SalarySlipRepositoryInterface;
use App\Interfaces\SchemeRepositoryInterface;
use App\Interfaces\StockRepositoryInterface;
use App\Interfaces\TeamRepositoryInterface;
use App\Interfaces\TicketRepositoryInterface;
use App\Interfaces\TransportDepartmentRepositoryInterface;
use App\Interfaces\UserLogRepositoryInterface;
use App\Repositories\AdminDashboardRepository;
use App\Repositories\AttendanceRepository;
use App\Repositories\BatchRepository;
use App\Repositories\OrderRepository;
use App\Repositories\RoleRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\DepartmentRepository;
use App\Repositories\EmployeeDashboardRepository;
use App\Repositories\InfoSheetRepository;
use App\Repositories\ProductRepository;
use App\Repositories\SchemeRepository;
use App\Repositories\SystemEngineerRepository;
use App\Repositories\UserLogRepository;
use App\Repositories\UserPermissionRepository;
use App\Repositories\EmployeeOrderRepository;
use App\Repositories\EmployeeSalaryRepository;
use App\Repositories\FeedbackRepository;
use App\Repositories\HolidayRepository;
use App\Repositories\HrDashboardRepository;
use App\Repositories\LeadRepository;
use App\Repositories\LeaveRepository;
use App\Repositories\SalarySlipRepository;
use App\Repositories\StockRepository;
use App\Repositories\TeamRepository;
use App\Repositories\TicketRepository;
use App\Repositories\TransportDepartmentRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(EmployeeRepositoryInterface::class, EmployeeRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(SystemEngineerRepositoryInterface::class, SystemEngineerRepository::class);
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(UserPermissionRepositoryInterface::class, UserPermissionRepository::class);
        $this->app->bind(InfoSheetRepositoryInterface::class, InfoSheetRepository::class);
        $this->app->bind(UserLogRepositoryInterface::class, UserLogRepository::class);
        $this->app->bind(EmployeeOrderRepositoryInterface::class, EmployeeOrderRepository::class);
        $this->app->bind(EmployeeSalaryRepositoryInterface::class, EmployeeSalaryRepository::class);
        $this->app->bind(SchemeRepositoryInterface::class, SchemeRepository::class);
        $this->app->bind(HolidayRepositoryInterface::class, HolidayRepository::class);
        $this->app->bind(TicketRepositoryInterface::class, TicketRepository::class);
        $this->app->bind(LeaveRepositoryInterface::class, LeaveRepository::class);
        $this->app->bind(LeadRepositoryInterface::class, LeadRepository::class);
        $this->app->bind(HrDashboardRepositoryInterface::class, HrDashboardRepository::class);
        $this->app->bind(AdminDashboardRepositoryInterface::class, AdminDashboardRepository::class);
        $this->app->bind(AttendanceRepositoryInterface::class, AttendanceRepository::class);
        $this->app->bind(SalarySlipRepositoryInterface::class, SalarySlipRepository::class);
        $this->app->bind(EmployeeDashboardRepositoryInterface::class, EmployeeDashboardRepository::class);
        $this->app->bind(TransportDepartmentRepositoryInterface::class, TransportDepartmentRepository::class);
        $this->app->bind(BatchRepositoryInterface::class, BatchRepository::class);
        $this->app->bind(StockRepositoryInterface::class, StockRepository::class);
        $this->app->bind(FeedbackRepositoryInterface::class, FeedbackRepository::class);
        $this->app->bind(TeamRepositoryInterface::class, TeamRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
