This project made in Laravel 10 and used mysql database. This is the Healtcare-CRM Project where user created with a specific role. For the roll and premssion spatie has been used. Database seeder has been used for roll-permission and user. In this project web application and API both are have been implemented. In this project API has been made for patient management. Sanctum has been used for API. Project setup steps given below:-

1. Clone the Repository    (git clone https://github.com/husainji/Healthcare-CRM.git)
2. Go into the Project Directory (cd Healthcare-CRM)
3. Install PHP Dependencies (composer install)
4. Install Node Dependencies  (npm install & npm run dev)
5. Set Up .env File  (cp .env.example .env) (open the .env file and give Databasename and credentials)
6. Generate Application Key (php artisan key:generate)
7. Run Database Migrations (php artisan migrate)
8. Run the Database seeders (php artisan db:seed)
9. php artisan serve (php artisan serve)


Publish the Vendor Files:-
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

Testing the web part:-
Seeded credentials (for testing):
Admin: admin@example.com / password
CRM Agent: crm@example.com / password
Doctor: doctor@example.com / password
Patient: patient@example.com / password
Lab Manager: lab@example.com / password

Testing the APIs:-
1.Create an API token for a user (e.g., admin):
#in tinker
$u = App\Models\User::where('email','admin@example.com')->first();
$u->createToken('api-token')->plainTextToken;

2.Use that token in Postman / http client:
Authorization: Bearer <plainTextToken>
Accept: application/json

3.Test:
POST /api/patients with JSON body to create patient.
GET /api/patients?q=john for search
POST /api/appointments to schedule (checks conflict)
GET /api/patients/{patient_id}/audits (Admin only)



Postman collection of Patient module:-

{
	"info": {
		"_postman_id": "0b0cca80-6f0a-4a33-8ab8-b6b4112b3e48",
		"name": "Patient Management API",
		"description": "Collection for testing Patient Management APIs",
		"schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json",
		"_exporter_id": "8974831"
	},
	"item": [
		{
			"name": "Create Patient",
			"request": {
				"auth": {
					"type": "bearer",
					"bearer": [
						{
							"key": "token",
							"value": "1|izsSlgIeA2ngjZmVVVDj1M23FimwZkOsKkwTIVWX2af07174",
							"type": "string"
						}
					]
				},
				"method": "POST",
				"header": [
					{
						"key": "Accept",
						"value": "application/json"
					},
					{
						"key": "Authorization",
						"value": "Bearer {{token}}"
					}
				],
				"body": {
					"mode": "raw",
					"raw": "{\n  \"user_id\": 4,\n  \"first_name\": \"Patient\",\n  \"last_name\": \"User\",\n  \"date_of_birth\": \"1990-01-01\",\n  \"gender\": \"Male\",\n  \"phone_number\": \"9998887777\",\n  \"email\": \"patient@example.com\",\n  \"address\": \"123 Street, City\",\n  \"emergency_contact_name\": \"Patient User\",\n  \"emergency_contact_phone\": \"8887776666\",\n  \"insurance_details\": {\"provider\": \"ABC Insurance\", \"policy_number\": \"POL12345\"}\n}",
					"options": {
						"raw": {
							"language": "json"
						}
					}
				},
				"url": {
					"raw": "http://127.0.0.1:8000/api/patients",
					"protocol": "http",
					"host": [
						"127",
						"0",
						"0",
						"1"
					],
					"port": "8000",
					"path": [
						"api",
						"patients"
					]
				}
			},
			"response": []
		},
		{
			"name": "Get All Patients",
			"request": {
				"auth": {
					"type": "bearer",
					"bearer": [
						{
							"key": "token",
							"value": "1|izsSlgIeA2ngjZmVVVDj1M23FimwZkOsKkwTIVWX2af07174",
							"type": "string"
						}
					]
				},
				"method": "GET",
				"header": [
					{
						"key": "Accept",
						"value": "application/json"
					},
					{
						"key": "Authorization",
						"value": "Bearer {{token}}"
					}
				],
				"url": {
					"raw": "http://127.0.0.1:8000/api/patients",
					"protocol": "http",
					"host": [
						"127",
						"0",
						"0",
						"1"
					],
					"port": "8000",
					"path": [
						"api",
						"patients"
					]
				}
			},
			"response": []
		},
		{
			"name": "Get Patient By ID",
			"request": {
				"auth": {
					"type": "bearer",
					"bearer": [
						{
							"key": "token",
							"value": "1|izsSlgIeA2ngjZmVVVDj1M23FimwZkOsKkwTIVWX2af07174",
							"type": "string"
						}
					]
				},
				"method": "GET",
				"header": [
					{
						"key": "Accept",
						"value": "application/json"
					},
					{
						"key": "Authorization",
						"value": "Bearer {{token}}"
					}
				],
				"url": {
					"raw": "http://127.0.0.1:8000/api/patients/PAT-KJGUY4Z0",
					"protocol": "http",
					"host": [
						"127",
						"0",
						"0",
						"1"
					],
					"port": "8000",
					"path": [
						"api",
						"patients",
						"PAT-KJGUY4Z0"
					]
				}
			},
			"response": []
		},
		{
			"name": "Get Patient Audits",
			"request": {
				"auth": {
					"type": "bearer",
					"bearer": [
						{
							"key": "token",
							"value": "1|izsSlgIeA2ngjZmVVVDj1M23FimwZkOsKkwTIVWX2af07174",
							"type": "string"
						}
					]
				},
				"method": "GET",
				"header": [
					{
						"key": "Accept",
						"value": "application/json"
					},
					{
						"key": "Authorization",
						"value": "Bearer {{token}}"
					}
				],
				"url": {
					"raw": "http://127.0.0.1:8000/api/patients/PAT-KJGUY4Z0/audits",
					"protocol": "http",
					"host": [
						"127",
						"0",
						"0",
						"1"
					],
					"port": "8000",
					"path": [
						"api",
						"patients",
						"PAT-KJGUY4Z0",
						"audits"
					]
				}
			},
			"response": []
		}
	],
	"variable": [
		{
			"key": "base_url",
			"value": "http://localhost:8000"
		},
		{
			"key": "token",
			"value": ""
		},
		{
			"key": "patient_id",
			"value": ""
		}
	]
}
