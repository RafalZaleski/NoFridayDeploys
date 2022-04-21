<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ApiEmployeesTest extends TestCase
{	
	private $postDataEmployee = [
		'name' => 'pracownik',
		'surname' => 'pracownik',
		'email' => 'test@aa.bb',
		'phone_number' => '123456789',
	];
	
	protected Company $company;
	protected Employee $employee;
	
	protected function setUp(): void
	{
		$postDataCompany = [
			'name' => 'FIRMA',
			'nip' => '1234567890',
			'address' => 'jakaś ulica 2',
			'city' => 'Miasto',
			'postal_code' => '12345',
		];
	
		parent::setUp();
		
		$this->company = Company::create($postDataCompany);
		
		$postDataEmployee = $this->postDataEmployee;
		$postDataEmployee['company_id'] = $this->company->id;
		
		$this->employee = Employee::create($postDataEmployee);
	}
	
    /**
     * test employees index
     *
     * @return void
     */
    public function test_employees_index()
    {
        $response = $this->get('/api/v1/companies/1/employees');

        $response->assertStatus(Response::HTTP_OK);
    }
	
	/**
     * test employees show
     *
     * @return void
     */
    public function test_employees_show()
    {
        $response = $this->get('/api/v1/companies/' . $this->company->id . '/employees/' . $this->employee->id);

        $response->assertStatus(Response::HTTP_OK);
    }
	
	/**
     * test employees show no record in db
     *
     * @return void
     */
    public function test_employees_show_no_record_in_db()
    {
        $response = $this->get('/api/v1/companies/0/employees/0');

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }
	
	/**
     * test employees store
     *
     * @return void
     */
    public function test_employees_store()
    {
        $response = $this->postJson('/api/v1/companies/' . $this->company->id . '/employees', $this->postDataEmployee);
	
		$employeeId = json_decode($response->content())->data->id;
		$postData = $this->postDataEmployee;
		$postData['id'] = $employeeId;
		
        $response->assertStatus(Response::HTTP_CREATED)->assertJson(['data' => $postData]);
    }
	
	/**
     * test employees update
     *
     * @return void
     */
    public function test_employees_update()
    {		
        $response = $this->putJson('/api/v1/companies/' . $this->company->id . '/employees/' . $this->employee->id, $this->postDataEmployee);
		
		$postData = $this->postDataEmployee;
		$postData['id'] = $this->employee->id;
		
        $response->assertStatus(Response::HTTP_OK)->assertJson(['data' => $postData]);
    }
	
	/**
     * test employees delete
     *
     * @return void
     */
    public function test_employees_delete()
    {		
        $response = $this->delete('/api/v1/companies/' . $this->company->id . '/employees/' . $this->employee->id);

        $response->assertStatus(Response::HTTP_OK);
    }
}
