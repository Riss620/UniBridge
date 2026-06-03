<?php

namespace Tests\Feature;

use App\Mail\SendOtpMail;
use App\Models\GovernmentUser;
use App\Models\Student;
use App\Models\University;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UniBridgeFlowsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. Public Landing Page
     */
    public function test_public_landing_page_works(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertViewIs('welcome');
    }

    /**
     * 2. Student Registration
     */
    public function test_student_registration_creates_user_and_sends_otp(): void
    {
        Mail::fake();

        $univUser = User::create([
            'name'              => 'Univ Admin',
            'email'             => 'univ@example.com',
            'password'          => Hash::make('Secret@123'),
            'role'              => 'university',
            'email_verified_at' => now(),
        ]);

        $university = University::create([
            'user_id'        => $univUser->id,
            'name'           => 'Delhi University',
            'address'        => 'Delhi',
            'city'           => 'Delhi',
            'state'          => 'Delhi',
            'affiliation_no' => 'DU-999',
            'contact_phone'  => '9999999999',
            'status'         => 'approved',
        ]);

        $response = $this->post(route('register'), [
            'name'                  => 'John Doe',
            'email'                 => 'john@example.com',
            'password'              => 'Password@123',
            'password_confirmation' => 'Password@123',
            'role'                  => 'student',
            'university_id'         => $university->id,
            'roll_no'               => 'ROLL999',
            'course'                => 'B.Tech',
            'department'            => 'Computer Science',
            'year'                  => 1,
            'admission_year'        => 2024,
        ]);

        $response->assertRedirect(route('otp.verify'));
        $this->assertDatabaseHas('users', [
            'name'  => 'John Doe',
            'email' => 'john@example.com',
            'role'  => 'student',
        ]);

        $this->assertDatabaseHas('students', [
            'university_id' => $university->id,
            'name'          => 'John Doe',
            'email'         => 'john@example.com',
            'status'        => 'pending',
        ]);

        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user->otp);
        $this->assertNotNull($user->otp_expires_at);

        Mail::assertSent(SendOtpMail::class, function ($mail) use ($user) {
            return $mail->hasTo('john@example.com') && $mail->otp === $user->otp;
        });
    }

    /**
     * 3. University Registration
     */
    public function test_university_registration_creates_pending_university_profile(): void
    {
        Mail::fake();

        $response = $this->post(route('register'), [
            'name'                  => 'DU Registrar',
            'email'                 => 'registrar@du.ac.in',
            'password'              => 'DUUniv@123',
            'password_confirmation' => 'DUUniv@123',
            'role'                  => 'university',
            'university_name'       => 'Delhi University',
            'university_address'    => 'Delhi, India',
            'city'                  => 'Delhi',
            'state'                 => 'Delhi',
            'affiliation_no'        => 'AFF-DU-99',
            'contact_phone'         => '9876543210',
            'university_type'       => 'central',
        ]);

        $response->assertRedirect(route('otp.verify'));

        $this->assertDatabaseHas('users', [
            'email' => 'registrar@du.ac.in',
            'role'  => 'university',
        ]);

        $user = User::where('email', 'registrar@du.ac.in')->first();
        $this->assertDatabaseHas('universities', [
            'user_id'        => $user->id,
            'name'           => 'Delhi University',
            'affiliation_no' => 'AFF-DU-99',
            'status'         => 'pending',
        ]);
    }

    /**
     * 4. Government Registration
     */
    public function test_government_registration_creates_government_profile(): void
    {
        Mail::fake();

        $response = $this->post(route('register'), [
            'name'                  => 'Govt Officer',
            'email'                 => 'officer@gov.in',
            'password'              => 'Officer@123',
            'password_confirmation' => 'Officer@123',
            'role'                  => 'government',
            'department'            => 'Department of Higher Education',
            'designation'           => 'Under Secretary',
        ]);

        $response->assertRedirect(route('otp.verify'));

        $user = User::where('email', 'officer@gov.in')->first();
        $this->assertDatabaseHas('government_users', [
            'user_id'     => $user->id,
            'department'  => 'Department of Higher Education',
            'designation' => 'Under Secretary',
        ]);
    }

    /**
     * 4.5 Admin Registration
     */
    public function test_admin_registration_creates_user_and_sends_otp(): void
    {
        Mail::fake();

        $response = $this->post(route('register'), [
            'name'                  => 'John Admin',
            'email'                 => 'john_admin@example.com',
            'password'              => 'Password@123',
            'password_confirmation' => 'Password@123',
            'role'                  => 'admin',
        ]);

        $response->assertRedirect(route('otp.verify'));
        $this->assertDatabaseHas('users', [
            'name'  => 'John Admin',
            'email' => 'john_admin@example.com',
            'role'  => 'admin',
        ]);

        $user = User::where('email', 'john_admin@example.com')->first();
        $this->assertNotNull($user->otp);
        $this->assertNotNull($user->otp_expires_at);

        Mail::assertSent(SendOtpMail::class, function ($mail) use ($user) {
            return $mail->hasTo('john_admin@example.com') && $mail->otp === $user->otp;
        });
    }

    /**
     * 5. OTP Verification
     */
    public function test_otp_verification_flow_success_and_failures(): void
    {
        // Setup unverified user
        $user = User::create([
            'name'              => 'Verify Me',
            'email'             => 'verify@example.com',
            'password'          => Hash::make('Secret@123'),
            'role'              => 'student',
            'otp'               => '123456',
            'otp_expires_at'    => now()->addMinutes(10),
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);

        // Invalid OTP
        $response = $this->post(route('otp.verify.post'), ['otp' => '999999']);
        $response->assertSessionHasErrors('otp');
        $this->assertNull($user->fresh()->email_verified_at);

        // Expired OTP
        $user->update(['otp_expires_at' => now()->subMinute()]);
        $response = $this->post(route('otp.verify.post'), ['otp' => '123456']);
        $response->assertSessionHasErrors('otp');
        $this->assertNull($user->fresh()->email_verified_at);

        // Success OTP
        $user->update(['otp_expires_at' => now()->addMinutes(5)]);
        $response = $this->post(route('otp.verify.post'), ['otp' => '123456']);
        $response->assertRedirect(route('student.dashboard'));
        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    /**
     * 6. OTP Resend
     */
    public function test_otp_resend_generates_new_otp_and_sends_mail(): void
    {
        Mail::fake();

        $user = User::create([
            'name'              => 'Resend Me',
            'email'             => 'resend@example.com',
            'password'          => Hash::make('Secret@123'),
            'role'              => 'student',
            'otp'               => '111111',
            'otp_expires_at'    => now()->subMinutes(5),
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);

        $response = $this->post(route('otp.resend'));
        $response->assertStatus(302); // Redirect back

        $user = $user->fresh();
        $this->assertNotEquals('111111', $user->otp);
        $this->assertNotNull($user->otp);
        $this->assertTrue($user->otp_expires_at > now());

        Mail::assertSent(SendOtpMail::class, function ($mail) use ($user) {
            return $mail->hasTo('resend@example.com') && $mail->otp === $user->otp;
        });
    }

    /**
     * 7. Login and Security checks
     */
    public function test_login_flow_checks_active_and_verified_status(): void
    {
        $user = User::create([
            'name'              => 'Login User',
            'email'             => 'login@example.com',
            'password'          => Hash::make('Secret@123'),
            'role'              => 'student',
            'is_active'         => true,
            'email_verified_at' => null, // Unverified
        ]);

        // 1. Invalid credentials
        $response = $this->post(route('login'), [
            'email'    => 'login@example.com',
            'password' => 'WrongPassword',
        ]);
        $response->assertSessionHasErrors('email');
        $this->assertFalse(auth()->check());

        // 2. Unverified redirects to OTP verify page
        $response = $this->post(route('login'), [
            'email'    => 'login@example.com',
            'password' => 'Secret@123',
        ]);
        $response->assertRedirect(route('otp.verify'));
        $this->assertTrue(auth()->check());

        // Logout
        $this->post(route('logout'));
        $this->assertFalse(auth()->check());

        // 3. Inactive user fails to login
        $user->update(['is_active' => false, 'email_verified_at' => now()]);
        $response = $this->post(route('login'), [
            'email'    => 'login@example.com',
            'password' => 'Secret@123',
        ]);
        $response->assertSessionHasErrors('email');
        $this->assertFalse(auth()->check());
    }

    /**
     * 8. Password Reset
     */
    public function test_password_reset_flow(): void
    {
        Mail::fake();

        $user = User::create([
            'name'              => 'Forgot User',
            'email'             => 'forgot@example.com',
            'password'          => Hash::make('OldPassword@123'),
            'role'              => 'student',
            'email_verified_at' => now(),
        ]);

        // Request OTP
        $response = $this->post(route('password.email'), ['email' => 'forgot@example.com']);
        $response->assertRedirect(route('password.reset.form', ['email' => 'forgot@example.com']));

        $user = $user->fresh();
        $this->assertNotNull($user->otp);

        Mail::assertSent(SendOtpMail::class, function ($mail) use ($user) {
            return $mail->otp === $user->otp;
        });

        // Submit password reset
        $response = $this->post(route('password.update'), [
            'email'                 => 'forgot@example.com',
            'otp'                   => $user->otp,
            'password'              => 'NewPassword@123',
            'password_confirmation' => 'NewPassword@123',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertTrue(Hash::check('NewPassword@123', $user->fresh()->password));
    }

    /**
     * 9. Admin Operations
     */
    public function test_admin_operations_dashboard_approvals_student_search_and_toggles(): void
    {
        $admin = User::create([
            'name'              => 'Admin User',
            'email'             => 'admin@example.com',
            'password'          => Hash::make('Secret@123'),
            'role'              => 'admin',
            'email_verified_at' => now(),
        ]);

        $univUser = User::create([
            'name'              => 'University User',
            'email'             => 'univ@example.com',
            'password'          => Hash::make('Secret@123'),
            'role'              => 'university',
            'email_verified_at' => now(),
        ]);

        $university = University::create([
            'user_id'        => $univUser->id,
            'name'           => 'Delhi University',
            'address'        => 'Delhi',
            'city'           => 'Delhi',
            'state'          => 'Delhi',
            'affiliation_no' => 'DU-999',
            'contact_phone'  => '9999999999',
            'status'         => 'approved',
        ]);

        $student = Student::create([
            'university_id'  => $university->id,
            'name'           => 'Alice Smith',
            'roll_no'        => 'DU1001',
            'email'          => 'alice@du.ac.in',
            'course'         => 'B.Tech',
            'department'     => 'Computer Science',
            'year'           => 3,
            'cgpa'           => 9.2,
            'status'         => 'active',
            'admission_year' => 2023,
        ]);

        $this->actingAs($admin);

        // Dashboard access
        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertViewHasAll(['stats', 'recentUniversities']);

        // Universities page
        $response = $this->get(route('admin.universities', ['status' => 'pending']));
        $response->assertStatus(200);

        // Search students
        $response = $this->get(route('admin.students', ['search' => 'Alice']));
        $response->assertStatus(200);

        // List Users
        $response = $this->get(route('admin.users'));
        $response->assertStatus(200);

        // Toggle user status
        $response = $this->post(route('admin.users.toggle', $univUser));
        $response->assertStatus(302);
        $this->assertFalse($univUser->fresh()->is_active);

        $response = $this->post(route('admin.users.toggle', $univUser));
        $this->assertTrue($univUser->fresh()->is_active);

        // Admin Create User - Government
        $response = $this->post(route('admin.users.store'), [
            'name'        => 'Admin Created Govt',
            'email'       => 'ac_govt@gov.in',
            'password'    => 'Secret@123',
            'role'        => 'government',
            'department'  => 'HRD',
            'designation' => 'Director',
        ]);
        $response->assertRedirect(route('admin.users'));
        $this->assertDatabaseHas('users', ['email' => 'ac_govt@gov.in']);
        $this->assertDatabaseHas('government_users', ['department' => 'HRD']);

        // Admin Delete User
        $userToDelete = User::where('email', 'ac_govt@gov.in')->first();
        $response = $this->delete(route('admin.users.delete', $userToDelete));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('users', ['email' => 'ac_govt@gov.in']);
        $this->assertDatabaseMissing('government_users', ['department' => 'HRD']);
    }

    /**
     * 10. University Operations
     */
    public function test_university_operations_student_management_and_approval_guards(): void
    {
        $univUser = User::create([
            'name'              => 'Univ Admin',
            'email'             => 'univ@example.com',
            'password'          => Hash::make('Secret@123'),
            'role'              => 'university',
            'email_verified_at' => now(),
        ]);

        $university = University::create([
            'user_id'        => $univUser->id,
            'name'           => ' दिल्ली विश्वविद्यालय',
            'address'        => 'Delhi',
            'city'           => 'Delhi',
            'state'          => 'Delhi',
            'affiliation_no' => 'DU-999',
            'contact_phone'  => '9999999999',
            'status'         => 'pending', // Unapproved
        ]);

        $this->actingAs($univUser);

        // Dashboard stats showing 0
        $response = $this->get(route('university.dashboard'));
        $response->assertStatus(200);

        // Trying to view students redirects with warning
        $response = $this->get(route('university.students'));
        $response->assertRedirect(route('university.dashboard'));
        $response->assertSessionHas('warning');

        // Trying to create student redirects with warning
        $response = $this->get(route('university.students.create'));
        $response->assertRedirect(route('university.dashboard'));

        // Admin approves university
        $university->update(['status' => 'approved']);
        $univUser->refresh();

        // Now view students page
        $response = $this->get(route('university.students'));
        $response->assertStatus(200);

        // Create student
        $response = $this->post(route('university.students.store'), [
            'name'           => 'Bob Vance',
            'roll_no'        => 'BOB2024',
            'email'          => 'bob@example.com',
            'course'         => 'MBA',
            'department'     => 'Management',
            'year'           => 1,
            'cgpa'           => 8.5,
            'status'         => 'pending',
            'admission_year' => 2024,
        ]);
        $response->assertRedirect(route('university.students'));
        $this->assertDatabaseHas('students', [
            'university_id' => $university->id,
            'name'          => 'Bob Vance',
            'roll_no'       => 'BOB2024',
            'status'        => 'pending',
        ]);

        $student = Student::where('roll_no', 'BOB2024')->first();

        // Approve student
        $response = $this->post(route('university.students.approve', $student));
        $response->assertRedirect();
        $this->assertEquals('active', $student->fresh()->status);

        // Edit form
        $response = $this->get(route('university.students.edit', $student));
        $response->assertStatus(200);

        // Update student
        $response = $this->put(route('university.students.update', $student), [
            'name'           => 'Bob Vance Jr',
            'roll_no'        => 'BOB2024',
            'email'          => 'bob@example.com',
            'course'         => 'MBA',
            'department'     => 'Management',
            'year'           => 2,
            'cgpa'           => 8.9,
            'status'         => 'active',
            'admission_year' => 2024,
        ]);
        $response->assertRedirect(route('university.students'));
        $this->assertEquals('Bob Vance Jr', $student->fresh()->name);
        $this->assertEquals(2, $student->fresh()->year);

        // Delete student
        $response = $this->delete(route('university.students.delete', $student));
        $response->assertRedirect();
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }

    /**
     * Test that an unapproved or rejected university cannot edit, update, or delete students.
     */
    public function test_unapproved_university_cannot_modify_or_edit_students(): void
    {
        $univUser = User::create([
            'name'              => 'Pending Univ',
            'email'             => 'pending@example.com',
            'password'          => Hash::make('Secret@123'),
            'role'              => 'university',
            'email_verified_at' => now(),
        ]);

        $university = University::create([
            'user_id'        => $univUser->id,
            'name'           => 'Delhi University',
            'address'        => 'Delhi',
            'city'           => 'Delhi',
            'state'          => 'Delhi',
            'affiliation_no' => 'DU-999',
            'contact_phone'  => '9999999999',
            'status'         => 'pending', // NOT approved
        ]);

        $student = Student::create([
            'university_id'  => $university->id,
            'name'           => 'Alice Smith',
            'roll_no'        => 'DU1001',
            'email'          => 'alice@du.ac.in',
            'course'         => 'B.Tech',
            'department'     => 'Computer Science',
            'year'           => 3,
            'cgpa'           => 9.2,
            'status'         => 'active',
            'admission_year' => 2023,
        ]);

        $this->actingAs($univUser);

        // 1. Edit student form should return 403
        $response = $this->get(route('university.students.edit', $student));
        $response->assertStatus(403);

        // 2. Update student should return 403
        $response = $this->put(route('university.students.update', $student), [
            'name'           => 'Alice Updated',
            'roll_no'        => 'DU1001',
            'course'         => 'B.Tech',
            'department'     => 'Computer Science',
            'year'           => 3,
            'admission_year' => 2023,
            'status'         => 'active',
        ]);
        $response->assertStatus(403);
        $this->assertEquals('Alice Smith', $student->fresh()->name);

        // 3. Delete student should return 403
        $response = $this->delete(route('university.students.delete', $student));
        $response->assertStatus(403);
        $this->assertDatabaseHas('students', ['id' => $student->id]);
    }

    /**
     * 11. Government Operations
     */
    public function test_government_operations_open_data_and_export(): void
    {
        $govtUser = User::create([
            'name'              => 'Govt Officer',
            'email'             => 'govt@example.com',
            'password'          => Hash::make('Secret@123'),
            'role'              => 'government',
            'email_verified_at' => now(),
        ]);

        GovernmentUser::create([
            'user_id'     => $govtUser->id,
            'department'  => 'HRD',
            'designation' => 'Advisor',
        ]);

        $univUser = User::create([
            'name'              => 'Univ Admin',
            'email'             => 'univ@example.com',
            'password'          => Hash::make('Secret@123'),
            'role'              => 'university',
            'email_verified_at' => now(),
        ]);

        $approvedUniv = University::create([
            'user_id'        => $univUser->id,
            'name'           => 'IIT Bombay',
            'address'        => 'Powai',
            'city'           => 'Mumbai',
            'state'          => 'Maharashtra',
            'affiliation_no' => 'IITB-001',
            'contact_phone'  => '9999999999',
            'status'         => 'approved',
        ]);

        $pendingUniv = University::create([
            'user_id'        => $univUser->id,
            'name'           => 'Pending Univ Govt Test',
            'address'        => 'Address',
            'city'           => 'City',
            'state'          => 'State',
            'affiliation_no' => 'PEND-999',
            'contact_phone'  => '9999999999',
            'status'         => 'pending',
        ]);

        Student::create([
            'university_id'  => $approvedUniv->id,
            'name'           => 'Charlie Brown',
            'roll_no'        => 'IITB101',
            'email'          => 'charlie@iitb.ac.in',
            'course'         => 'B.Tech CS',
            'department'     => 'Computer Science',
            'year'           => 4,
            'cgpa'           => 9.5,
            'status'         => 'active',
            'admission_year' => 2022,
        ]);

        $this->actingAs($govtUser);

        // Dashboard
        $response = $this->get(route('government.dashboard'));
        $response->assertStatus(200);

        // Reject university
        $response = $this->post(route('government.universities.reject', $pendingUniv), ['reason' => 'Invalid docs']);
        $response->assertStatus(302);
        $this->assertEquals('rejected', $pendingUniv->fresh()->status);

        // Approve university
        $response = $this->post(route('government.universities.approve', $pendingUniv));
        $response->assertStatus(302);
        $this->assertEquals('approved', $pendingUniv->fresh()->status);

        // View Student Open Data
        $response = $this->get(route('government.data', ['search' => 'Charlie', 'state' => 'Maharashtra']));
        $response->assertStatus(200);
        $response->assertViewHasAll(['students', 'states', 'universities']);

        // Export Data CSV
        $response = $this->get(route('government.export', ['state' => 'Maharashtra']));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('Charlie Brown', $response->getContent());

        // Approved Universities List
        $response = $this->get(route('government.universities'));
        $response->assertStatus(200);
    }

    /**
     * 12. Student Operations
     */
    public function test_student_dashboard_links_automatically_via_email(): void
    {
        $univUser = User::create([
            'name'              => 'DU Admin',
            'email'             => 'du@example.com',
            'password'          => Hash::make('Secret@123'),
            'role'              => 'university',
            'email_verified_at' => now(),
        ]);

        $university = University::create([
            'user_id'        => $univUser->id,
            'name'           => 'Delhi University',
            'address'        => 'Delhi',
            'city'           => 'Delhi',
            'state'          => 'Delhi',
            'affiliation_no' => 'DU-888',
            'contact_phone'  => '9999999999',
            'status'         => 'approved',
        ]);

        // University created student record
        $studentRecord = Student::create([
            'university_id'  => $university->id,
            'name'           => 'Emma Watson',
            'roll_no'        => 'DU9876',
            'email'          => 'emma@student.du.ac.in',
            'course'         => 'B.A. English',
            'department'     => 'English',
            'year'           => 2,
            'cgpa'           => 9.1,
            'status'         => 'active',
            'admission_year' => 2023,
        ]);

        // Student registers as user with same email
        $studentUser = User::create([
            'name'              => 'Emma Watson',
            'email'             => 'emma@student.du.ac.in',
            'password'          => Hash::make('Secret@123'),
            'role'              => 'student',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($studentUser);

        // View dashboard
        $response = $this->get(route('student.dashboard'));
        $response->assertStatus(200);
        $response->assertViewHas('studentRecord');
        
        $returnedRecord = $response->viewData('studentRecord');
        $this->assertNotNull($returnedRecord);
        $this->assertEquals($studentRecord->id, $returnedRecord->id);
        $this->assertEquals('Delhi University', $returnedRecord->university->name);
    }

    /**
     * 13. Database Seeder
     */
    public function test_database_seeder_runs_successfully(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
        $this->assertDatabaseHas('users', ['email' => 'admin@unibridge.in']);
        $this->assertDatabaseHas('users', ['email' => 'govt@unibridge.in']);
        $this->assertDatabaseHas('users', ['email' => 'du@unibridge.in']);
        $this->assertDatabaseHas('users', ['email' => 'student1@du.ac.in']);
        $this->assertDatabaseHas('universities', ['name' => 'University of Delhi']);
        $this->assertDatabaseHas('government_users', ['department' => 'Ministry of Education']);
        $this->assertTrue(Student::count() > 0);
    }
}
