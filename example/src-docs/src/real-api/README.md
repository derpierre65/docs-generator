---
sidebar: [".\/README.md"]
sidebarDepth: 2
---

# Real API Example Resources

| Resource | Endpoint | Description |
|----------|----------|-------------|
| User | [Get Users](.//README.md#get-users) | Gets a list of all users. |
| User | [Update user](.//README.md#update-user) | Updates the user’s information. |
| User | [Get user](.//README.md#get-user) | Get user’s information. |
| User | [Acting as User](.//README.md#acting-as-user) | Start acting as an other user. |

<docs-Endpoint>

## User

</docs-Endpoint>

<docs-Endpoint>

### Get Users

<docs-ApiUrl type="GET" endpoint="https://api.example.org/v1/users" />

Gets a list of all users.

#### Response Body

| Field | Type | Description |
|-------|------|-------------|
| data | Array[] | No description |
| &nbsp;&nbsp;&nbsp;id | Integer | No description |
| &nbsp;&nbsp;&nbsp;username | String | No description |
| &nbsp;&nbsp;&nbsp;slug | String | No description |
| &nbsp;&nbsp;&nbsp;email | String | No description |
| &nbsp;&nbsp;&nbsp;overlay_token | String | No description |
| &nbsp;&nbsp;&nbsp;avatar | String | No description |
| &nbsp;&nbsp;&nbsp;acting_as | Object | No description |
| &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;id | Integer | No description |
| &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;username | String | No description |
| &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;slug | String | No description |
| &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;email | String | No description |
| &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;overlay_token | String | No description |
| &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;avatar | String | No description |
| &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;scopes | String[] | No description |
| &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;created_at | Datetime | No description |
| &nbsp;&nbsp;&nbsp;scopes | String[] | No description |
| &nbsp;&nbsp;&nbsp;created_at | Datetime | No description |

<docs-EndpointExample>

#### Example response

```json
{
    "data": [
        {
            "id": 42,
            "username": "hello world",
            "slug": "hello world",
            "email": "hello world",
            "overlay_token": "hello world",
            "avatar": "hello world",
            "acting_as": {
                "id": 42,
                "username": "hello world",
                "slug": "hello world",
                "email": "hello world",
                "overlay_token": "hello world",
                "avatar": "hello world",
                "scopes": [
                    "hello world"
                ],
                "created_at": "2022-01-01T00:00:00Z"
            },
            "scopes": [
                "hello world"
            ],
            "created_at": "2022-01-01T00:00:00Z"
        }
    ]
}
```

</docs-EndpointExample>
</docs-Endpoint>
<docs-Endpoint>

### Update user

<docs-ApiUrl type="PATCH" endpoint="https://api.example.org/v1/users/{user}" />

Updates the user’s information.

#### Response Body

| Field | Type | Description |
|-------|------|-------------|
| id | Integer | No description |
| username | String | No description |
| slug | String | No description |
| email | String | No description |
| overlay_token | String | No description |
| acting_as | Object | No description |
| &nbsp;&nbsp;&nbsp;id | Integer | No description |
| &nbsp;&nbsp;&nbsp;username | String | No description |
| &nbsp;&nbsp;&nbsp;slug | String | No description |
| &nbsp;&nbsp;&nbsp;email | String | No description |
| &nbsp;&nbsp;&nbsp;overlay_token | String | No description |
| &nbsp;&nbsp;&nbsp;avatar | String | No description |
| &nbsp;&nbsp;&nbsp;scopes | String[] | No description |
| &nbsp;&nbsp;&nbsp;created_at | Datetime | No description |
| scopes | String[] | No description |
| created_at | Datetime | No description |

<docs-EndpointExample>

#### Example response

```json
{
    "id": 42,
    "username": "hello world",
    "slug": "hello world",
    "email": "hello world",
    "overlay_token": "hello world",
    "acting_as": {
        "id": 42,
        "username": "hello world",
        "slug": "hello world",
        "email": "hello world",
        "overlay_token": "hello world",
        "avatar": "hello world",
        "scopes": [
            "hello world"
        ],
        "created_at": "2022-01-01T00:00:00Z"
    },
    "scopes": [
        "hello world"
    ],
    "created_at": "2022-01-01T00:00:00Z"
}
```

</docs-EndpointExample>
</docs-Endpoint>
<docs-Endpoint>

### Get user

<docs-ApiUrl type="GET" endpoint="https://api.example.org/v1/users/{user}" />

Get user’s information.

#### Response Body

| Field | Type | Description |
|-------|------|-------------|
| id | Integer | No description |
| username | String | No description |
| slug | String | No description |
| email | String | No description |
| overlay_token | String | No description |
| avatar | String | No description |
| acting_as | Object | No description |
| &nbsp;&nbsp;&nbsp;id | Integer | No description |
| &nbsp;&nbsp;&nbsp;username | String | No description |
| &nbsp;&nbsp;&nbsp;slug | String | No description |
| &nbsp;&nbsp;&nbsp;email | String | No description |
| &nbsp;&nbsp;&nbsp;overlay_token | String | No description |
| &nbsp;&nbsp;&nbsp;avatar | String | No description |
| &nbsp;&nbsp;&nbsp;scopes | String[] | No description |
| &nbsp;&nbsp;&nbsp;created_at | Datetime | No description |
| scopes | String[] | No description |
| created_at | Datetime | No description |

<docs-EndpointExample>

#### Example response

```json
{
    "id": 42,
    "username": "hello world",
    "slug": "hello world",
    "email": "hello world",
    "overlay_token": "hello world",
    "avatar": "hello world",
    "acting_as": {
        "id": 42,
        "username": "hello world",
        "slug": "hello world",
        "email": "hello world",
        "overlay_token": "hello world",
        "avatar": "hello world",
        "scopes": [
            "hello world"
        ],
        "created_at": "2022-01-01T00:00:00Z"
    },
    "scopes": [
        "hello world"
    ],
    "created_at": "2022-01-01T00:00:00Z"
}
```

</docs-EndpointExample>
</docs-Endpoint>
<docs-Endpoint>

### Acting as User

<docs-ApiUrl type="POST" endpoint="https://api.example.org/v1/user/acting-as/" />

Start acting as an other user.

#### Request Body Parameters

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| user_id | Integer | Yes | User ID of the other user. |

#### Response Body

| Field | Type | Description |
|-------|------|-------------|
| id | Integer | No description |
| username | String | No description |
| slug | String | No description |
| email | String | No description |
| overlay_token | String | No description |
| avatar | String | No description |
| scopes | String[] | No description |
| created_at | Datetime | No description |

<docs-EndpointExample>

#### Example response

```json
{
    "id": 42,
    "username": "hello world",
    "slug": "hello world",
    "email": "hello world",
    "overlay_token": "hello world",
    "avatar": "hello world",
    "scopes": [
        "hello world"
    ],
    "created_at": "2022-01-01T00:00:00Z"
}
```

</docs-EndpointExample>
</docs-Endpoint>