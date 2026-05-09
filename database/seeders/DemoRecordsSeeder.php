<?php

namespace Database\Seeders;

use App\Models\AttendanceRecord;
use App\Models\Benefit;
use App\Models\ConstructionProject;
use App\Models\Customer;
use App\Models\Document;
use App\Models\Employee;
use App\Models\Payment;
use App\Models\Payroll;
use App\Models\Property;
use App\Models\Reservation;
use App\Models\TitleTransfer;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoRecordsSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        foreach ([
            'benefits',
            'payrolls',
            'attendance_records',
            'employees',
            'title_transfers',
            'documents',
            'payments',
            'reservations',
            'construction_projects',
            'properties',
            'customers',
        ] as $table) {
            DB::table($table)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = fake();

        $customerIds = [];
        for ($i = 1; $i <= 60; $i++) {
            $customer = Customer::create([
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'middle_name' => $faker->optional()->firstName(),
                'email' => "customer{$i}@midland.local",
                'phone' => $faker->numerify('09#########'),
                'address' => $faker->address(),
                'employment_status' => $faker->randomElement(['employed', 'self_employed', 'contractual', 'retired']),
                'employer_name' => $faker->company(),
                'job_title' => $faker->jobTitle(),
                'monthly_income' => $faker->numberBetween(20000, 120000),
                'status' => $faker->randomElement(['pending', 'for_approval', 'approved', 'rejected']),
                'profile_notes' => $faker->sentence(),
                'date_of_birth' => $faker->date(),
                'contact_person_name' => $faker->name(),
                'contact_person_phone' => $faker->numerify('09#########'),
            ]);
            $customerIds[] = $customer->id;
        }

        $propertyIdsByStatus = [
            'available' => [],
            'reserved' => [],
            'sold' => [],
            'under_construction' => [],
            'turned_over' => [],
        ];

        $propertyStatuses = array_merge(
            array_fill(0, 16, 'available'),
            array_fill(0, 10, 'reserved'),
            array_fill(0, 18, 'sold'),
            array_fill(0, 10, 'under_construction'),
            array_fill(0, 6, 'turned_over')
        );

        for ($i = 1; $i <= 60; $i++) {
            $status = $propertyStatuses[$i - 1];
            $property = Property::create([
                'block_number' => 'B'.str_pad((string) (int) ceil($i / 5), 2, '0', STR_PAD_LEFT),
                'lot_number' => 'L'.str_pad((string) (($i - 1) % 5 + 1), 2, '0', STR_PAD_LEFT).'-'.$i,
                'house_type' => $faker->randomElement(['Townhouse', 'Single Detached', 'Duplex', 'Bungalow']),
                'price' => $faker->numberBetween(1800000, 6200000),
                'lot_size' => $faker->numberBetween(48, 140).' sqm',
                'floor_area' => $faker->numberBetween(36, 120).' sqm',
                'status' => $status,
                'description' => $faker->sentence(),
                'available_at' => Carbon::now()->subDays($faker->numberBetween(0, 365))->toDateString(),
            ]);

            $propertyIdsByStatus[$status][] = $property->id;
        }

        $reservationStatuses = ['active', 'completed', 'cancelled', 'skipped'];
        $reservations = [];
        for ($i = 0; $i < 38; $i++) {
            $status = $faker->randomElement($reservationStatuses);
            $customerId = $faker->randomElement($customerIds);
            $propertyId = $faker->randomElement(array_merge(
                $propertyIdsByStatus['available'],
                $propertyIdsByStatus['reserved'],
                $propertyIdsByStatus['sold'],
                $propertyIdsByStatus['under_construction']
            ));

            $reservedOn = Carbon::now()->subDays($faker->numberBetween(5, 180));
            $reservation = Reservation::create([
                'customer_id' => $customerId,
                'property_id' => $propertyId,
                'reservation_fee' => $faker->numberBetween(5000, 40000),
                'reserved_on' => $reservedOn->toDateString(),
                'expires_at' => (clone $reservedOn)->addDays(30)->toDateString(),
                'status' => $status,
                'notes' => $faker->sentence(),
            ]);
            $reservations[] = $reservation;
        }

        for ($i = 0; $i < 130; $i++) {
            $customerId = $faker->randomElement($customerIds);
            $propertyId = $faker->randomElement(array_merge(...array_values($propertyIdsByStatus)));
            $reservation = $faker->optional(0.55)->randomElement($reservations);

            Payment::create([
                'customer_id' => $customerId,
                'property_id' => $propertyId,
                'reservation_id' => $reservation?->id,
                'payment_type' => $faker->randomElement(['downpayment', 'installment', 'equity', 'reservation_fee', 'other']),
                'amount' => $faker->numberBetween(5000, 350000),
                'payment_date' => Carbon::now()->subDays($faker->numberBetween(0, 210))->toDateString(),
                'payment_method' => $faker->randomElement(['Cash', 'Bank Transfer', 'GCash', 'Check']),
                'notes' => $faker->sentence(),
            ]);
        }

        for ($i = 0; $i < 75; $i++) {
            $status = $faker->randomElement(['pending', 'processing', 'completed']);
            $dueDate = Carbon::now()->addDays($faker->numberBetween(-45, 45));
            Document::create([
                'customer_id' => $faker->randomElement($customerIds),
                'property_id' => $faker->optional(0.8)->randomElement(array_merge(...array_values($propertyIdsByStatus))),
                'document_type' => $faker->randomElement(['contract_to_sell', 'deed_of_absolute_sale', 'bir_related', 'other']),
                'title' => $faker->randomElement(['Buyer Compliance', 'Contract Packet', 'Tax Declaration', 'Transfer Checklist']),
                'status' => $status,
                'due_date' => $dueDate->toDateString(),
                'completed_at' => $status === 'completed' ? $dueDate->copy()->addDays($faker->numberBetween(-5, 12))->toDateString() : null,
                'notes' => $faker->sentence(),
            ]);
        }

        $workerTypes = [
            ['position' => 'Manager', 'employment_type' => 'office', 'salary_type' => 'monthly', 'monthly_salary' => 80000, 'daily_rate' => null],
            ['position' => 'Engineer', 'employment_type' => 'office', 'salary_type' => 'monthly', 'monthly_salary' => 50000, 'daily_rate' => null],
            ['position' => 'Mason', 'employment_type' => 'onsite', 'salary_type' => 'weekly', 'monthly_salary' => null, 'daily_rate' => 600],
            ['position' => 'Painter', 'employment_type' => 'onsite', 'salary_type' => 'weekly', 'monthly_salary' => null, 'daily_rate' => 600],
            ['position' => 'Welder', 'employment_type' => 'onsite', 'salary_type' => 'weekly', 'monthly_salary' => null, 'daily_rate' => 600],
            ['position' => 'Plumber', 'employment_type' => 'onsite', 'salary_type' => 'weekly', 'monthly_salary' => null, 'daily_rate' => 550],
            ['position' => 'Electrical Engineer', 'employment_type' => 'onsite', 'salary_type' => 'weekly', 'monthly_salary' => null, 'daily_rate' => 550],
            ['position' => 'Laborer', 'employment_type' => 'onsite', 'salary_type' => 'weekly', 'monthly_salary' => null, 'daily_rate' => 350],
            ['position' => 'Heavy Truck Operator', 'employment_type' => 'onsite', 'salary_type' => 'weekly', 'monthly_salary' => null, 'daily_rate' => 700],
            ['position' => 'Marketing Agent', 'employment_type' => 'office', 'salary_type' => 'monthly', 'monthly_salary' => 20000, 'daily_rate' => null],
            ['position' => 'Documentation In Charge', 'employment_type' => 'office', 'salary_type' => 'monthly', 'monthly_salary' => 20000, 'daily_rate' => null],
            ['position' => 'Admin Staff', 'employment_type' => 'office', 'salary_type' => 'monthly', 'monthly_salary' => 20000, 'daily_rate' => null],
        ];

        $employeeComposition = [
            'Manager' => 1,
            'Engineer' => 1,
            'Mason' => 4,
            'Painter' => 6,
            'Welder' => 7,
            'Plumber' => 1,
            'Electrical Engineer' => 1,
            'Laborer' => 8,
            'Heavy Truck Operator' => 1,
            'Marketing Agent' => 1,
            'Documentation In Charge' => 1,
            'Admin Staff' => 1,
        ];

        $plannedEmployees = [];
        foreach ($employeeComposition as $position => $count) {
            for ($x = 0; $x < $count; $x++) {
                $plannedEmployees[] = $position;
            }
        }

        $employeeIds = [];
        foreach ($plannedEmployees as $idx => $plannedPosition) {
            $i = $idx + 1;
            $type = collect($workerTypes)->firstWhere('position', $plannedPosition);
            $hireDate = Carbon::now()->subDays($faker->numberBetween(70, 2000));
            $employee = Employee::create([
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'middle_name' => $faker->optional()->firstName(),
                'email' => "employee{$i}@midland.local",
                'phone' => $faker->numerify('09#########'),
                'position' => $type['position'],
                'employment_type' => $type['employment_type'],
                'salary_type' => $type['salary_type'],
                'daily_rate' => $type['daily_rate'],
                'monthly_salary' => $type['monthly_salary'],
                'hire_date' => $hireDate->toDateString(),
                'is_active' => $faker->boolean(92),
                'notes' => $faker->sentence(),
            ]);

            $employeeIds[] = $employee->id;

            for ($daysAgo = 0; $daysAgo < 14; $daysAgo++) {
                $date = Carbon::now()->subDays($daysAgo)->toDateString();
                if ($faker->boolean(82)) {
                    AttendanceRecord::create([
                        'employee_id' => $employee->id,
                        'attendance_date' => $date,
                        'time_in' => '08:00',
                        'time_out' => $faker->randomElement(['16:30', '17:00', '17:30']),
                        'status' => $faker->randomElement(['present', 'present', 'present', 'late', 'absent']),
                        'hours_worked' => $faker->randomFloat(2, 0, 9),
                        'notes' => $faker->optional(0.3)->sentence(),
                    ]);
                }
            }

            $periodEnd = Carbon::now()->next(Carbon::SATURDAY);
            for ($p = 0; $p < 3; $p++) {
                $endDate = $periodEnd->copy()->subWeeks($p);
                $startDate = $type['salary_type'] === 'weekly'
                    ? $endDate->copy()->subDays(6)
                    : $endDate->copy()->startOfMonth();
                $gross = $type['salary_type'] === 'weekly'
                    ? ($type['daily_rate'] ?? 450) * $faker->numberBetween(5, 6)
                    : ($type['monthly_salary'] ?? 20000);
                $deductions = $faker->numberBetween(0, 1800);
                $payrollStatus = $faker->randomElement(['pending', 'approved']);
                $paidAt = $payrollStatus === 'approved' ? $endDate->toDateString() : null;
                Payroll::create([
                    'employee_id' => $employee->id,
                    'period_start_date' => $startDate->toDateString(),
                    'period_end_date' => $endDate->toDateString(),
                    'gross_amount' => $gross,
                    'deductions' => $deductions,
                    'net_amount' => max($gross - $deductions, 0),
                    'payroll_type' => $type['salary_type'],
                    'status' => $payrollStatus,
                    'paid_at' => $paidAt,
                    'approved_at' => $payrollStatus === 'approved' ? $endDate->toDateString() : null,
                    'notes' => $faker->optional(0.2)->sentence(),
                ]);
            }

            $eligibilityDate = $hireDate->copy()->addYears(2);
            if ($eligibilityDate->isPast() && $faker->boolean(74)) {
                Benefit::create([
                    'employee_id' => $employee->id,
                    'benefit_type' => $faker->randomElement(['sss', 'pagibig', 'philhealth']),
                    'membership_number' => strtoupper($faker->bothify('MVH-########')),
                    'start_date' => $eligibilityDate->copy()->addDays($faker->numberBetween(1, 30))->toDateString(),
                    'eligibility_date' => $eligibilityDate->toDateString(),
                    'status' => $faker->randomElement(['active', 'active', 'pending', 'inactive']),
                    'notes' => $faker->optional(0.3)->sentence(),
                ]);
            }
        }

        $projectPropertyIds = array_slice(array_merge(
            $propertyIdsByStatus['under_construction'],
            $propertyIdsByStatus['reserved'],
            $propertyIdsByStatus['available']
        ), 0, 30);

        foreach ($projectPropertyIds as $propertyId) {
            ConstructionProject::create([
                'property_id' => $propertyId,
                'status' => $faker->randomElement(['not_started', 'ongoing', 'completed']),
                'start_date' => Carbon::now()->subDays($faker->numberBetween(10, 220))->toDateString(),
                'completion_date' => $faker->optional(0.45)->date(),
                'progress_percent' => $faker->numberBetween(0, 100),
                'notes' => $faker->sentence(),
            ]);
        }

        for ($i = 0; $i < 35; $i++) {
            TitleTransfer::create([
                'customer_id' => $faker->randomElement($customerIds),
                'property_id' => $faker->optional(0.88)->randomElement(array_merge(...array_values($propertyIdsByStatus))),
                'status' => $faker->randomElement(['pending', 'submitted_to_bir', 'car_issued', 'registered', 'tct_issued', 'rejected']),
                'submitted_to_bir_at' => $faker->optional(0.65)->date(),
                'car_issued_at' => $faker->optional(0.5)->date(),
                'registered_at' => $faker->optional(0.45)->date(),
                'tct_issued_at' => $faker->optional(0.35)->date(),
                'remarks' => $faker->sentence(),
            ]);
        }
    }
}
