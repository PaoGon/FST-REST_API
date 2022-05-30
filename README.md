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

returns the Authorization bearer key or the JWT token


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
JSON data with success(0 or 1), HTTP response code, data(JSON {teacher_id, full_name, position, total_credits})


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

#### Get own services
```http
  GET /controllers/services/get_service?teacher_id=${teacher_id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :------------------------- |
| `JWT token` | `string` | **Required**. Users Authorization token/JWT token |
| `teacher_id`      | `int` | **Required**. ID of the teacher to fetch with |

returns all services associated to the teacher from latest to oldest posts this called when viewing the users timeline
JSON data with success(0 or 1), HTTP response code, data(JSON {service_id, teacher_id, event_name, starting_date, ending_date, venue, level_of_event, credit_point, sponsor
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

#### Delete posts

```http
  DELETE /controllers/services/delete_service
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :------------------------- |
| `JWT token` | `string` | **Required**. Users Authorization token/JWT token |
| `service_id`      | `int` | **Required**. ID of service to delete |
| `teacher_id`      | `int` | **Required**. ID of the teacher |

returns a JSON data with success, HTTP response code, message(successfull, failed or Method not Allowed)
