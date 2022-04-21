<?php

namespace Tests\Feature;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ApiCompaniesTest extends TestCase
{
	use RefreshDatabase;
	
	private array $postData = [
		'name' => 'FIRMA',
		'nip' => '1234567890',
		'address' => 'jakaś ulica 2',
		'city' => 'Miasto',
		'postal_code' => '12345',
	];
	
	protected Company $company;
	
	protected function setUp(): void
	{
		parent::setUp();
		
		$this->company = Company::create($this->postData);
	}
	
    /**
     * test companies index
     *
     * @return void
     */
    public function test_companies_index()
    {
        $response = $this->get('/api/v1/companies');

        $response->assertStatus(Response::HTTP_OK);
    }
	
	/**
     * test companies show
     *
     * @return void
     */
    public function test_companies_show()
    {
        $response = $this->get('/api/v1/companies/' . $this->company->id);

        $response->assertStatus(Response::HTTP_OK);
    }
	
	/**
     * test companies show no record in db
     *
     * @return void
     */
    public function test_companies_show_no_record_in_db()
    {
        $response = $this->get('/api/v1/companies/0');

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }
	
	/**
     * test companies store
     *
     * @return void
     */
    public function test_companies_store()
    {
        $response = $this->postJson('/api/v1/companies', $this->postData);
	
		$companyId = json_decode($response->content())->data->id;
		$postData = $this->postData;
		$postData['id'] = $companyId;
		
        $response->assertStatus(Response::HTTP_CREATED)->assertJson(['data' => $postData]);
    }
	
	/**
     * test companies update
     *
     * @return void
     */
    public function test_companies_update()
    {		
        $response = $this->putJson('/api/v1/companies/' . $this->company->id, $this->postData);
		
		$postData = $this->postData;
		$postData['id'] = $this->company->id;
		
        $response->assertStatus(Response::HTTP_OK)->assertJson(['data' => $postData]);
    }
	
	/**
     * test companies delete
     *
     * @return void
     */
    public function test_companies_delete()
    {		
        $response = $this->delete('/api/v1/companies/' . $this->company->id);

        $response->assertStatus(Response::HTTP_OK);
    }
}
