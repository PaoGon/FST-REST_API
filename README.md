# Facutly service tracker REST API

The REST API for our project [Faculty Service Tracker](https://github.com/PaoGon/faculty_service_tracker)





## Project prerequisites

General requirements
* composer

installation of [Composer](https://getcomposer.org/)
## Quick start

Note: clone this inside your webserver's directory

1.Cloning the repository

```bash
git clone https://github.com/PaoGon/FST-REST_API.git
```
3.Installing dependencies

```bash
cd FST-REST_API
composer install
```

## API Reference

### Accounts end points

#### Login

```http
  POST /api/accounts/login
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :------------------------- |
| `email` | `string` | **Required**. Account Email |
| `password` | `string` | **Required**. Account Password |

returns the Authorization bearer key or the  JWT token, Profile URL and the account type


#### Create account

```http
  POST /accounts/signup
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :------------------------- |
| `JWT token` | `string` | **Required**. Users Authorization token/JWT token |
| `full_name`      | `int` | **Required**. Account full name |
| `email` | `string` | **Required**. Account email |
| `password` | `string` | **Required**. Accountt password |
| `gender` | `string` | **Required**. Gender of the user  |
| `is_admin` | `int` | **Required**. type of account(0 or 1)  |

returns a JSON data with success, HTTP response code, message(successfull, failed or Method not Allowed)



### Admin end points

#### Get all teachers

```http
  GET /controllers/admin/get_accounts
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `JWT token` | `string` | **Required**. Users Authorization token/JWT token |

returns all of the existing teachers
JSON data with success(0 or 1), HTTP response code, data(JSON {teacher_id, full_name, position, total_credits, profile_dir})

#### Update service

```http
  PUT /controllers/admin/update_credit
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :------------------------- |
| `JWT token` | `string` | **Required**. Users Authorization token/JWT token |
| `teacher_id`      | `int` | **Required**. ID of the teacher |
| `starting_date` | `date` | **Required**. The 1st day of the event |
| `ending_date` | `date` | **Required**. The last day of the event |
| `credit points` | `int` | **Required**. equivalent credit for the service |


returns a JSON data with success, HTTP response code, message(successfull, failed or Method not Allowed)

#### Delete account

```http
  DELETE /controllers/admin/delete_acc
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :------------------------- |
| `JWT token` | `string` | **Required**. Users Authorization token/JWT token |
| `acc_id`      | `int` | **Required**. ID of account to delete |

returns a JSON data with success, HTTP response code, message(successfull, failed or Method not Allowed)



### Teacher end points

#### Create services

```http
  POST /controllers/services/create_services
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :------------------------- |
| `JWT token` | `string` | **Required**. Users Authorization token/JWT token |
| `teacher_id`      | `int` | **Required**. ID of the teacher |
| `event_name` | `string` | **Required**. name of the event attended |
| `starting_date` | `date` | **Required**. The 1st day of the event |
| `ending_date` | `date` | **Required**. The last day of the event |
| `venue` | `string` | **Required**. online or physical |
| `level_of_event` | `string` | **Required**. local or international |
| `credit points` | `int` | **Required**. equivalent credit for the service |
| `sponsor` | `string` | **Required**. sponsor of the event |

returns a JSON data with success, HTTP response code, message(successfull, failed or Method not Allowed)

#### Upload service picutre

```http
  POST /controllers/services/upload_service_pic.php
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :------------------------- |
| `JWT token` | `string` | **Required**. Users Authorization token/JWT token |
| `file`      | `string` | **Required**. The file |
| `acc_id` | `int` | **Required**. The account id |
| `service_id` | `int` | **Required**. The account id |

returns a JSON data with success, HTTP response code, message(successfull, failed or Method not Allowed)

#### Get own services
```http
  GET /controllers/services/get_service?teacher_id=${teacher_id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :------------------------- |
| `JWT token` | `string` | **Required**. Users Authorization token/JWT token |
| `teacher_id`      | `int` | **Required**. ID of the teacher to fetch with |

returns all services associated to the teacher from latest to oldest posts this called when viewing the users timeline
JSON data with success(0 or 1), HTTP response code, data(JSON {service_id, teacher_id, event_name, starting_date, ending_date, venue, level_of_event, credit_point, sponsor, service picture DIR
})


#### Update service

```http
  PUT /controllers/services/update_service
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :------------------------- |
| `JWT token` | `string` | **Required**. Users Authorization token/JWT token |
| `teacher_id`      | `int` | **Required**. ID of the teacher |
| `event_name` | `string` | **Required**. name of the event attended |
| `starting_date` | `date` | **Required**. The 1st day of the event |
| `ending_date` | `date` | **Required**. The last day of the event |
| `venue` | `string` | **Required**. online or physical |
| `level_of_event` | `string` | **Required**. local or international |
| `credit points` | `int` | **Required**. equivalent credit for the service |
| `sponsor` | `string` | **Required**. sponsor of the event |


returns a JSON data with success, HTTP response code, message(successfull, failed or Method not Allowed)

#### Delete service

```http
  DELETE /controllers/services/delete_service
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :------------------------- |
| `JWT token` | `string` | **Required**. Users Authorization token/JWT token |
| `service_id`      | `int` | **Required**. ID of service to delete |
| `teacher_id`      | `int` | **Required**. ID of the teacher |

returns a JSON data with success, HTTP response code, message(successfull, failed or Method not Allowed)

#### Upload Profile Picture

```http
  POST /controllers/upload_profile
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :------------------------- |
| `JWT token` | `string` | **Required**. Users Authorization token/JWT token |
| `file`      | `string` | **Required**. The file |
| `acc_id` | `int` | **Required**. The account id |

returns a JSON data with success, HTTP response code, message(successfull, failed or Method not Allowed)

#### Upload File

```http
  POST /controllers/services/upload_file
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :------------------------- |
| `JWT token` | `string` | **Required**. Users Authorization token/JWT token |
| `file`      | `string` | **Required**. The file |
| `acc_id` | `int` | **Required**. The account id |

returns a JSON data with success, HTTP response code, message(successfull, failed or Method not Allowed)
