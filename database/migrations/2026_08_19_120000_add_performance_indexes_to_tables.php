<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sablons', function (Blueprint $table) {
            $table->index(['status', 'date_sablon'], 'idx_sablons_status_date');
            $table->index(['supplier_id', 'date_sablon'], 'idx_sablons_supplier_date');
            $table->index('date_sablon', 'idx_sablons_date_sablon');
            $table->index(['deleted_at', 'status'], 'idx_sablons_deleted_status');
        });

        Schema::table('sablon_employee_details', function (Blueprint $table) {
            $table->index('salary_employee_id', 'idx_sed_salary_emp_id');
            $table->index(['salary_employee_id', 'is_settled', 'is_paid', 'employee_id'], 'idx_sed_eligible');
            $table->index(['settlement_of_id', 'settled_at'], 'idx_sed_settlement');
            $table->index(['late_eligible_at', 'settlement_of_id'], 'idx_sed_late_eligible');
            $table->index(['employee_id', 'is_bon', 'is_settled'], 'idx_sed_employee_bon');
        });

        Schema::table('salary_employees', function (Blueprint $table) {
            $table->index(['date', 'employee_id'], 'idx_sal_emp_date_emp');
            $table->index(['status', 'date'], 'idx_sal_emp_status_date');
        });

        Schema::table('fabrics', function (Blueprint $table) {
            $table->index('date_coming', 'idx_fabrics_date_coming');
            $table->index(['supplier_id', 'code'], 'idx_fabrics_supplier_code');
        });

        Schema::table('fabric_details', function (Blueprint $table) {
            $table->index(['fabric_id', 'color_fabric_id'], 'idx_fd_fabric_color');
        });

        Schema::table('sablon_details', function (Blueprint $table) {
            $table->index(['fabric_detail_id', 'sablon_id'], 'idx_sd_fabric_detail');
            $table->index(['sablon_id', 'color_fabric_id'], 'idx_sd_sablon_color');
        });

        Schema::table('bill_suppliers', function (Blueprint $table) {
            $table->index(['is_paid', 'date_bill'], 'idx_bill_supp_paid_date');
            $table->index(['supplier_id', 'date_bill'], 'idx_bill_supp_supplier_date');
        });

        Schema::table('presences', function (Blueprint $table) {
            $table->index(['employee_id', 'week_of'], 'idx_presences_emp_week');
        });

        Schema::table('memos', function (Blueprint $table) {
            $table->index(['employee_id', 'salary_employee_id'], 'idx_memos_emp_salary');
        });

        Schema::table('price_suppliers', function (Blueprint $table) {
            $table->index(['supplier_id', 'type_fabric_id', 'type_color_id'], 'idx_ps_supplier_fabric_color');
        });

        Schema::table('price_employees', function (Blueprint $table) {
            $table->index(['type_fabric_id', 'type_color_id'], 'idx_pe_fabric_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('price_employees', function (Blueprint $table) {
            $table->dropIndex('idx_pe_fabric_color');
        });

        Schema::table('price_suppliers', function (Blueprint $table) {
            $table->dropIndex('idx_ps_supplier_fabric_color');
        });

        Schema::table('memos', function (Blueprint $table) {
            $table->dropIndex('idx_memos_emp_salary');
        });

        Schema::table('presences', function (Blueprint $table) {
            $table->dropIndex('idx_presences_emp_week');
        });

        Schema::table('bill_suppliers', function (Blueprint $table) {
            $table->dropIndex('idx_bill_supp_paid_date');
            $table->dropIndex('idx_bill_supp_supplier_date');
        });

        Schema::table('sablon_details', function (Blueprint $table) {
            $table->dropIndex('idx_sd_fabric_detail');
            $table->dropIndex('idx_sd_sablon_color');
        });

        Schema::table('fabric_details', function (Blueprint $table) {
            $table->dropIndex('idx_fd_fabric_color');
        });

        Schema::table('fabrics', function (Blueprint $table) {
            $table->dropIndex('idx_fabrics_date_coming');
            $table->dropIndex('idx_fabrics_supplier_code');
        });

        Schema::table('salary_employees', function (Blueprint $table) {
            $table->dropIndex('idx_sal_emp_date_emp');
            $table->dropIndex('idx_sal_emp_status_date');
        });

        Schema::table('sablon_employee_details', function (Blueprint $table) {
            $table->dropIndex('idx_sed_salary_emp_id');
            $table->dropIndex('idx_sed_eligible');
            $table->dropIndex('idx_sed_settlement');
            $table->dropIndex('idx_sed_late_eligible');
            $table->dropIndex('idx_sed_employee_bon');
        });

        Schema::table('sablons', function (Blueprint $table) {
            $table->dropIndex('idx_sablons_status_date');
            $table->dropIndex('idx_sablons_supplier_date');
            $table->dropIndex('idx_sablons_date_sablon');
            $table->dropIndex('idx_sablons_deleted_status');
        });
    }
};
