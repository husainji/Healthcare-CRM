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
