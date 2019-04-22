
# ToDo List API



## Indices

* [Auth](#auth)

  * [Register](#1-register)
  * [Login](#2-login)
  * [Refresh Token](#3-refresh-token)
  * [Logout](#4-logout)
  * [Self](#5-self)

* [ToDo](#todo)

  * [List](#1-list)
  * [Show](#2-show)
  * [Create](#3-create)
  * [Update](#4-update)
  * [Delete](#5-delete)


--------


## Auth



### 1. Register


Создание нового пользователя, получение токенов доступа.


***Endpoint:***

```bash
Method: POST
Type: FORMDATA
URL: {{base_url}}/api/register
```



***Body:***

| Key | Value | Description |
| --- | ------|-------------|
| email | radchenko_io@gmail.com |  |
| name | Ihor |  |
| password | 123456 |  |
| confirm_password | 123456 |  |



***Responses:***


Status: Register validation error | Code: 422



```js
{
    "email": {
        "objectFound": "A user with this email already exists."
    }
}
```



Status: Register success | Code: 201



```js
{
    "type": "bearer",
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzM4NCJ9.eyJpc3MiOiIxMjcuMC4wLjEiLCJpYXQiOjE1NTU5NTM0NjIsIm5iZiI6MTU1NTk1MzQ2MiwiZXhwIjoxNTU1OTU3MDYyLCJqdGkiOiIyRm1peEx0WWNWQlJXc2JyIiwic3ViIjoiMTYiLCJhY2MiOnRydWV9.qAaixFomHcUyIGVbi1N909lCS1_afC-_aky51xkOkNWpp4gII018cH9zoCPGbjQV",
    "refresh_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzM4NCJ9.eyJpc3MiOiIxMjcuMC4wLjEiLCJpYXQiOjE1NTU5NTM0NjIsIm5iZiI6MTU1NTk1MzQ2MiwiZXhwIjoxNTU3MTYzMDYyLCJqdGkiOiIwUmNNMlBseWc0aGxyUGZlIiwic3ViIjoiMTYiLCJhY2MiOmZhbHNlfQ.aqZpTkWEcz0fWcYJVkkytiwuE4CU3RJb_TIjGFnDyjdTyqByO-DDBw5QgLpURw4C",
    "expired_in": 3600
}
```



### 2. Login


Получение токенов доступа.


***Endpoint:***

```bash
Method: POST
Type: FORMDATA
URL: {{base_url}}/api/login
```



***Body:***

| Key | Value | Description |
| --- | ------|-------------|
| email | radchenko_io@gmail.com |  |
| password | 123456 |  |



***Responses:***


Status: Login validation error | Code: 422



```js
{
    "email": {
        "noObjectFound": "Invalid credentials."
    }
}
```



Status: Login success | Code: 200



```js
{
    "type": "bearer",
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzM4NCJ9.eyJpc3MiOiIxMjcuMC4wLjEiLCJpYXQiOjE1NTU5NTM3MjIsIm5iZiI6MTU1NTk1MzcyMiwiZXhwIjoxNTU1OTU3MzIyLCJqdGkiOiJxWjZtNU5lZ0FWRFhxdVNUIiwic3ViIjoiMTciLCJhY2MiOnRydWV9.Lp1K6gx5jRonDPTAN3ukeIyozfVPlIhkCypoMNrnSWNdY0Pf7If79G4sOdLIUzUH",
    "refresh_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzM4NCJ9.eyJpc3MiOiIxMjcuMC4wLjEiLCJpYXQiOjE1NTU5NTM3MjIsIm5iZiI6MTU1NTk1MzcyMiwiZXhwIjoxNTU3MTYzMzIyLCJqdGkiOiI4a1U2aUJ4QU1SY3lSTTVYIiwic3ViIjoiMTciLCJhY2MiOmZhbHNlfQ.GuoMnX4jFApYUyU9J9bnv79fZ_3r45DhvKO2I7t1MFjo0H2_Wu7Lw_5FMSLH-WOG",
    "expired_in": 3600
}
```



### 3. Refresh Token


Обновление токенов доступа.


***Endpoint:***

```bash
Method: POST
Type: RAW
URL: {{base_url}}/api/refresh
```



***Responses:***


Status: Refresh Token invalid token | Code: 400



```js
{
    "content": "Invalid token."
}
```



Status: Refresh Token success | Code: 200



```js
{
    "type": "bearer",
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzM4NCJ9.eyJpc3MiOiIxMjcuMC4wLjEiLCJpYXQiOjE1NTU5NTM4MjcsIm5iZiI6MTU1NTk1MzgyNywiZXhwIjoxNTU1OTU3NDI3LCJqdGkiOiJDRkF3WWc3MEdxdFY4YTZhIiwic3ViIjoiMTciLCJhY2MiOnRydWV9.F00IEaoul2be9Jz8NsaARnEDlVL8G-XT7ARhP5wem8aC75RULC4bCHmLWRiGiJ_H",
    "refresh_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzM4NCJ9.eyJpc3MiOiIxMjcuMC4wLjEiLCJpYXQiOjE1NTU5NTM4MjcsIm5iZiI6MTU1NTk1MzgyNywiZXhwIjoxNTU3MTYzNDI3LCJqdGkiOiJST0lEbnlFdmZNY3pXeDZlIiwic3ViIjoiMTciLCJhY2MiOmZhbHNlfQ.sX0MkfFTJ3aiGWt23qyITlxPtK9G_PiM_iDalYAhaaPfdkh1JNEYZag4PHn9AkY6",
    "expired_in": 3600
}
```



### 4. Logout



***Endpoint:***

```bash
Method: POST
Type: RAW
URL: {{base_url}}/api/logout
```



***Responses:***


Status: Logout success | Code: 204



### 5. Self


Получение данных о залогиненом юзере.


***Endpoint:***

```bash
Method: GET
Type: RAW
URL: {{base_url}}/api/self
```



***Responses:***


Status: Self success | Code: 200



```js
{
    "data": {
        "id": 17,
        "type": "user",
        "attributes": {
            "name": "Ihor",
            "email": "radchenko_io@gmail.com",
            "created_at": "2019-04-22T17:20:58+00:00",
            "updated_at": "2019-04-22T17:20:58+00:00"
        }
    }
}
```



## ToDo



### 1. List


Получение todo списка пользователя. 
Для получения только завершенных todo можно использовать фильтр(параметр filter[is_completed]=1), соответсвенно для получения только не завершенных (filter[is_completed]=0).
Для сортировки списка используется параметр sort. Доступные поля (title, is_completed, created_at, updated_at), для указания направления: - перед полем DESC, иначе ASC.


***Endpoint:***

```bash
Method: GET
Type: RAW
URL: {{base_url}}/api/v1/todos
```



***Query params:***

| Key | Value | Description |
| --- | ------|-------------|
| sort | -is_completed |  |
| filter[is_completed] | 1 |  |



***Responses:***


Status: List only completed | Code: 200



```js
{
    "data": [
        {
            "id": 6,
            "type": "todo",
            "attributes": {
                "title": "Title",
                "user_id": 17,
                "is_completed": true,
                "created_at": "2019-04-22T17:55:27+00:00",
                "updated_at": "2019-04-22T17:57:31+00:00"
            }
        }
    ]
}
```



Status: List success | Code: 200



```js
{
    "data": [
        {
            "id": 6,
            "type": "todo",
            "attributes": {
                "title": "Title",
                "user_id": 17,
                "is_completed": true,
                "created_at": "2019-04-22T17:55:27+00:00",
                "updated_at": "2019-04-22T17:57:31+00:00"
            }
        },
        {
            "id": 5,
            "type": "todo",
            "attributes": {
                "title": "New todo",
                "user_id": 17,
                "is_completed": false,
                "created_at": "2019-04-22T17:31:41+00:00",
                "updated_at": "2019-04-22T17:31:41+00:00"
            }
        }
    ]
}
```



Status: List sort is_completed desc | Code: 200



```js
{
    "data": [
        {
            "id": 6,
            "type": "todo",
            "attributes": {
                "title": "Title",
                "user_id": 17,
                "is_completed": true,
                "created_at": "2019-04-22T17:55:27+00:00",
                "updated_at": "2019-04-22T17:57:31+00:00"
            }
        },
        {
            "id": 5,
            "type": "todo",
            "attributes": {
                "title": "New todo",
                "user_id": 17,
                "is_completed": false,
                "created_at": "2019-04-22T17:31:41+00:00",
                "updated_at": "2019-04-22T17:31:41+00:00"
            }
        }
    ]
}
```



### 2. Show


Получение 1 todo пользователя.


***Endpoint:***

```bash
Method: GET
Type: RAW
URL: {{base_url}}/api/v1/todos/{{todo_id}}
```



***Responses:***


Status: Show success | Code: 200



```js
{
    "data": {
        "id": 6,
        "type": "todo",
        "attributes": {
            "title": "New todo",
            "user_id": 17,
            "is_completed": false,
            "created_at": "2019-04-22T17:55:27+00:00",
            "updated_at": "2019-04-22T17:55:27+00:00"
        }
    }
}
```



Status: Show not found | Code: 404



```js
{
    "message": "Page not found.",
    "reason": "error-controller-cannot-dispatch",
    "display_exceptions": true,
    "controller": "V1\\Controller\\ToDoController",
    "controller_class": null
}
```



### 3. Create


Создание нового ToDo.


***Endpoint:***

```bash
Method: POST
Type: FORMDATA
URL: {{base_url}}/api/v1/todos
```



***Body:***

| Key | Value | Description |
| --- | ------|-------------|
| title | New todo |  |



***Responses:***


Status: Create validation error | Code: 422



```js
{
    "title": {
        "isEmpty": "Value is required and can't be empty"
    }
}
```



Status: Create success | Code: 201



```js
{
    "data": {
        "id": 6,
        "type": "todo",
        "attributes": {
            "title": "New todo",
            "user_id": 17,
            "is_completed": false,
            "created_at": "2019-04-22T17:55:27+00:00",
            "updated_at": "2019-04-22T17:55:27+00:00"
        }
    }
}
```



### 4. Update


Обновление todo.


***Endpoint:***

```bash
Method: PUT
Type: URLENCODED
URL: {{base_url}}/api/v1/todos/{{todo_id}}
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Content-Type | application/x-www-form-urlencoded |  |



***Body:***


| Key | Value | Description |
| --- | ------|-------------|
| title | Title |  |
| is_completed | 1 |  |



***Responses:***


Status: Update success | Code: 200



```js
{
    "data": {
        "id": 6,
        "type": "todo",
        "attributes": {
            "title": "Title",
            "user_id": 17,
            "is_completed": true,
            "created_at": "2019-04-22T17:55:27+00:00",
            "updated_at": "2019-04-22T17:57:31+00:00"
        }
    }
}
```



Status: Update validation error | Code: 422



```js
{
    "is_completed": {
        "isEmpty": "Value is required and can't be empty"
    }
}
```



### 5. Delete


Удаление todo.


***Endpoint:***

```bash
Method: DELETE
Type: RAW
URL: {{base_url}}/api/v1/todos/{{todo_id}}
```



***Responses:***


Status: Delete success | Code: 204



Status: Delete not found | Code: 404



```js
{
    "message": "Page not found.",
    "reason": "error-controller-cannot-dispatch",
    "display_exceptions": true,
    "controller": "V1\\Controller\\ToDoController",
    "controller_class": null
}
```



---
[Back to top](#todo-list-api)
> Made with &#9829; by [thedevsaddam](https://github.com/thedevsaddam) | Generated at: 2019-04-22 22:25:02 by [docgen](https://github.com/thedevsaddam/docgen)
