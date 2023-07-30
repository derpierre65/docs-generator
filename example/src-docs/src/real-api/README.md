---
sidebar: [".\/README.md"]
sidebarDepth: 2
---

# Real API Example Resources

| Resource | Endpoint | Description |
|----------|----------|-------------|
| User | [Get Users](.//README.md#get-users) | Gets a list of all users. |
| User | [Update user](.//README.md#update-user) | Updates the user’s information. |

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
| data | User[] | No description |

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
| avatar | String | No description |
| acting_as | User | No description |
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